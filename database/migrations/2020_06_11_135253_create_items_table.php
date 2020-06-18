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
            $table->text('Desc__EN')->nullable();
            $table->string('Type')->nullable();
            $table->text('NameZh')->nullable();
            $table->text('NameZh__EN')->nullable();
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
            $table->text('UnlockEffect')->nullable();
            $table->text('DepositEffect')->nullable();
            $table->text('CanEquip')->nullable();
            $table->string('Quality')->nullable();
            

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
