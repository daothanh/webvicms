<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->nullable();

            $table->integer('permission_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['permission_id', 'locale']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_translations', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
        });
        Schema::dropIfExists('permission_translations');
    }
}
