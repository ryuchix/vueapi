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

use Str;

class ItemController extends Controller
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
        'Costume',
        'Face'
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

    private $items__ = [
        'Blueprint',
        'Consumables',
        'Crafting material',
        "Death's Breath",
        'Enhance equipment',
        'Blueprint',
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
        null
    ];

    private $jobs__ = [
        0 => 'All jobs',
        1 => 'Novice',
        11 => 'Swordsman',
        12 => 'Knight',
        13 => 'Lord Knight',
        14 => 'Rune Knight',
        15 => 'Runemaster',
        
        72 => 'Crusader',
        73 => 'Paladin',
        74 => 'Royal Guard',
        75 => 'Divine Avenger',
        
        21 => 'Mage',
        22 => 'Wizard',
        23 => 'High Wizard',
        24 => 'Warlock',
        25 => 'Arcane Master',
        
        82 => 'Sage',
        83 => 'Professor',
        84 => 'Sorcerer',
        85 => 'Chronomancer',
        
        31 => 'Thief',
        32 => 'Assassin',
        33 => 'Assassin Cross',
        34 => 'Guillotine Cross',
        35 => 'Soulblade Cross',
         
        92 => 'Rogue',
        93 => 'Stalker',
        94 => 'Shadow Chaser',
        95 => 'Phantom Dancer',
        
        41 => 'Archer',
        42 => 'Hunter',
        43 => 'Sniper',
        44 => 'Ranger',
        45 => 'Stellar Hunter',
         
        102 => 'Bard',
        103 => 'Clown',
        104 => 'Minstrel',
        105 => 'Solar Trouvere',
        
        112 => 'Dancer',
        113 => 'Gypsy',
        114 => 'Wanderer',
        115 => 'Luna Danseuse',
        
        51 => 'Acolyte',
        52 => 'Priest',
        53 => 'High Priest',
        54 => 'Archbishop',
        55 => 'Saint',
        
        122 => 'Monk',
        123 => 'Champion',
        124 => 'Shura',
        125 => 'Dragon Fist',
        
        61 => 'Merchant',
        62 => 'Blacksmith',
        63 => 'Whitesmith',
        64 => 'Mechanic',
        65 => 'Lightbringer',
        
        132 => 'Alchemist',
        133 => 'Creator',
        134 => 'Genetic',
        135 => 'Begetter',
        
        143 => 'Advanced Novice',
        144 => 'Super Novice',
        145 => 'Novice Guardian',
        
        151 => 'Warlock Doram',
        152 => 'Spiritualist',
        153 => 'Summoner',
        154 => 'Animist',
        155 => 'Spirit Whisperer'
    ];

    public function equipments() {
        return Item::whereIn('type_name', $this->equips__)->orderBy('name_en')->paginate();
    }

    public function getEquipment($id) {
        $equip = Item::where('id', $id)->whereIn('type_name', $this->equips__)->with('monsters')->firstOrFail();
        $equip['slot'] = strpos($equip->name_en,'[1]') !== false || strpos($equip->name_en, '[2]') !== false;
        $equip['tradable'] = $equip->auction_price == 1 ? true : false;
        $itemtiers = ItemTier::where('item_id', $equip->id)->with('materials')->get();
        $equip['tiers'] = $equip->tier_list == 1 ? $itemtiers : null;
        $itemsets = ItemSet::where('item_id', $equip->id)->select('effect_desc_en', 'item_id', 'items', 'equip_suit_desc_en', 'id')->get();
        $equip['sets'] = $equip->item_set == 1 ? $itemsets : null;
        $equip['before'] = $equip->prior_equipment != null ? Item::where('key_id', $equip->prior_equipment)->select('id', 'icon', 'name_en')->first() : null;
        $itemSynth = ItemSynthesis::where('item_id', $equip->id)->first();
        $equip['after'] = $equip->synthesis_recipe != null ? Item::where('key_id', $itemSynth->item_output)->select('id', 'icon', 'name_en')->first() : null;
        $jobs = [];
        $can_equip = json_decode($equip->can_equip);
        foreach($can_equip as $v) {
            $jobs[] = $this->jobs__[$v];
        }
        $equip['jobs'] = $jobs;

        return $equip;
    }

    public function cards() {
        return Item::whereIn('type_name', $this->cards__)->orderBy('name_en')->paginate();
    }

    public function getCard($id) {
        return Item::where('id', $id)->whereIn('type_name', $this->cards__)->firstOrFail();
    }

    public function getItems() {
        return Item::whereIn('type_name', $this->items__)->orderBy('name_en')->paginate();
    }

    public function getItem($id) {
        $item = Item::where('id', $id)->whereIn('type_name', $this->items__)->with('monsters')->firstOrFail();
        $item['tradable'] = $item->auction_price == 1 ? true : false;
        return $item;
    }

    public function getItemSets() {
        $items = Item::where('item_set', null)->get();

        foreach ($items as $key => $item) {
            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
                $result = json_decode($json, true);
                if (array_key_exists("ItemSet", $result)) {
    
                    foreach ($result['ItemSet'] as $set) {
                        $itemsets = new ItemSet();
                        $itemsets->item_id = $item->id;
                        $itemsets->effect_desc = $set['EffectDesc'];
                        $itemsets->effect_desc_en = $set['EffectDesc__EN'];
                        $itemsets->equip_suit_desc = $set['EquipSuitDsc'];
                        $itemsets->equip_suit_desc_en = $set['EquipSuitDsc__EN'];
                        $itemsets->items = json_encode(array_values($set['Suitid']));
                        $itemsets->save();
                    }
    
                }
    
            } catch (\Throwable $th) {
                return $th;
            }
        }
    }

    public function runNull() {
        $items = Item::where('icon', null)->get();
        foreach ($items as $key => $item) {
            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
                $result = json_decode($json, true);

                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['key_id'], '-').'_img.png'));

                $item_ = Item::find($item->id);
                $item_->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item_->icon = Str::slug($result['key_id'], '-').'_img.png';
                $item_->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                $item_->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                $item_->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                $item_->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                $item_->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                $item_->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                $item_->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                $item_->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                $item_->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;
                if (array_key_exists("AttrData", $result)) {
                    $item_->stat = implode(", ", array_key_exists("Stat", $result['AttrData']) ? $result['AttrData']['Stat'] : []);
                    $item_->stat_extra = implode(", ", array_key_exists("StatExtra", $result['AttrData']) ? $result['AttrData']['StatExtra'] : []);
                    $item_->stat_type = array_key_exists("Type", $result['AttrData']) ? $result['AttrData']['Type'] : '';
                    $item_->can_equip = array_key_exists("CanEquip", $result['AttrData']) ? $result['AttrData']['CanEquip'] : '';
                }

                $item_->item_set = array_key_exists("ItemSet", $result);
                $item_->compose_recipe = array_key_exists("ComposeRecipe", $result);

                if (array_key_exists("TierList", $result)) {
                    $item_->tier_list = array_key_exists("TierList", $result);
                    $finditemfortier = Item::where('key_id', $result['id'])->first();
                    $this->addTier($finditemfortier->id, $result['TierList']);
                }
    
                if (array_key_exists("SynthesisRecipe", $result) && $result['TypeName'] != 'Crafting material') {
                    $item_->synthesis_recipe = array_key_exists("SynthesisRecipe", $result);
                    $finditemforsynth = Item::where('key_id', $result['id'])->first();
                    $this->addSynthesis($finditemforsynth->id, $result['SynthesisRecipe']);
                }

                //$item_->synthesis_recipe = array_key_exists("SynthesisRecipe", $result);
                $item_->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;
                //$item_->tier_list = array_key_exists("TierList", $result);
      
                $item_->unlock_effect = array_key_exists("UnlockEffect", $result) ? $this->saveUnlockDeposit($result['UnlockEffect']) : null;
                $item_->deposit_effect = array_key_exists("DepositEffect", $result) ? $this->saveUnlockDeposit($result['DepositEffect']) : null;

                $item_->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
                $item_->save();

            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    // public function getExtraStat() {
    //     $items = Item::get();
    //     $json = file_get_contents('https://www.romcodex.com/api/item/44303');
    //     $result = json_decode($json, true);

    //     foreach ($items as $item) {
    //         try {
    //             $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
    //             $result = json_decode($json, true);
                
    //             if (array_key_exists("AttrData", $result)) {
    //                 $_item = Item::find($item->id);
    //                 if (array_key_exists("Stat", $result['AttrData'])) {
    //                     $_item->stat = json_encode($result['AttrData']['Stat']);
    //                 }
    //                 if (array_key_exists("StatExtra", $result['AttrData'])) {
    //                     $_item->stat_extra = json_encode($result['AttrData']['StatExtra']);
    //                 }
    //                 $_item->save();
    //             }

    //         } catch (\Throwable $th) {
    //             //throw $th;
    //         }
    //     }
    // }

    // public function addSingleItem() {
    //     $json = file_get_contents('https://www.romcodex.com/api/item/52821');
    //     $result = json_decode($json, true);

    //     $checkItem = Item::where('key_id', $result['id'])->first();

    //     if ($checkItem) {
    //         $item = new Item();
    //         $item->key_id = $result['key_id'];
    //         copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['key_id'], '-').'_img.png'));
    //         $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
    //         $item->icon = Str::slug($result['key_id'], '-').'_img.png';
    //         $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
    //         $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
    //         $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
    //         $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
    //         $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
    //         $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
    //         $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
    //         $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
    //         $item->save();

    //     }

    // }

    // public function getMonsters() {
    //     $json = file_get_contents('https://www.romcodex.com/api/monster');
    //     $result = json_decode($json, true);
    //     // 10101 - 10226
    //     // 20001 - 20038 mini
    //     // 30000 - 30060 mvp
    //     // 120000 - 120099 star 
    //     // 120101 - 120122 star 
    //     // 10001
    //     // 18125 - 18198 
    //     // 204000 - 204170 x10 - 
    //     for ($i=204000; $i <= 204170 ; $i+=10) { 
    //         $this->getMonster($i);
    //     }
    // }

    // public function getMonster($id) {
    //     try {
    //         $json = file_get_contents('https://www.romcodex.com/api/monster/'.$id);
    //         $result = json_decode($json, true);

    //         if (array_key_exists("error", $result)) {
    //             return false;
    //         }

    //         $checkMonster = Monster::where('key_id', $result['key_id'])->first();

    //         if ($checkMonster == null) {
    //             $monster  = new Monster();
    //             $monster->key_id = $result['key_id'];
    //             $monster->ShowName = $result['ShowName'];
    //             $monster->Position = $result['Position'];
    //             $monster->MoveSpd = $result['MoveSpd'];
    //             $monster->MDef = $result['MDef'];
    //             $monster->NameZh = $result['NameZh'];
    //             $monster->NameZh__EN = $result['NameZh__EN'];
    //             $monster->Atk = $result['Atk'];
    //             $monster->MAtk = $result['MAtk'];
    //             $monster->Def = $result['Def'];
    //             $monster->Icon = Str::slug($result['key_id'], '-').'0_img.png';
    //             $monster->Hit = $result['Hit'];
    //             $monster->PassiveLv = $result['PassiveLv'];
    //             $monster->Zone = $result['Zone'];
    //             $monster->Type = $result['Type'];
    //             $monster->JobExp = $result['JobExp'];
    //             $monster->Flee = $result['Flee'];
    //             $monster->BaseExp = $result['BaseExp'];
    //             $monster->move = $result['move'];
    //             $monster->Level = $result['Level'];
    //             $monster->Hp = $result['Hp'];
    //             $monster->AtkSpd = $result['AtkSpd'];
    //             $monster->Race = $result['Race'];
    //             $monster->Shape = $result['Shape'];
    //             $monster->Nature = $result['Nature'];
    //             $monster->Scale = $result['Scale'];
    //             $monster->Dex = $result['Dex'];
    //             $monster->Agi = $result['Agi'];
    //             $monster->Str = $result['Str'];
    //             $monster->Luk = $result['Luk'];
    //             $monster->Desc__EN = $result['Desc__EN'];
    //             $monster->IsStar = $result['IsStar'];
    //             $monster->CopySkill = $result['CopySkill'];
    //             $monster->Int = $result['Int'];
    //             $monster->Vit = $result['Vit'];
                
    //             if ($monster->save()) {
    //                 if (array_key_exists("LootData", $result)) {
    //                     $this->addMonsterDrop($monster, $result['LootData']);
    //                 }
    //                 // if (array_key_exists("Location", $result)) {
    //                 //     $this->addMapMonster($monster, $result['Location']);
    //                 // }
    //             }
    //             copy('https://www.romcodex.com/icons/face/'.strtolower($result['Icon']).'.png', public_path('/uploads/monsters/'.Str::slug($result['key_id'], '-').'0_img.png'));
               
    //         }
    //     } 
    //     catch(\Exception $e){
    //         return false;
    //     }
    // }

    // public function addMonsterDrop($monster, $details) {
    //     foreach ($details as $key => $item) {
    //         $this->addItem($monster, $item, null);
    //     }
    // }

    // public function addMapMonster($monster, $details) {
    //     foreach ($details as $key => $map) {
    //         $this->addMap($monster, $map);
    //     }
    // }

    // public function addMap($monster, $maps) {
    //     $json = file_get_contents('https://www.romcodex.com/api/map/'.$maps['key_id']);
    //     $result = json_decode($json, true);

    //     $checkMap = Map::where('key_id', $maps['key_id'])->first();

    //     if ($checkMap == null) {
    //         $map = new Map();
    //         $map->key_id = $result['key_id'];
    //         $map->Desc__EN = $result['Desc__EN'];
    //         $map->NameEn = $result['NameEn'];
    //         $map->Type = $result['Type'];
    //         $map->NameZh = $result['NameZh'];
    //         $map->NameZh__EN = $result['NameZh__EN'];
    //         $map->MapArea = $result['MapArea'];
            
    //         if ($map->save()) {
    //             if (array_key_exists("NpcList", $result)) {
    //                $this->addMonsterNpc($map->id, $result['NpcList']);
    //             }
    //             if (array_key_exists("MonsterList", $result)) {
    //                 $this->getMons($result['MonsterList']);
    //             }
                
    //         }
    //     }
    // }

    // public function getMons($details) {
    //     foreach ($details as $key => $mon) {
    //         $this->getMonster($mon['key_id']);
    //     }
    // }

    // public function addMonsterNpc($map, $details) {
    //     foreach ($details as $key => $npc) {
    //         $checkNpc = Npc::where('NameZh__EN', array_key_exists("NameZh__EN", $npc) ? $npc['NameZh__EN'] : '')->first();

    //         if ($checkNpc == null) {
    //             $npcs = new Npc();
    //             $npcs->map_id = $map;
    //             $npcs->NameZh__EN = array_key_exists("NameZh__EN", $npc) ? $npc['NameZh__EN'] : null;
    //             $npcs->Nature = array_key_exists("Nature", $npc) ? $npc['Nature'] : null;
    //             $npcs->NPC = array_key_exists("Type", $npc) ? $npc['Type'] : null;
    //             $npcs->Icon = array_key_exists("Icon", $npc) ? $npc['Icon'] : null;
    //             $npcs->Position__EN = array_key_exists("Position__EN", $npc) ? $npc['Position__EN'] : null;
    //             $npcs->Guild__EN = array_key_exists("Guild__EN", $npc) ? $npc['Guild__EN'] : null;
    //             $npcs->Desc__EN = array_key_exists("Desc__EN", $npc) ? $npc['Desc__EN'] : null;
    //             $npcs->save();
    //         }
    //     }
    // }

    // public function checkNull($key, $obj) {
    //     return array_key_exists($key, $obj) ? $obj[$key] : null;
    // }

    public function saveUnlockDeposit($obj) {
        foreach ($obj as $key => $value) {
            return implode(", ", $value);
        }
    }

    public function addItemSet($id, $details) {

        foreach ($details as $key => $item) {

            $itemset = new ItemSet();
            $itemset->item_id = $id;
            $itemset->effect_desc = $item['EffectDesc'];
            $itemset->effect_desc_en = $item['EffectDesc__EN'];
            $itemset->equip_suit_desc = $item['EquipSuitDsc'];
            $itemset->equip_suit_desc_en = $item['EquipSuitDsc__EN'];
            $itemset->items = null;
            $itemset->save();
            
            foreach ($item['Suitid'] as $key => $value) {
                $setid = [];
                $itemset_ = 
                array_push($setid, $value['key_id']);

                $item = ItemSet::find($itemset->id);
                $item->items = implode(" ,", $setid);
                $item->save();
                
                //$this->addItem(null, null, $key);

            }
        }
    }

    // public function addItem($monster = null, $details = null, $itemid = null) {

    //     try{
    //         $json = '';
    //         if ($details != null && $itemid == null) {
    //             $json = file_get_contents('https://www.romcodex.com/api/item/'.$details['id']);
    //         }
    //         if ($details == null && $itemid != null) {
    //             $json = file_get_contents('https://www.romcodex.com/api/item/'.$itemid);
    //         }
            
    //         $result = json_decode($json, true);
    
    //         $checkItem = Item::where('key_id', $result['id'])->first();
    //         $hasitem = '';
    //         if ($checkItem == null) {
    //             $item_ = new Item();
    //             $item_->key_id = $result['key_id'];
    //             $item_->save();
    //             $item = Item::find($item_->id);
    //             $hasitem = $item_->id;
    //             $item->SellPrice = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
    //             $item->Icon = Str::slug($result['key_id'], '-').'_img.png';
    //             $item->Desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
    //             $item->Desc__EN = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
    //             $item->Type = array_key_exists("Type", $result) ? $result['Type'] : null;
    //             $item->NameZh = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
    //             $item->NameZh__EN = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
    //             $item->AuctionPrice = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
    //             $item->TypeName = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
    //             $item->Quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
    //             $item->ComposeID = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;
    //             $item->ComposeOutputID = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
    
    //             if (array_key_exists("TypeName", $result)) {
    
    //                 if ($result['TypeName'] == 'Furniture - Plant' ||
    //                     $result['TypeName'] == 'Furniture - Stairs' ||
    //                     $result['TypeName'] == 'Furniture - Fireplace' ||
    //                     $result['TypeName'] == 'Furniture - Chair' ||
    //                     $result['TypeName'] == 'Furniture - Cupboards' ||
    //                     $result['TypeName'] == 'Furniture - Lighting' ||
    //                     $result['TypeName'] == 'Furniture - Tables' ||
    //                     $result['TypeName'] == 'Furniture - Carpets' ||
    //                     $result['TypeName'] == 'Furniture - Beds' ||
    //                     $result['TypeName'] == 'Furniture - Windows' ||
    //                     $result['TypeName'] == 'Furniture - Bathroom' ||
    //                     $result['TypeName'] == 'Furniture - Shelves' ||
    //                     $result['TypeName'] == 'Furniture - Outdoors' ||
    //                     $result['TypeName'] == 'Furniture - Toy' ||
    //                     $result['TypeName'] == 'Furniture - Wall Decorations' ||
    //                     $result['TypeName'] == 'Furniture - Statue' ||
    //                     $result['TypeName'] == 'Furniture - Spring' ||
    //                     $result['TypeName'] == 'Furniture - Partitions' ||
    //                     $result['TypeName'] == 'Furniture - Creativity' ||
    //                     $result['TypeName'] == 'Furniture - Hobbies' ||
    //                     $result['TypeName'] == 'Furniture - Pets' ||
    //                     $result['TypeName'] == 'Furniture - Luxury Goods' ||
    //                     $result['TypeName'] == 'Furniture - Artwork' ||
    //                     $result['TypeName'] == 'Furniture - Excercise' ||
    //                     $result['TypeName'] == 'Furniture - Kitchen Utensils' ||
    //                     $result['TypeName'] == 'Furniture - Ceiling' ||
    //                     $result['TypeName'] == 'Furniture - Wall' ||
    //                     $result['TypeName'] == 'Furniture - Floor' ||
    //                     $result['TypeName'] == 'Furniture - Door') {
    //                         // foreach ($result['UnlockEffect'] as $key => $value) {
    //                         //     $item->UnlockEffect = $value['Dsc__EN'];
    //                         // }
    //                 }
    
    //                 if ($result['TypeName'] == 'Blueprint' ||
    //                     $result['TypeName'] == 'Armor card' ||
    //                     $result['TypeName'] == 'Off-hand card' ||
    //                     $result['TypeName'] == 'Garments card' ||
    //                     $result['TypeName'] == 'Shoe card' ||
    //                     $result['TypeName'] == 'Headwear card' ||
    //                     $result['TypeName'] == 'Accessory card' ||
    //                     $result['TypeName'] == 'Weapon card' ||
    //                     $result['TypeName'] == 'Head' ||
    //                     $result['TypeName'] == 'Face' ||
    //                     $result['TypeName'] == 'Mouth' ||
    //                     $result['TypeName'] == 'Back' ||
    //                     $result['TypeName'] == 'Tail' ||
    //                     $result['TypeName'] == 'Artifact - Back' ||
    //                     $result['TypeName'] == 'Artifact - Headwear'){
    //                     $item->UnlockEffect = $this->saveUnlockDeposit($result['UnlockEffect']);
    //                     $item->DepositEffect = $this->saveUnlockDeposit($result['DepositEffect']);
    //                     $item->ComposeRecipe = array_key_exists("ComposeRecipe", $result);
    
    //                     $item->Stat = implode(", ", array_key_exists("Stat", $result['AttrData']) ? $result['AttrData']['Stat'] : []);
    //                     $item->StatExtra = implode(", ", array_key_exists("StatExtra", $result['AttrData']) ? $result['AttrData']['StatExtra'] : []);
    //                     $item->StatType = array_key_exists("Type", $result['AttrData']) ? $result['AttrData']['Type'] : '';
    //                     $item->CanEquip = array_key_exists("CanEquip", $result['AttrData']) ? $result['AttrData']['CanEquip'] : '';
    
    //                     if (array_key_exists("ItemSet", $result)) {
    //                         $item->ItemSet = array_key_exists("ItemSet", $result);
    
    //                         if (array_key_exists("ItemSet", $result)) {
    //                             $finditemforitemset = Item::where('key_id', $result['id'])->first();
    //                             $this->addItemSet($finditemforitemset->id, $result['ItemSet']);
    //                         }
    //                     }
    //                 }
    
    //                 if ($result['TypeName'] == 'Armors' || 
    //                     $result['TypeName'] == 'Accessory' ||
    //                     $result['TypeName'] == 'Garments' || 
    //                     $result['TypeName'] == 'Footgears' || 
    //                     $result['TypeName'] == 'Weapons' || 
    //                     $result['TypeName'] == 'Off-hand - Shield' || 
    //                     $result['TypeName'] == 'Costume' || 
    //                     $result['TypeName'] == 'Garments' || 
    //                     $result['TypeName'] == 'Garments' ||  
    //                     $result['TypeName'] == 'Weapon - Mace' || 
    //                     $result['TypeName'] == 'Weapon - Staff'||
    //                     $result['TypeName'] == 'Weapon - Spear' || 
    //                     $result['TypeName'] == 'Weapon - Sword' || 
    //                     $result['TypeName'] == 'Weapon - Katar' || 
    //                     $result['TypeName'] == 'Weapon - Bow' || 
    //                     $result['TypeName'] == 'Weapon - Axe' ||
    //                     $result['TypeName'] == 'Weapon - Book' ||
    //                     $result['TypeName'] == 'Weapon - Dagger' ||
    //                     $result['TypeName'] == 'Weapon - Musical Instrument' ||
    //                     $result['TypeName'] == 'Weapon - Knuckles' ||
    //                     $result['TypeName'] == 'Weapon - Whips' ||
    //                     $result['TypeName'] == 'Artifact - Katar'||
    //                     $result['TypeName'] == 'Artifact -Knuckles'||
    //                     $result['TypeName'] == 'Artifact - Separ'||
    //                     $result['TypeName'] == 'Artifact - Wand'||
    //                     $result['TypeName'] == 'Artifact - Dagger'||
    //                     $result['TypeName'] == 'Artifact - Book'||
    //                     $result['TypeName'] == 'Artifact - Axe'||
    //                     $result['TypeName'] == 'Artifact - Whip'||
    //                     $result['TypeName'] == 'Artifact - Instrument'||
    //                     $result['TypeName'] == 'Artifact - Bow'||
    //                     $result['TypeName'] == 'Artifact - Maces tool') {
    //                     $item->ComposeRecipe = array_key_exists("ComposeRecipe", $result);
    
    //                     $item->Stat = implode(", ", $result['AttrData']['Stat']);
    //                     $item->StatExtra = implode(", ", $result['AttrData']['StatExtra']);
    //                     $item->StatType = $result['AttrData']['Type'];
    //                     $item->CanEquip = $result['AttrData']['CanEquip'];
    
    //                     if (array_key_exists("ItemSet", $result)) {
    //                         $item->ItemSet = array_key_exists("ItemSet", $result);
    
    //                         if (array_key_exists("ItemSet", $result)) {
    //                             $finditemforitemset = Item::where('key_id', $result['id'])->first();
    //                             $this->addItemSet($finditemforitemset->id, $result['ItemSet']);
    //                         }
    //                     }
    //                 }
    //             }
    
    //             if (array_key_exists("PriorEquipment", $result)) {
    //                 $item->PriorEquipment = $result['PriorEquipment']['key_id'];
    //                 $this->addItem(null, null, $result['PriorEquipment']['key_id']);
    //             }
    
    //             if (array_key_exists("TierList", $result)) {
    //                 $item->TierList = array_key_exists("TierList", $result);
    //                 $finditemfortier = Item::where('key_id', $result['id'])->first();
    //                 $this->addTier($finditemfortier->id, $result['TierList']);
    //             }
    
    //             if (array_key_exists("SynthesisRecipe", $result) && $result['TypeName'] != 'Crafting material') {
    //                 $item->SynthesisRecipe = array_key_exists("SynthesisRecipe", $result);
    //                 $finditemforsynth = Item::where('key_id', $result['id'])->first();
    //                 $this->addSynthesis($finditemforsynth->id, $result['SynthesisRecipe']);
    //             }

    //             $item->save();
    //             copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['key_id'], '-').'_img.png'));
                
    //         }
    //         $_item = Item::where('key_id', $result['key_id'])->first();
    //         if ($monster != null) {
    //             $monster->items()->detach($_item);
    //             $monster->items()->attach($_item);
    //         }
    //     } 
    //     catch(\Exception $e){
    //         return false;
    //     }

    // }

    public function addSynthesis($id, $details) {
        $synthid = [];
        foreach ($details as $key => $output) {
            $itemsynth = new ItemSynthesis();
            $itemsynth->item_id = $id;
            $itemsynth->item_output = $output['output']['id'];
            $itemsynth->cost = $output['cost'];
            $itemsynth->isInput = $output['isInput'];
            $itemsynth->save();

            array_push($synthid, $itemsynth->id);
        }

        foreach ($details as $key => $input) {

            foreach ($input['input']['weapons'] as $weapons) {
                $itemsynth = new ItemSynthesisEquipment();
                $itemsynth->item_syntheses_id = json_encode($synthid);
                $itemsynth->item_id = $weapons['id'];
                $itemsynth->tier = $weapons['tier'];
                $itemsynth->save();
                
            }

            foreach ($input['input']['materials'] as $key => $materials) {
                $itemsynth = new ItemSynthesisMaterial();
                $itemsynth->item_syntheses_id = json_encode($synthid);
                $itemsynth->item_id = $materials['id'];
                $itemsynth->quantity = $materials['quantity'];
                $itemsynth->save();
                
            }
        }
    }

    public function addTier($itemid, $details) {
        foreach ($details as $key => $tiers) {
            $itemtier = new ItemTier();
            $itemtier->item_id = $itemid;
            $itemtier->tier_name = $tiers['effect']['BuffName__EN'];
            $itemtier->tier_buff = $tiers['effect']['Dsc__EN'];
            $itemtier->save();

            foreach ($tiers['material'] as $key => $material) {
                $itemtiermaterial = new ItemTierMaterial();
                $itemtiermaterial->item_tier_id = $itemtier->id;
                $itemtiermaterial->tier_item_id = $material['item']['key_id'];
                $itemtiermaterial->qty = $material['quantity'];
                $itemtiermaterial->save();
            }
        }
    }


    // // public function getItems($id) {
    // //     $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
    // //     $result = json_decode($json, true);

    // //     $checkItem = Item::where('key_id', $result['key_id'])->first();

    // //     if ($checkItem == null) {
    // //         $item = new Item();
    // //         $item->key_id = $result['key_id'];
    // //         $item->SellPrice = $result['SellPrice'];
    // //         copy('https://www.romcodex.com/icons/item/item_'.$id.'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'], '-').'.png'));
    // //         $item->Icon = Str::slug($result['NameZh__EN'], '-').'.png';
    // //         $item->Desc = $result['Desc'];
    // //         $item->Desc__EN = $result['Desc__EN'];
    // //         $item->Type = $result['Type'];
    // //         $item->NameZh = $result['NameZh'];
    // //         $item->NameZh__EN = $result['NameZh__EN'];
    // //         $item->AuctionPrice = $result['AuctionPrice'];
    // //         $item->TypeName = $result['TypeName'];
    // //         $item->Quality = $result['Quality'];
    // //         $item->ComposeID = $result['ComposeID'];
    // //         $item->ComposeOutputID = $result['ComposeOutputID'];
    // //         $item->UnlockEffect = $result['UnlockEffect'];
    // //         $item->DepositEffect = $result['DepositEffect'];
    // //         $item->ComposeRecipe = $result['ComposeRecipe'];
    // //         $item->Stat = $result['Stat'];
    // //         $item->StatExtra = $result['StatExtra'];
    // //         $item->StatType = $result['StatType'];
    // //         $item->CanEquip = $result['CanEquip'];
    // //         $item->PriorEquipment = $result['PriorEquipment'];
    // //         $item->TierList = $result['TierList'];
    // //         $item->ItemSet = $result['ItemSet'];
    // //         $item->SynthesisRecipe = $result['SynthesisRecipe'];
    // //         $item->CanEquip = $result['CanEquip'];
    // //         $item->save();

            


    // //     }

    // //     return response()->json(['message' => 'added'], 200); 
    // // }

    // public function addItemJob($item, $details) {
    //     $map = Map::create($details);



    // }


}
