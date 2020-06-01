<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider__items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('slider_id');
            $table->string('url')->nullable();
            $table->string('url_target')->default('_blank');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('slider_id')->references('id')->on('slider__sliders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider__items');
    }
}
