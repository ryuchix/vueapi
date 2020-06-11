<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonstersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->integer('key_id')->nullable();
            $table->string('ShowName')->nullable();
            $table->string('Position')->nullable();
            $table->string('MoveSpd')->nullable();
            $table->string('MDef')->nullable();
            $table->string('NameZh')->nullable();
            $table->string('NameZh__EN')->nullable();
            $table->string('Atk')->nullable();
            $table->string('MAtk')->nullable();
            $table->string('Def')->nullable();
            $table->string('Icon')->nullable();
            $table->string('Hit')->nullable();
            $table->string('PassiveLv')->nullable();
            $table->string('Zone')->nullable();
            $table->string('Type')->nullable();
            $table->string('JobExp')->nullable();
            $table->string('Flee')->nullable();
            $table->string('BaseExp')->nullable();
            $table->string('move')->nullable();
            $table->string('Level')->nullable();
            $table->string('Hp')->nullable();
            $table->string('AtkSpd')->nullable();
            $table->string('Race')->nullable();
            $table->string('Shape')->nullable();
            $table->string('Nature')->nullable();
            $table->string('Scale')->nullable();
            $table->string('Dex')->nullable();
            $table->string('Agi')->nullable();
            $table->string('Str')->nullable();
            $table->string('Luk')->nullable();
            $table->string('Int')->nullable();
            $table->string('Vit')->nullable();
            $table->string('Desc__EN')->nullable();
            $table->string('IsStar')->nullable();
            $table->string('CopySkill')->nullable();
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
        Schema::dropIfExists('monsters');
    }
}
