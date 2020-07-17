<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Monster;
use App\ItemMonster;
use App\Map;
use App\Item;

class MonsterController extends Controller
{
    public function index() {
        $monsters = Monster::select('id','slug','icon','name_en','level','hp','element','race','size','type')->orderBy('name_en', 'asc')->paginate();

        return $monsters;
    }

    public function getMonster($id) {
        $monster =  Monster::where('slug', $id)
        ->select('id','slug','icon','name_en','level','hp','element','race','size','move_spd','mdef','atk','matk','def','hit','plvl','zone','type','job_exp','flee','base_exp','move_aspd','atk_spd','dex','agi','str','luk','int','vit','desc_en','star','plag')
        ->with('items')->first();

        return $monster;
    }

    public function saveMonsterItem() {

        $monsters = Monster::all();
        
        foreach ($monsters as $monster) {
            try {
                $json = file_get_contents('https://www.romcodex.com/api/monster/'.$monster->key_id);
                $result = json_decode($json, true);
                
                if (array_key_exists("LootData", $result)) {
                    $loots = $result['LootData'];
                    foreach ($loots as $key => $loot) {
                        $item = Item::where('key_id', $loot['id'])->first();
                        if (!$monster->items()->where('item_id', $item->id)->exists()) {
                            $monster->items()->attach($item, ['qty' => $loot['num']]);
                        }
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
