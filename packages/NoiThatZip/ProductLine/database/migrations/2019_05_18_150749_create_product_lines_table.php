<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->tinyInteger('quantity');
            $table->integer('price');
            $table->integer('discount')->default(0);
            $table->integer('point')->default(0);
            $table->integer('amount')->default(0);
            $table->morphs('productable');
            $table->morphs('creator');
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
        Schema::dropIfExists('product_lines');
    }
}
