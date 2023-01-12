<?php
/**
 * Created by PhpStorm.
 * User: darryl
 * Date: 4/30/2017
 * Time: 10:58 AM
 */

namespace App\Http\Controllers;


use Darryldecode\Cart\CartCondition;
use Auth;
use App\Product;
use App\Order;
use App\OrderLine;
use App\Resource;
use App\Customer;
use NoiThatZip\Quote\Models\Quote;
use NoiThatZip\ProductLine\Models\ProductLine;
use NoiThatZip\Contact\Models\Contact;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use NoiThatZip\Costcenter\Models\Costcenter;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from

        if(request()->ajax())
            
        {
            $items = [];

            \Cart::session($userId)->getContent()->each(function($item) use (&$items)
            {
                $product = Product::findOrFail($item->id);
                $items[] = [
                    'id' => $item->id,
                    'name' => $product->name,
                    'quantity' => $item->quantity,
                   'code' => $product->code,
                   'price' => $item->price,
                   'discount' => $item->getPriceSumWithConditions() - $item->getPriceSum(),
                   'thanh_tien' => $item->getPriceSumWithConditions()
                ];
            });

            return response(array(
                'success' => true,
                'data' => $items,
                'message' => 'cart get items success'
            ),200,[]);
        }
        else
        {
            return view('cart');
        }
    }

    public function add()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from

        $id = request('id');
        $product = Product::findOrFail($id);
        $name = $product->name;
        $isDiscount = request('isDiscount');
        $discount = request('discount');
        $price = $product->price;
        if (!$isDiscount) {
            $price = request('price');
        }
        $qty = request('qty');

        $customAttributes = [
            'color_attr' => [
                'label' => 'red',
                'price' => 10.00,
            ],
            'size_attr' => [
                'label' => 'xxl',
                'price' => 15.00,
            ]
        ];

        $saleCondition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'SALE OFF',
            'type' => 'tax',
            'value' => $discount,
        ));

        $item = \Cart::session($userId)->add($id, $name, $price, $qty, $customAttributes, $saleCondition);

        return response(array(
            'success' => true,
            'data' => $item,
            'message' => "item added."
        ),201,[]);
    }

    public function addCondition()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from

        /** @var \Illuminate\Validation\Validator $v */
        $v = validator(request()->all(),[
            'name' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'value' => 'required|string',
        ]);

        if($v->fails())
        {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => $v->errors()->first()
            ),400,[]);
        }

        $name = request('name');
        $type = request('type');
        $target = request('target');
        $value = request('value');
        $order = request('order');

        $cartCondition = new CartCondition([
            'name' => $name,
            'type' => $type,
            'target' => $target, // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $value,
            'order' => $order,
            'attributes' => array()
        ]);

        \Cart::session($userId)->condition($cartCondition);

        return response(array(
            'success' => true,
            'data' => $cartCondition,
            'message' => "condition added."
        ),201,[]);
    }

    public function clearCartConditions()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from

        \Cart::session($userId)->clearCartConditions();

        return response(array(
            'success' => true,
            'data' => [],
            'message' => "cart conditions cleared."
        ),200,[]);


    }

    public function delete($id)
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from

        \Cart::session($userId)->remove($id);

        return response(array(
            'success' => true,
            'data' => $id,
            'message' => "cart item {$id} removed."
        ),200,[]);
    }

    public function clear()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from
        \Cart::session($userId)->clear();
        \Cart::session($userId)->clearCartConditions();
    }

    public function getcustomer(){
        $deliver_to = request()->session()->get('deliver_to');
        return  Customer::findOrFail($deliver_to);
    }

    public function getorder(){
        $order_id = request()->session()->get('order_id');
        // $a = Order::findOrFail($order_id);
        // dd($a);
        return Order::findOrFail($order_id);
    }

    public function loadcart()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from
        \Cart::session($userId)->clear();
        $so_number = request('so');
        $order = Order::UpdateOrCreate([
            'so_number' => $so_number
        ]);
        if (!Auth::User()->hasRole('asm')) {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Ban khong co quyen!'
            ),400,[]);
        }

        request()->session()->put('deliver_to', $order->deliver_to);
        request()->session()->put('order_id', $order->id);
        $customAttributes = [
            'color_attr' => [
                'label' => 'red',
                'price' => 10.00,
            ],
            'size_attr' => [
                'label' => 'xxl',
                'price' => 15.00,
            ]
        ];

        if ($order->vat) {
            $cartCondition = new CartCondition([
            'name' => 'VAT 10%',
            'type' => 'VAT',
            'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '10%',
            'order' => 2,
            'attributes' => array()
            ]);
            \Cart::session($userId)->condition($cartCondition);
        }

        if ($order->qgg) {
            $cartCondition = new CartCondition([
            'name' => 'Mã giảm giá',
            'type' => 'QGG',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '-0' . $order->qgg,
            'order' => 1,
            'attributes' => array()
            ]);
            \Cart::session($userId)->condition($cartCondition);
        }

        if ($order->fee_vc) {
            $cartCondition = new CartCondition([
            'name' => 'Phí vận chuyển',
            'type' => 'FEE_VC',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $order->fee_vc,
            'order' => 1,
            'attributes' => array()
            ]);
            \Cart::session($userId)->condition($cartCondition);
        }

        if ($order->fee_ld) {
            $cartCondition = new CartCondition([
            'name' => 'Phí lắp đặt',
            'type' => 'FEE_LD',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $order->fee_ld,
            'order' => 1,
            'attributes' => array()
            ]);
            \Cart::session($userId)->condition($cartCondition);
        }

        foreach ($order->orderlines as $orderline) {
            $product = Product::UpdateOrCreate([
                'code' => $orderline->item
            ]);
            $saleCondition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'SALE OFF',
                'type' => 'tax',
                'value' => $orderline->discount . '%',
            ));
            \Cart::session($userId)->add($product->id, $product->name, $orderline->price, $orderline->quantity, $customAttributes, $saleCondition);
        }
    }

    public function checkout()
    {
        // dd(request()->all());
        /** @var \Illuminate\Validation\Validator $v */
        $v = validator(request()->all(),[
            'so_number' => 'required',
            'phone' => 'required|string|max:10|min:10',
            'name' => 'required',
            'address' => 'required',
            'pre_pay' => 'required|numeric',
            'delivery' => 'required',
            'qgg' => 'required',
            'fee_vc' => 'required',
            'fee_ld' => 'required',
            'costcenter_id' => 'required',
            'soType' => 'required',
            'warehouse_id' => 'required'
        ],
        [
            'so_number.required'=>'Số đơn hàng',
            'phone.required'=>'Số điện thoại',
            'name.required'=>'Tên khách hàng',
            'address.required'=>'Địa chỉ khách hàng',
            'pre_pay.required'=>'Tiền đặt cọc',
            'delivery.required'=>'Ngày giao hàng',
            'costcenter_id.required'=>'Showroom',
            'warehouse_id.required'=>'Chọn tỉnh giao hàng',
            'soType.required'=>'Chọn loại đơn hàng'
        ]);

        if($v->fails())
        {
            return response(array(
                'success' => false,
                'data' => request()->all(),
                'message' => $v->errors()
            ),400,[]);
        }

        $user =Auth::user();
        $userId = $user->id;
        $costcenter = Costcenter::find(request('costcenter_id'));
        $costcenter_chia = Costcenter::find(request('costcenter_chia'));
        $customer = Customer::UpdateOrCreate([
            'phone' => request('phone')
        ]);
        $customer->name = request('name');
        $customer->address_line1 = request('address');
        $customer->save();
        $so = Order::firstOrNew([
            'so_number' => request('so_number')
        ]);

        if ($so->status_id == 2) {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => [ 'error' => 'Đơn hàng đã duyệt' ]
            ),400,[]);
        }
        $so->type = request('soType');
        $so->description = request('soType') . '_' . request('so_number');
        $so->your_ref    = request('so_number');
        $so->resource    = $userId;
        $so->ordered_by  = $customer->id;
        $so->deliver_to  = $customer->id;
        $so->invoice_to  = $customer->id;
        // $so->warehouse_id  = $request['warehouse_id']; // add 8/12
        $so->warehouse    = \NoiThatZip\Warehouse\Models\Warehouse::find(request('warehouse_id'))->code;
        $so->delivery_method = 'DB';
        $so->delivery_date = request('delivery');
        $so->start_date = request('start_date');
        $so->costcenter = $costcenter->code;
        if ($costcenter_chia) {
            $so->cos_chia = $costcenter_chia->code;
        }
        $so->user_id = $userId;
        $so->pre_pay = request('pre_pay');
        $so->journal_id = request('journal_id');
        $so->qgg = request('qgg');
        $so->fee_vc = request('fee_vc');
        $so->fee_ld = request('fee_ld');        
        $so->event = request('back');
        $so->amount = \Cart::session($userId)->getTotal();
        $so->vat = \Cart::session($userId)->getTotal() - \Cart::session($userId)->getSubTotal();
        $so->subtotal = \Cart::session($userId)->getSubTotal();
        //$so->type = request('soType');
        $so->note = request('note');
        $so->trangthai = request('trangthai');
        $so->status_id = 2;
        $so->save();
        $so->syncCostcenters([$costcenter->id]);
        $items = \Cart::session($userId)->getContent();
        DB::table('order_lines')->where('order_id', '=', $so->id)->delete();
        foreach ($items as $item) {
            $product = Product::find($item['id']);

            $orderline = OrderLine::UpdateOrCreate([
                'product_id' => $item['id'],
                'item' => $product->code,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'discount' => (($item->getPriceSum() - $item->getPriceSumWithConditions())/$item->getPriceSum())*100,
                'amount' => $item->getPriceSumWithConditions(),
                'order_id' => $so->id,
                'point' => $product->point,
                'delivery' => request('delivery')
            ]);
        }
        Helper::orderToXml($so->id);
        activity()
        ->performedOn($so)
        ->causedBy($user)
        ->withProperties(['zip' => 'saleOrder','id' => $so->so_number])
        ->log('Tạo đơn hàng :subject.so_number, bởi :causer.name');
        return $so;
    }

    public function taobaogia()
    {
        /** @var \Illuminate\Validation\Validator $v */
        $v = validator(request()->all(),[
            'fee_vc' => 'required',
            'fee_ld' => 'required',

        ]);

        if($v->fails())
        {
            return response(array(
                'success' => false,
                'data' => request()->all(),
                'message' => $v->errors()
            ),400,[]);
        }

        $user =Auth::user();
        $userId = $user->id; // get this from session or wherever it came from
        $resource = $user->resource;
        $so_number = request('subject');

        $quote = Quote::Create([
            'subject' => request('subject'),
            'quotestage' => request('quotestage'),
            'contact_id' => request('contact_id'),
            'subtotal' => request('subtotal'),
            'total' => request('total'),
            'discount_amount' => request('discount_amount'),
            'fee_vc' => request('fee_vc'),
            'fee_ld' => request('fee_ld')
        ]);
        $items = \Cart::session($userId)->getContent();
        DB::table('quote_items')->where('quote_id', '=', $quote->id)->delete();
        foreach ($items as $item) {
            QuoteItem::Create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'listprice' => $item['price'],
                'discount_percent' => (($item->getPriceSum() - $item->getPriceSumWithConditions())/$item->getPriceSum())*100,
                'quote_id' => $quote->id,
            ]);
        }
        return $quote;
    }

    public function details()
    {
        $userId = Auth::User()->id; // get this from session or wherever it came from
        $total = \Cart::session($userId)->getTotal();
        $subtotal = \Cart::session($userId)->getSubTotal();
        $vat = 0;

        $qgg = 0;
        $vat_condition = \Cart::getCondition('Mã giảm giá');
        if ($vat_condition) {
            $qgg = $vat_condition->getCalculatedValue($subtotal);
        }

        $phiVanChuyen = 0;
        $vat_condition = \Cart::getCondition('Phí vận chuyển');
        if ($vat_condition) {
            $phiVanChuyen = $vat_condition->getCalculatedValue($subtotal);
        }

        $phiLapDat = 0;
        $vat_condition = \Cart::getCondition('Phí lắp đặt');
        if ($vat_condition) {
            $phiLapDat = $vat_condition->getCalculatedValue($subtotal);
        }

        $vat_condition = \Cart::getCondition('VAT 10%');
        if ($vat_condition) {
            $vat = $vat_condition->getCalculatedValue($subtotal - $phiLapDat - $phiVanChuyen);
        }
        return response(array(
            'success' => true,
            'data' => array(
                'total_quantity' => \Cart::session($userId)->getTotalQuantity(),
                'sub_total' => $subtotal,
                'total' => $total,
                'vat' => $vat,
                'qgg' => $qgg,
                'fee_vc' => $phiVanChuyen,
                'fee_ld' => $phiLapDat,
            ),
            'message' => "Get cart details success."
        ),200,[]);
    }

    public function storeQuote()
    {
        $user = Auth::User();
        //return \Cart::session($user->id)->getContent();
        $id = request()->session()->get('contact_id');
        $contact = Contact::findOrFail($id);
        $quote = $contact->quote(request()->all(), $user);
        
        \Cart::session($user->id)->getContent()->each(function($item) use(&$quote,$user)
        {
            $quote->productLine([
                'product_id' => $item->id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
                'discount'   => $item->discount,
                'point'      => Product::find($item->id)->point,
                'amount'     => $item->getPriceSumWithConditions()   
            ], $user);
        });
        return $quote;
    }

    public function loadFromQuote($id)
    {
        $quote = Quote::findOrFail($id);
        return $productLines = $quote->productLines;

    }
}