<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NoiThatZip\Lead\Models\Lead;
use NoiThatZip\Contact\Models\Contact;
use App\Exports\ProjectExport;
use App\Project;
use App\User;
use App\Product;
use App\Customer;
use App\Order;
use App\Exports\ContactsExport;
use App\Http\Resources\Contact as ContactResource;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class ContactsController extends Controller
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setContact($id)
    {
        request()->session()->put('contact_id', $id);
        return $id;
    }

    public function getContact(){
        $id = request()->session()->get('contact_id');
        $contact = Contact::findOrFail($id);
        $customer = Customer::where('phone',$contact->phone)->first();
        $item = $contact->toArray();
        //$item['tao_boi'] = User::find($item->user_id)->name;
        $item['activities'] = $contact->activities;
        $item['comments'] = $contact->comments;
        $item['tasks'] = $contact->tasks;
        $item['attachmens'] = $contact->attachmens;
        $item['coscenters'] = $contact->coscenters;
        $item['note'] = $contact->status;
        $item['category'] = $contact->categoriesList();
        if ($customer) {
            $item['orders'] = Order::where('deliver_to',$customer->id)->get();
        }
        
        return $item;
    }

    public function clearContact()
    {
        request()->session()->forget('contact_id');
    }

    public function getquote(){
        $id = request()->session()->get('contact_id');
        $contact = Contact::findOrFail($id);
        $quotes = $contact->quotes;
        $quotes->map(function($quote){
            $quote->quotestage = $quote->status;
            if(!$quote->quotestage){
                $quote->quotestage = "Mới tạo";
            }
        });
        return $quotes;
    }

    public function luutailieu(Request $request){
        return $request;
    }

    public function export(Request $request) 
    {
        $user = Auth::User();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }
        $contacts = Contact::whereBetween('start_date',['2019-12-30','2020-12-31'])->orderBy('start_date','desc')->get();
        $count = 0;
        $contact = $contacts->map(function ($value) use ($count){
            $count += 1;
            $a = '';
            $b = array();
            if ($value->status) {
                $a = "Đang chăm sóc";
            }
            if ($value->status == 1) {
                $a = "Đang chăm sóc";
            }
            if ($value->status == 2) {
                $a = "Tạm dừng";
            }
            if ($value->status == 3) {
                $a = "Gần đơn hàng";
            }
            if ($value->status == 4) {
                $a = "Đơn hàng chờ";
            }
            if ($value->status == 5) {
                $a = "Đơn hàng";
            }
            if ($value->status == 8) {
                $a = "Tiềm Năng Xa";
            }
            if ($value->status == 6) {
                $a = "Gọi Điện";
            }
            if ($value->status == 7) {
                $a = "Có Cuộc Hẹn Tại Nhà";
            }
            if ($value->status == 9) {
                $a = "Có Cuộc Hẹn Tại Showroom";
            }
            if ($value->status == 10) {
                $a = "Cuộc Hẹn Tại Nhà";
            }
            if ($value->status == 11) {
                $a = "Cuộc Hẹn Tại Showroom";
            }
            $c = Product::select('id','code as name')->whereIn('id',$value->productLines()->pluck('product_id'))->get();
            if (count($c) > 0 ) {
                foreach ($c as $key => $v) {
                    $b[] = $v->name;
                }
            }

            return [
                'stt' => $count,
                // 'id' => $value->id,
                'costcenters' => count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '',
                'title'      => $value->title,
                'last_name' => $value->last_name,
                'phone'      => $value->phone,
                'start_date' => $value->created_at,
                'start_time' => $value->start_time,
                'end_time' => $value->end_time,
                'note' => $value->note,
                'description' => $value->description,
                'created_at' => $value->created_at,
                'status'     => $a,
                'products'     => implode(',', $b),
            ];
        });
        return Excel::download(new ContactsExport($contact), 'P5KHTN.xlsx');
    }

    public function exportlead(Request $request) 
    {
        $user = Auth::User();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }
        $contacts = Lead::whereBetween ('start_date',['2019-12-30','2020-12-31'])->orderBy('start_date','desc')->get();
        $count = 0;
        $contact = $contacts->map(function ($value) use ($count){
            $a = '';
            $count += 1;

            $b = array();
            if ($value->statuskh) {
                $a = "Đang chăm sóc";
            }
            if ($value->statuskh == 1) {
                $a = "Đang chăm sóc";
            }
            if ($value->statuskh == 2) {
                $a = "Tạm dừng";
            }
            if ($value->statuskh == 3) {
                $a = "Gần đơn hàng";
            }
            if ($value->statuskh == 4) {
                $a = "Đơn hàng chờ";
            }
            if ($value->statuskh == 5) {
                $a = "Đơn hàng";
            }
            if ($value->statuskh == 8) {
                $a = "Tiềm Năng Xa";
            }
            if ($value->statuskh == 6) {
                $a = "Gọi Điện";
            }
            if ($value->statuskh == 7) {
                $a = "Có Cuộc Hẹn Tại Nhà";
            }
            if ($value->statuskh == 9) {
                $a = "Có Cuộc Hẹn Tại Showroom";
            }
            if ($value->statuskh == 10) {
                $a = "Cuộc Hẹn Tại Nhà";
            }
            if ($value->statuskh == 11) {
                $a = "Cuộc Hẹn Tại Showroom";
            }
            $c = Product::select('id','code as name')->whereIn('id',$value->productLines()->pluck('product_id'))->get();
            if (count($c) > 0 ) {
                foreach ($c as $key => $v) {
                    $b[] = $v->name;
                }
            }
            return [
                'stt' => $count,
                // 'id' => $value->id,
                'costcenters' => count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '',
                'title'      => $value->title,
                'last_name' => $value->last_name,
                'phone'      => $value->phone,
                'start_date' => $value->start_date,
                'start_time' => $value->start_time,
                'end_time' => $value->end_time,
                'status'     => $a,
                'products'     => implode(',', $b),
            ];
        });
        return Excel::download(new ContactsExport($contact), 'P5KH15.xlsx');
    }

    public function exportProject(Request $request) 
    {
        $user = \Auth::User();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }

        $contacts = Project::filter($request->all())->get();
        return Excel::download(new ProjectExport($contacts), 'duan.xlsx');
    }
}
