<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNpcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('npcs', function (Blueprint $table) {
            $table->id();
            $table->integer('map_id')->nullable();
            $table->text('NameZh__EN')->nullable();
            $table->text('Nature')->nullable();
            $table->text('NPC')->nullable();
            $table->string('Icon')->nullable();
            $table->text('Position__EN')->nullable();
            $table->text('Guild__EN')->nullable();
            $table->text('Desc__EN')->nullable();
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
        Schema::dropIfExists('npcs');
    }
}
