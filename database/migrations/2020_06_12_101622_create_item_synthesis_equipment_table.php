<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSynthesisEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_synthesis_equipment', function (Blueprint $table) {
            $table->id();
            $table->integer('item_syntheses_id')->index();
            $table->integer('item_id')->index();
            $table->string('tier')->index();
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
        Schema::dropIfExists('item_synthesis_equipment');
    }
}
