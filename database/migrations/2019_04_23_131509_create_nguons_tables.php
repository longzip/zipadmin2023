<?php

declare(strict_types=1);

/*
 * This file is part of Laravel nguons.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

class CreateNguonsTables extends Migration
{
    public function up()
    {
        Schema::create('nguons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('default');
            NestedSet::columns($table);
            $table->timestamps();
        });

        Schema::create('model_has_nguons', function (Blueprint $table) {
            $table->integer('nguon_id');
            $table->morphs('model');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nguons');
        Schema::dropIfExists('model_has_nguons');
    }
}
