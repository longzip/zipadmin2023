<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrdersExport implements FromCollection,WithHeadings,ShouldAutoSize,WithStrictNullComparison
{
    public function __construct($year){
        $this->year = $year;
    }

    public function collection()
    {
        // dd($this->year);
    	$customer_data = \DB::table('order_delivery')->select('order','item','quantity','price','amount','delivery','startorder','warehouse','costcenter')->wherebetween('delivery',$this->year)->OrderBy('delivery','desc')->get();
        // dd($customer_data);
        return $customer_data;
    }

    //Thêm hàng tiêu đề cho bảng
    public function headings() :array {
    	return ['Đơn Hàng','Sản Phẩm','Số lượng','Giá/ sản phẩm','Giá sau ck/sp','Ngày giao','Ngày Kí Đơn','Kho','SR'];
    }

}
