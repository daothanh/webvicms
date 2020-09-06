<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodTranslationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerce__payment_method_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_method_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');

            $table->unique(['payment_method_id', 'locale']);
            $table->foreign('payment_method_id')->references('id')->on('commerce__payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commerce__payment_method_translations');
    }
}
