<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerce__product_color', function (Blueprint $table) {
            $table->bigInteger('color_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->primary(['color_id', 'product_id']);
            $table->foreign('color_id')->references('id')->on('commerce__colors')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('commerce__products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commerce__product_color');
    }
}
