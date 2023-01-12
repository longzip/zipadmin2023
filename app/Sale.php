<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //protected $primaryKey = 'transaction_id';
    protected $fillable = ['transaction_id', 'invoice_number', 'cashier', 'date', 'type', 'total_amount', 'due_date', 'name', 'month', 'year', 'balance', 'date_delivery', 'amount', 'cash', 'p_amount', 'vat', 'status', 'type_SO', 'SOnumber', 'SM', 'payment', 'note', 'status_del', 'fee', 'fee_ld', 'cashier_id', 'customer_address', 'customer_contact'];
}
