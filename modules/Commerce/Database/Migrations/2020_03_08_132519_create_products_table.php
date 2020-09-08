<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerce__products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('sale_price',12, 2)->nullable();
            $table->string('currency', 20)->default('VND')->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commerce__products');
    }
}
