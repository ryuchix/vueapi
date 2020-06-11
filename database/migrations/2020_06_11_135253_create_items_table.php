<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('key_id')->nullable();
            $table->string('SellPrice')->nullable();
            $table->string('Icon')->nullable();
            $table->string('Desc')->nullable();
            $table->string('Desc__EN')->nullable();
            $table->string('Type')->nullable();
            $table->string('NameZh')->nullable();
            $table->string('NameZh__EN')->nullable();
            $table->string('AuctionPrice')->nullable();
            $table->string('TypeName')->nullable();
            $table->string('ComposeOutputID')->nullable();
            $table->string('ComposeID')->nullable();
            $table->string('Stat')->nullable();
            $table->text('StatExtra')->nullable();
            $table->string('StatType')->nullable();
            $table->string('ItemSet')->nullable();
            $table->string('ComposeRecipe')->nullable();
            $table->string('SynthesisRecipe')->nullable();
            $table->string('PriorEquipment')->nullable();
            $table->string('TierList')->nullable();
            $table->string('UnlockEffect')->nullable();
            $table->string('DepositEffect')->nullable();
            $table->string('CanEquip')->nullable();
            

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
        Schema::dropIfExists('items');
    }
}
