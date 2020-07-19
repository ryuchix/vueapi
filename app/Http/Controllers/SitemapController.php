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

	public function blogs()
	{
	    $posts = Blog::orderBy('updated_at', 'desc')->get();

	    return response()->view('sitemap.posts', [
	        'posts' => $posts,
	    ])->header('Content-Type', 'text/xml');
	}

	public function cards()
	{
	    $cards = Card::all();
	    return response()->view('sitemap.cards', [
	        'cards' => $cards,
	    ])->header('Content-Type', 'text/xml');
	}

	public function monsters()
	{
	    $monsters = Monster::all();
	    return response()->view('sitemap.monsters', [
	        'monsters' => $monsters,
	    ])->header('Content-Type', 'text/xml');
	}

	public function items()
	{
	    $items = Item::all();
	    return response()->view('sitemap.items', [
	        'items' => $items,
	    ])->header('Content-Type', 'text/xml');
	}

	public function guides()
	{
	    $guides = Blog::where('category', 'Guide')->get();
	    return response()->view('sitemap.guides', [
	        'guides' => $guides,
	    ])->header('Content-Type', 'text/xml');
	}

	public function quests()
	{
	    $quests = Blog::where('category', 'Quest')->get();
	    return response()->view('sitemap.quests', [
	        'quests' => $quests,
	    ])->header('Content-Type', 'text/xml');
	}
	
	public function maps()
	{
	    $maps = Map::all();
	    return response()->view('sitemap.maps', [
	        'maps' => $maps,
	    ])->header('Content-Type', 'text/xml');
	}

	public function news()
	{
	    $items = Blog::orderBy('updated_at', 'desc')->get();

	    return response()->view('sitemap.news', [
	        'items' => $items,
	    ])->header('Content-Type', 'text/xml');
	}

	public function pages()
	{
	    return response()->view('sitemap.page')->header('Content-Type', 'text/xml');
	}
}
