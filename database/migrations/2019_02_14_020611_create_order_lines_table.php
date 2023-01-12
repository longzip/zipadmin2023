<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('item')->nullable();
            $table->tinyInteger('quantity')->nullable();
            $table->integer('price')->nullable();
            $table->integer('amount')->nullable();
            $table->tinyInteger('vat')->nullable();
            $table->tinyInteger('discount')->nullable();
            $table->date('delivery')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('recipe')->nullable();
            $table->string('costcenter')->nullable();
            $table->string('resource')->nullable();
            $table->integer('point')->nullable();
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
        Schema::dropIfExists('order_lines');
    }
}
