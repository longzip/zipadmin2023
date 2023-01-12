<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\CartCondition;
use NoiThatZip\Quote\Models\Quote;
use Auth;
use App\Customer;
use NoiThatZip\Contact\Models\Contact;
use App\Product;
use NoiThatZip\Quote\Http\Resources\Quote as QuoteResource;

class QuotesController extends Controller
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

        public function flashQuote(Request $request, $id)
    {
        $request->session()->put('quote_id', $id);
        return $request->session()->get('quote_id');
    }

    public function flushQuote(Request $request){
        return $request->session()->get('quote_id');
    }

    public function quoteitems($id){
    	$quote = Quote::findOrFail($id);
    	$quoteitems = $quote->quoteitems;
        $quoteitems->each(function($quoteitem){
            $quoteitem->description = $quoteitem->product->name;

        });
        return $quoteitems;
    }

    public function quoteStart($id){
        request()->session()->put('quote_id', $id);
        $quote = Quote::findOrFail($id);
        $userId = Auth::User()->id; // get this from session or wherever it came from
        //return 'test' . $userId;
        \Cart::session($userId)->clear();
        \Cart::session($userId)->clearCartConditions();
        $quote->productLines->each(function($quoteitem) use ($userId){
            $saleCondition = new CartCondition(array(
                'name' => 'SALE OFF',
                'type' => 'tax',
                'value' => '-0' . ($quoteitem->discount/$quoteitem->quantity)
            ));
            \Cart::session($userId)->add($quoteitem->product_id, 'Test', $quoteitem->price, $quoteitem->quantity, array(), $saleCondition);
        });

        //
        if (isset($quote->vat) && $quote->vat !=0) {
            $vatCondition = new CartCondition([
                'name' => 'VAT 10%',
                'type' => 'VAT',
                'target' => 'total',
                'value' => $quote->vat == 0 ? '0' : '10%',
                'order' => 2,
                'attributes' => array()
            ]);
            \Cart::session($userId)->condition($vatCondition);
        }

        if (isset($quote->qgg) && $quote->qgg !=0) {
            $qggCondition = new CartCondition([
                'name' => 'Mã giảm giá',
                'type' => 'QGG',
                'target' => 'subtotal',
                'value' => '-0' . $quote->qgg,
                'order' => '0',
                'attributes' => array()
            ]);
            \Cart::session($userId)->condition($qggCondition);
        }

        if (isset($quote->fee_vc) && $quote->fee_vc !=0) {
            $feevcCondition = new CartCondition([
                'name' => 'Phí vận chuyển',
                'type' => 'FEE_VC',
                'target' => 'subtotal',
                'value' => $quote->fee_vc,
                'order' => 1,
                'attributes' => array()
            ]);
            \Cart::session($userId)->condition($feevcCondition);
        }
        if (isset($quote->fee_ld) && $quote->fee_ld !=0) {
            $feeldCondition = new CartCondition([
                'name' => 'Phí lắp đặt',
                'type' => 'FEE_LD',
                'target' => 'subtotal',
                'value' => $quote->fee_ld,
                'order' => 1,
                'attributes' => array()
            ]);
            \Cart::session($userId)->condition($feeldCondition);
        }

        // $contact = Contact::find($quote->contact_id);
        // $customer = Customer::UpdateOrCreate([
        //     'phone' => $contact->phone
        // ]);
        // $customer->name = $contact->last_name;
        // $customer->address_line1 = $contact->address;

        // $customer->save();
        request()->session()->forget(['deliver_to', 'order_id']);
        //request()->session()->put('deliver_to', $customer->id);
        request()->session()->put('order_id', 0);
    }

    public function quoteDestroy(){
        $userId = Auth::User()->id; // get this from session or wherever it came from
        \Cart::session($userId)->clear();
        \Cart::session($userId)->clearCartConditions();
        $order_id = request()->session()->get('order_id');
        $quote_id = request()->session()->get('quote_id');
        if($order_id == 0){
            $quote = Quote::find($quote_id);
            $quote->setStatus('Đơn hàng');
            $contact = Contact::find($quote->contact_id);
            $contact->setStatus('Đơn hàng');
        }
        request()->session()->forget(['deliver_to', 'order_id', 'quote_id']);
    }

    public function print($id){
        $quote = Quote::find($id);
       return view('quotes.print', ['quote' => $quote]);
    }
}
