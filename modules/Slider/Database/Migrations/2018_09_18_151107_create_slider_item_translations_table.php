<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderItemTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider__item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slider_item_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->unique(['slider_item_id', 'locale']);
            $table->foreign('slider_item_id', 'slider_item_trans')->references('id')->on('slider__items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider__item_translations');
    }
}
