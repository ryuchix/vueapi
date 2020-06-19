<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInMonstersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monsters', function (Blueprint $table) {
            $table->renameColumn('ShowName', 'show_name');
            $table->renameColumn('Position', 'position');
            $table->renameColumn('MoveSpd', 'move_spd');
            $table->renameColumn('MDef', 'mdef');
            $table->renameColumn('NameZh', 'name_ch');
            $table->renameColumn('NameZh__EN', 'name_en');
            $table->renameColumn('Atk', 'atk');
            $table->renameColumn('MAtk', 'matk');
            $table->renameColumn('Def', 'def');
            $table->renameColumn('Icon', 'icon');
            $table->renameColumn('Hit', 'hit');
            $table->renameColumn('PassiveLv', 'plvl');
            $table->renameColumn('Zone', 'zone');
            $table->renameColumn('Type', 'type');
            $table->renameColumn('JobExp', 'job_exp');
            $table->renameColumn('Flee', 'flee');
            $table->renameColumn('BaseExp', 'base_exp');
            $table->renameColumn('move', 'move_aspd');
            $table->renameColumn('Level', 'level');
            $table->renameColumn('Hp', 'hp');
            $table->renameColumn('AtkSpd', 'atk_spd');
            $table->renameColumn('Race', 'race');
            $table->renameColumn('Shape', 'size');
            $table->renameColumn('Nature', 'element');
            $table->renameColumn('Scale', 'scale');
            $table->renameColumn('Dex', 'dex');
            $table->renameColumn('Agi', 'agi');
            $table->renameColumn('Str', 'str');
            $table->renameColumn('Luk', 'luk');
            $table->renameColumn('Int', 'int');
            $table->renameColumn('Vit', 'vit');
            $table->renameColumn('Desc__EN', 'desc_en');
            $table->renameColumn('IsStar', 'star');
            $table->renameColumn('CopySkill', 'plag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monsters', function (Blueprint $table) {
            //
        });
    }
}
