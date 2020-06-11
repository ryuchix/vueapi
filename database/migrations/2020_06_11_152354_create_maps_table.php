<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->integer('key_id')->nullable();
            $table->string('Desc__EN')->nullable();
            $table->string('NameEn')->nullable();
            $table->string('Type')->nullable();
            $table->string('NameZh')->nullable();
            $table->string('NameZh__EN')->nullable();
            $table->string('MapArea')->nullable();
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
        Schema::dropIfExists('maps');
    }
}
