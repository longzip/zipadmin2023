<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrdersExportDoanhThu implements FromCollection,WithHeadings,ShouldAutoSize,WithStrictNullComparison
{
    public function __construct($year){
        $this->year = $year;
    }

    public function collection()
    {
        // dd($this->year);
    	$customer_data = \DB::table('orders')->select('id','so_number','warehouse','start_date','costcenter','delivery_date','subtotal','amount','vat','fee_ld','fee_vc','qgg')->wherebetween('delivery_date',$this->year)->OrderBy('delivery_date','desc')->get();
        foreach ($customer_data as $value) {
            $orders_line = \DB::table('order_lines')->where('order_id',$value->id)->select('order_id','item','quantity','price','amount')->get();
            foreach ($orders_line as $val) {
                $b = array_change_key_case((array)$val,CASE_UPPER);
                $get_order =array_merge($b, (array) $value);
                $get_arr[]= (object)$get_order;
            }
        }
        $a=(object)$get_arr;
        // var_dump($orders_line);
        // var_dump($a);
        // dd($a);
        return $a;
    }

    //Thêm hàng tiêu đề cho bảng
    public function headings() :array {
    	return ['Đơn Hàng','Sản Phẩm','Số lượng','Giá/ sản phẩm','Giá trước ck/sp','Giá sau ck/sp','Ngày giao','Ngày Kí Đơn','Kho','SR'];
    }

}
