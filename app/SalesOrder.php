<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_order';
    //protected $primaryKey = 'transaction_id';
    protected $fillable = ['transaction_id', 'invoice', 'product', 'qty', 'amount', 'name', 'price', 'discount', 'category', 'date', 'omonth', 'oyear', 'qtyleft', 'dname', 'vat', 'total_amount', 'date_delivery', 'mau', 'SOnumber', 'sale_id'];
}
