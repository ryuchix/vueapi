<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Monster;
use App\ItemMonster;
use App\Map;
use App\Item;

class MonsterController extends Controller
{

    private $elements__ = ['Dark','Earth','Fire','Ghost', 'Holy','Neutral','Poison', 'Undead','Water','Wind'];
    private $sizes__ = ['Small', 'Medium', 'Large'];
    private $types__ = ['Mini', 'MVP', 'Star', 'Undead'];
    private $races__ = ['Angel', 'Brute', 'DemiHuman', 'Demon', 'Dragon', 'Fish', 'Formless', 'Insect', 'Plant', 'Undead'];

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

    public function filterMonster(Request $request) {
        $q = Monster::query();

        if ($request->has('race')) {
            if ($request->race == 'All') {
                $q->whereIn('race', $this->races__);
            } else {
                $q->where('race', $request->race);
            }
        }

        if ($request->has('element')) {
            if ($request->element == 'All') {
                $q->whereIn('element', $this->elements__);
            } else {
                $q->where('element', $request->element);
            }
        }

        if ($request->has('size')) {
            if ($request->size == 'All') {
                $q->whereIn('size', ['S', 'M', 'L']);
            } else {
                $q->where('size', $request->size[0]);
            }
        }

        if ($request->has('type')) {
            if ($request->type == 'All') {
                $q->whereIn('type', ['MINI', 'MVP', 'Monster']);
            } elseif ($request->type == 'Star') {
                $q->where('star', 1);
            } elseif ($request->type == 'Undead') {
                $q->where('type', 'MVP')->where('element', 'Undead');
            } else {
                $q->where('type', $request->type);
            }
        }

        if ($request->has('order')) {
            if ($request->order == 'Name ASC') {
                return $q->orderBy('name_en', 'ASC')->paginate()->appends($request->all());
            }
            if ($request->order == 'Name Desc') {
                return $q->orderBy('name_en', 'DESC')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Level ASC') {
                return $q->orderByRaw('LENGTH(level) asc')->paginate()->appends($request->all());
            }
            if ($request->order == 'Level Desc') {
                return $q->orderByRaw('LENGTH(level) desc')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Base Exp') {
                return $q->orderByRaw('LENGTH(base_exp) desc')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Job Exp') {
                return $q->orderByRaw('LENGTH(job_exp) desc')->paginate()->appends($request->all());
            }
        } else {
            return $q->paginate()->appends($request->all());
        }
        
    }
}
