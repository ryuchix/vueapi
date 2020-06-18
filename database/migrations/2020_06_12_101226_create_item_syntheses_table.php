<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSynthesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_syntheses', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->index();
            $table->integer('item_output')->nullable();
            $table->string('cost')->nullable();
            $table->string('isInput')->nullable();
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
        Schema::dropIfExists('item_syntheses');
    }
}
