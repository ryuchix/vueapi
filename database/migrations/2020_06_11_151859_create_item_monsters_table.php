<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMonstersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_monster', function (Blueprint $table) {
            $table->integer('item_id')->unsigned()->index();
            $table->integer('monster_id')->unsigned()->index();
            $table->primary(['item_id', 'monster_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_monsters');
    }
}
