<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('SellPrice', 'sell_price');
            $table->renameColumn('Icon', 'icon');
            $table->renameColumn('Desc', 'desc');
            $table->renameColumn('Desc__EN', 'desc_en');
            $table->renameColumn('Type', 'type');
            $table->renameColumn('NameZh', 'name_ch');
            $table->renameColumn('NameZh__EN', 'name_en');
            $table->renameColumn('AuctionPrice', 'auction_price');
            $table->renameColumn('TypeName', 'type_name');
            $table->renameColumn('ComposeOutputID', 'compose_output_id');
            $table->renameColumn('ComposeID', 'compose_id');
            $table->renameColumn('Stat', 'stat');
            $table->renameColumn('StatExtra', 'stat_extra');
            $table->renameColumn('StatType', 'stat_type');
            $table->renameColumn('ItemSet', 'item_set');
            $table->renameColumn('ComposeRecipe', 'compose_recipe');
            $table->renameColumn('SynthesisRecipe', 'synthesis_recipe');
            $table->renameColumn('PriorEquipment', 'prior_equipment');
            $table->renameColumn('TierList', 'tier_list');
            $table->renameColumn('UnlockEffect', 'unlock_effect');
            $table->renameColumn('DepositEffect', 'deposit_effect');
            $table->renameColumn('CanEquip', 'can_equip');
            $table->renameColumn('Quality', 'quality');      
        });

        Schema::table('maps', function (Blueprint $table) {
            $table->renameColumn('Desc__EN', 'desc_en');
            $table->renameColumn('NameEn', 'name_enn');
            $table->renameColumn('Type', 'type');
            $table->renameColumn('NameZh', 'name_ch');
            $table->renameColumn('NameZh__EN', 'name_en');
            $table->renameColumn('MapArea', 'area');
        });

        Schema::table('item_sets', function (Blueprint $table) {
            $table->renameColumn('EffectDesc', 'effect_desc');
            $table->renameColumn('EffectDesc__EN', 'effect_desc_en');
            $table->renameColumn('EquipSuitDsc', 'equip_suit_desc');
            $table->renameColumn('EquipSuitDsc__EN', 'equip_suit_desc_en');
        });

        Schema::table('npcs', function (Blueprint $table) {
            $table->renameColumn('NameZh__EN', 'name_en');
            $table->renameColumn('Nature', 'nature');
            $table->renameColumn('NPC', 'npc');
            $table->renameColumn('Icon', 'icon');
            $table->renameColumn('Position__EN', 'position_en');
            $table->renameColumn('Guild__EN', 'guild_en');
            $table->renameColumn('Desc__EN', 'desc_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
