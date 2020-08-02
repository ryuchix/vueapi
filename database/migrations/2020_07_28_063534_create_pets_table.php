<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->string('unlock')->nullable();
            $table->integer('capture_type')->nullable();
            $table->text('compose')->nullable();
            $table->text('food')->nullable();
            $table->integer('key_id')->nullable();
            $table->text('description')->nullable();
            $table->string('element')->nullable();
            $table->string('race')->nullable();
            $table->string('size')->nullable();
            $table->text('skill')->nullable();
            $table->integer('star')->nullable();
            $table->integer('safe')->nullable();
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('pets');
    }
}
