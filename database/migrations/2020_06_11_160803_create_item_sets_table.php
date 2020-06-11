<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_set', function (Blueprint $table) {
            $table->integer('item_id')->index();
            $table->string('EffectDesc')->nullable();
            $table->string('EffectDesc__EN')->nullable();
            $table->string('EquipSuitDsc')->nullable();
            $table->string('EquipSuitDsc__EN')->nullable();
            $table->string('items')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_sets');
    }
}
