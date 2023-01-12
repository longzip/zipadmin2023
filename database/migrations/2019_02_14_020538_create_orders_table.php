<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_number')->nullable();
            $table->string('description')->nullable();
            $table->string('your_ref')->nullable();
            $table->string('resource')->nullable();
            $table->string('ordered_by')->nullable();
            $table->string('deliver_to')->nullable();
            $table->string('invoice_to')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('delivery_method')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('costcenter')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('user_name')->nullable();
            $table->integer('pre_pay')->nullable();
            $table->integer('status_id')->nullable();
            $table->datetime('export')->nullable();
            $table->integer('journal_id')->nullable();
            $table->integer('glaccount_id')->nullable();
            $table->integer('debit')->nullable();
            $table->integer('credit')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('vat')->default(0);
            $table->integer('fee_kh')->default(0);
            $table->integer('fee_ld')->default(0);
            $table->integer('fee_vc')->default(0);
            $table->integer('qgg')->default(0);
            $table->integer('subtotal')->default(0);
            $table->integer('type')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
