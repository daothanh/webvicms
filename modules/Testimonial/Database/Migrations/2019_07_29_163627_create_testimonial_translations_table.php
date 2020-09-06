<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonialTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('position');
            $table->string('content')->nullable();

            $table->bigInteger('testimonial_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['testimonial_id', 'locale']);
            $table->foreign('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimonial_translations');
    }
}
