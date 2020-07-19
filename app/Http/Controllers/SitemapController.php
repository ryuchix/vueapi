<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Monster;
use App\Map;
use App\Npc;
use App\ItemTierMaterial;
use App\ItemTier;
use App\ItemSynthesisMaterial;
use App\ItemSynthesisEquipment;
use App\ItemSynthesis;
use App\ItemSet;
use App\ItemMonster;
use App\ItemJob;
use App\ItemCraft;
use App\MapMonster;
use App\ItemCompose;
use App\ItemComposeMaterial;
use App\Blog;
use App\Event;
use Str;

class SitemapController extends Controller
{

    private $equips__ = [
        'Weapon - Sword', 
        'Weapon - Dagger',
        'Weapon - Axe',
        'Weapon - Book',
        'Weapon - Bow',
        'Weapon - Katar',
        'Weapon - Knuckles',
        'Weapon - Spear',
        'Whips',
        'Weapon - Staff',
        'Weapon - Mace',
        'Off-hand - Jewelry',
        'Off-hand - Bracer',
        'Off-hand - Bangle',
        'Musical Instrument',
        'Garments',
        'Footgears',
        'Armors',
        'Accessory',
        'Off-hand - Shield',
    ];

    private $cards__ = [
        'Accessory card',
        'Armor card',
        'Garments card',
        'Headwear card',
        'Off-hand card',
        'Shoe card',
        'Weapon card',
        'Accessory card'
    ];

    private $headwears__ = [
        'Face',
        'Costume',
        'Back',
        'Headwear',
        'Mouth',
        'Tail',
    ];

    private $items__ = [
        'Blueprint',
        'Consumables',
        'Crafting material',
        "Death's Breath",
        'Enhance equipment',
        'Blueprint',
        'Fruit',
        'Holiday material',
        'Meat',
        'Mora coin',
        'Potion / Effect',
        'Quest triggering item',
        'Redeem item',
        'Seafood',
        'Spice',
        'Vegetable',
        'Zeny',
        'Gift Box'
    ];

	public function index() {
        $post = Blog::orderBy('updated_at', 'desc')->select('id','updated_at')->first();
        $card = Item::whereIn('type_name', $this->cards__)->orderBy('updated_at', 'desc')->select('id','updated_at')->first();
        $equipment = Item::whereIn('type_name', $this->equips__)->orderBy('updated_at', 'desc')->select('id','updated_at')->first();
        $headwear = Item::whereIn('type_name', $this->headwears__)->orderBy('updated_at', 'desc')->select('id','updated_at')->first();
        $item = Item::whereIn('type_name', $this->items__)->orderBy('updated_at', 'desc')->select('id','updated_at')->first();
        $monster = Monster::orderBy('updated_at', 'desc')->select('id','updated_at')->first();
      
        return response()->json([
            'posts' => $post,
            'cards' => $card,
            'monsters' => $monster,
            'headwears' => $headwear,
            'items' => $item,
            'equipments' => $equipment,
        ]);
	}

	public function equipments() {
        $equipments = Item::whereIn('type_name', $this->equips__)->orderBy('updated_at', 'desc')->select('id','slug','updated_at')->get();

        return response()->json([
            'equipments' => $equipments,
        ]);
	}

	public function headwears() {
        $headwears = Item::whereIn('type_name', $this->headwears__)->orderBy('updated_at', 'desc')->select('id','slug','updated_at')->get();

        return response()->json([
            'headwears' => $headwears,
        ]);
	}

	public function blogs() {
	    $posts = Blog::orderBy('updated_at', 'desc')->select('id', 'slug','updated_at')->get();

        return response()->json([
            'posts' => $posts,
        ]);
	}

	public function cards() {
        $cards = Item::whereIn('type_name', $this->cards__)->orderBy('updated_at', 'desc')->select('id','slug','updated_at')->get();

        return response()->json([
            'cards' => $cards,
        ]);
	}

	public function monsters(){
        $monsters = Monster::orderBy('updated_at', 'desc')->select('id','slug','updated_at')->get();

        return response()->json([
            'monsters' => $monsters,
        ]);
	}

	public function items() {
        $items = Item::whereIn('type_name', $this->items__)->orderBy('updated_at', 'desc')->select('id','slug','updated_at')->get();

        return response()->json([
            'items' => $items,
        ]);
    }
    
    public function saveWeeklies(Request $request) {
        $inputs = $request->all();
        $event = Event::create($inputs);

        return view('weekly')->with(['item' => 'saved']);
    }
    
    public function getWeeklies() {
        $event = Event::latest('created_at')->first();

        return $event;
    }

}
