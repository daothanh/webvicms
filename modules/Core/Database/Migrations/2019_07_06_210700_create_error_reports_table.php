<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->string('message')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->text('data')->nullable();
            $table->mediumText('trace')->nullable();
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
        Schema::dropIfExists('error_reports');
    }
}
