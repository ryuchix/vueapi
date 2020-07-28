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
use Str;

class NpcController extends Controller
{

    private $iddd = [
        '3031851',
'3031700',
'3031553',
'3031552',
'3031521',
'3031520',
'3031284',
'3031283',
'3031260',
'3031187',
'3031186',
'3031176',
'3031168',
'3031167',
'3001862',
'3001861'
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
        
        150 => 'Novice Doram',
        151 => 'Warlock Doram',
        152 => 'Spiritualist',
        153 => 'Summoner',
        154 => 'Animist',
        155 => 'Spirit Whisperer'
    ];

    public function getCards(Request $request) {
        if ($request->has('url')) {
            $url = $request->input('url');
            $removedString = str_replace('https://www.romcodex.com/item/', '', $url);
            $id = strstr($removedString, '/', true);
        } else {
            exit;
        }



        try {
            $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
            $result = json_decode($json, true);

            $checkItem = Item::where('key_id', $result['key_id'])->first();
            
            if ($checkItem == null) {
                $item = new Item();
                $item->special = array_key_exists("Condition", $result) ? $result['Condition'] : null;
                $item->key_id = $result['key_id'];
                $item->slug = $this->createSlug($result['NameZh__EN']);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg';
                $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;
                $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
                $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;

                if (array_key_exists("AttrData", $result)) {
                    if (array_key_exists("Stat", $result['AttrData'])) {
                        $item->stat = json_encode($result['AttrData']['Stat']);
                    }
                    if (array_key_exists("StatExtra", $result['AttrData'])) {
                        $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    }
                    if (array_key_exists("CardPicture", $result['AttrData'])) {
                        $cardIconId = $result['AttrData']['CardPicture'];
                        $getImage = copy('https://www.romcodex.com/pic/cards/'.$cardIconId.'.jpg', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg'));
                        
                        if (!$getImage) {
                            copy('https://www.romcodex.com/pic/cards/'.$cardIconId.'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg'));
                        }
                        
                        $item->icon = Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg';
                    }
                }
    
                if (array_key_exists("UnlockEffect", $result)) {
                    foreach ($result['UnlockEffect'] as $unlock) {
                        $item->unlock_effect = json_encode($unlock['Dsc__EN']);
                    }
                }
    
                if (array_key_exists("DepositEffect", $result)) {
                    foreach ($result['DepositEffect'] as $unlock) {
                        $item->deposit_effect = json_encode($unlock['Dsc__EN']);
                    }
                }

                $item->save();

                if (array_key_exists("ComposeRecipe", $result)) {

                    if ($result['Condition'] == 6) {
                        foreach ($result['ComposeRecipe']['composeFrom'] as $key => $compose) {
                            // delete itemcompose record if exists
                            $_itemcompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($_itemcompose) {
                                $_itemcompose->delete();
    
                                $_itemcomposematerials = ItemComposeMaterial::where('item_compose_id', $_itemcompose->id)->get();
                                foreach ($_itemcomposematerials as $icm) {
                                    $icm->delete();
                                }
                            }
                            
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $result['key_id'];
                            $itemcompose->save();
        
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
        
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
                            }
                        }
                    } else {
                        foreach ($result['ComposeRecipe']['composeFrom'] as $key => $compose) {
                            // delete itemcompose record if exists
                            $_itemcompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($_itemcompose) {
                                $_itemcompose->delete();
    
                                $_itemcomposematerials = ItemComposeMaterial::where('item_compose_id', $_itemcompose->id)->get();
                                foreach ($_itemcomposematerials as $icm) {
                                    $icm->delete();
                                }
                            }
                            
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $compose['output'][0]['id'];
                            $itemcompose->save();
        
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
    
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
              
                            }
                        }
                    }
                }
    
                $item->save();

                return view('card')->with(['item' => $item->name_en]);

            } else {
                return view('card')->with(['item' => 'exists']);
            }

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function getHeadwears(Request $request) { 

        if ($request->has('url')) {
            $id = $request->input('url');
            // $removedString = str_replace('https://www.romcodex.com/item/', '', $url);
            // $id = strstr($removedString, '/', true);
        } else {
            exit;
        }

        try {
            
            $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
            $result = json_decode($json, true);

            $checkItem = Item::where('key_id', $id)->first();

            if (!$checkItem) {

                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg'));
                
                $item = new Item();
                $item->key_id = $result['key_id'];
                $item->special = array_key_exists("Condition", $result) ? $result['Condition'] : null;
                $item->slug = $this->createSlug($result['NameZh__EN']);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg';
                $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;

                if (array_key_exists("AttrData", $result)) {
                    if (array_key_exists("Stat", $result['AttrData'])) {
                        $item->stat = json_encode($result['AttrData']['Stat']);
                    }
                    if (array_key_exists("StatExtra", $result['AttrData'])) {
                        $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    }
                    if (array_key_exists("Type", $result['AttrData'])) {
                        $item->stat_type = json_encode($result['AttrData']['Type']);
                    }
                    if (array_key_exists("CanEquip", $result['AttrData'])) {
                        $item->can_equip = json_encode($result['AttrData']['CanEquip']);
                    }
                }

                if (array_key_exists("UnlockEffect", $result)) {
                    foreach ($result['UnlockEffect'] as $unlock) {
                        $item->unlock_effect = json_encode($unlock['Dsc__EN']);
                    }
                }

                if (array_key_exists("DepositEffect", $result)) {
                    foreach ($result['DepositEffect'] as $unlock) {
                        $item->deposit_effect = json_encode($unlock['Dsc__EN']);
                    }
                }
                $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
                $item->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;
                $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;

                $item->save();

                if (array_key_exists("ComposeRecipe", $result)) {

                    if ($result['TypeName'] == 'Blueprint') {
                        foreach ($result['ComposeRecipe']['composeTo'] as $key => $compose) {

                            $checkCompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($checkCompose) {
    
                                $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
                                foreach ($$composematerials as $composematerial) {
                                    $composematerial->delete();
                                }
    
                                $checkCompose->delete();
                            }
    
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $compose['output'][0]['id'];
                            $itemcompose->save();
    
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
    
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
                                
                            }
                        }
                    } else {
                        foreach ($result['ComposeRecipe']['composeFrom'] as $key => $compose) {

                            $checkCompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($checkCompose) {
    
                                $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
                                foreach ($$composematerials as $composematerial) {
                                    $composematerial->delete();
                                }
    
                                $checkCompose->delete();
                            }
    
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $compose['output'][0]['id'];
                            $itemcompose->save();
    
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
    
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
                                
                            }
                        }
                    }

                

                }
                $msg = $item->name_en;
                return view('headwear')->with(['item' => $msg]);
            }  else {
                $msg = 'exists';
                return view('headwear')->with(['item' => $msg]);
            }
   

        } catch (\Throwable $th) {
            throw $th;
        }
    }



    public function getHeadwearss($id) { 

        try {
            
            $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
            $result = json_decode($json, true);

            $checkItem = Item::where('key_id', $id)->first();

            if (!$checkItem) {

                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg'));
                
                $item = new Item();
                $item->key_id = $result['key_id'];
                $item->slug = $this->createSlug($result['NameZh__EN']);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN'].'-'.$result['TypeName'], '-').'-img.jpg';
                $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;

                if (array_key_exists("AttrData", $result)) {
                    if (array_key_exists("Stat", $result['AttrData'])) {
                        $item->stat = json_encode($result['AttrData']['Stat']);
                    }
                    if (array_key_exists("StatExtra", $result['AttrData'])) {
                        $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    }
                    if (array_key_exists("Type", $result['AttrData'])) {
                        $item->stat_type = json_encode($result['AttrData']['Type']);
                    }
                    if (array_key_exists("CanEquip", $result['AttrData'])) {
                        $item->can_equip = json_encode($result['AttrData']['CanEquip']);
                    }
                }

                if (array_key_exists("UnlockEffect", $result)) {
                    foreach ($result['UnlockEffect'] as $unlock) {
                        $item->unlock_effect = json_encode($unlock['Dsc__EN']);
                    }
                }

                if (array_key_exists("DepositEffect", $result)) {
                    foreach ($result['DepositEffect'] as $unlock) {
                        $item->deposit_effect = json_encode($unlock['Dsc__EN']);
                    }
                }
                $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
                $item->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;
                $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;

                $item->save();

                if (array_key_exists("ComposeRecipe", $result)) {

                    if ($result['TypeName'] == 'Blueprint') {
                        foreach ($result['ComposeRecipe']['composeTo'] as $key => $compose) {

                            $checkCompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($checkCompose) {
    
                                $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
                                foreach ($$composematerials as $composematerial) {
                                    $composematerial->delete();
                                }
    
                                $checkCompose->delete();
                            }
    
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $compose['output'][0]['id'];
                            $itemcompose->save();
    
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
    
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
                                
                            }
                        }
                    } else {
                        foreach ($result['ComposeRecipe']['composeFrom'] as $key => $compose) {

                            $checkCompose = ItemCompose::where('item_id', $item->id)->first();
                            if ($checkCompose) {
    
                                $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
                                foreach ($$composematerials as $composematerial) {
                                    $composematerial->delete();
                                }
    
                                $checkCompose->delete();
                            }
    
                            $itemcompose = new ItemCompose();
                            $itemcompose->item_id = $item->id;
                            $itemcompose->is_input = $compose['isInput'];      
                            $itemcompose->cost = $compose['cost'];
                            $itemcompose->item_output = $compose['output'][0]['id'];
                            $itemcompose->save();
    
                            foreach ($compose['input'] as $key => $material) {
                                $checkitem = Item::where('key_id', $material['id'])->first();
    
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();

                                if ($checkitem == null) {
                                    $this->getHeadwearss($material['id']);
                                }
                                
                            }
                        }
                    }

                

                }
                $msg = $item->name_en;
                return view('headwear')->with(['item' => $msg]);
            }  else {
                $msg = 'exists';
                return view('headwear')->with(['item' => $msg]);
            }
   

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function addRegularItem() {
        try {
            $results = ItemSynthesisMaterial::get();

            foreach ($results as $key => $value) {
                $checkItem = Item::where('key_id', $value->item_id)->first();
                if ($checkItem == null) {
                    $json = file_get_contents('https://www.romcodex.com/api/item/'.$value->item_id);
                    $result = json_decode($json, true);

                    if ($result['Icon'] == null) {
                        continue;
                    }

                    copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['key_id'], '-').'_img.png'));
                    
                    $item = new Item();
                    $item->key_id = $result['key_id'];
                    $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                    $item->icon = Str::slug($result['key_id'], '-').'_img.png';
                    $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                    $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                    $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                    $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                    $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                    $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                    $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                    $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                    $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;
                    if (array_key_exists("AttrData", $result)) {
                        $item->stat = implode(", ", array_key_exists("Stat", $result['AttrData']) ? $result['AttrData']['Stat'] : []);
                        $item->stat_extra = implode(", ", array_key_exists("StatExtra", $result['AttrData']) ? $result['AttrData']['StatExtra'] : []);
                        $item->stat_type = array_key_exists("Type", $result['AttrData']) ? $result['AttrData']['Type'] : '';
                        $item->can_equip = array_key_exists("CanEquip", $result['AttrData']) ? $result['AttrData']['CanEquip'] : '';
                    }
    
                    $item->item_set = array_key_exists("ItemSet", $result);
                    $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
    
                    $item->synthesis_recipe = array_key_exists("SynthesisRecipe", $result);
                    $item->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;
                    $item->tier_list = array_key_exists("TierList", $result);
          
                    $item->unlock_effect = array_key_exists("UnlockEffect", $result) ? $this->saveUnlockDeposit($result['UnlockEffect']) : null;
                    $item->deposit_effect = array_key_exists("DepositEffect", $result) ? $this->saveUnlockDeposit($result['DepositEffect']) : null;
    
                    $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
                    $item->save();
    
                    // if (array_key_exists("ItemSet", $result)) {
                    //     $this->addItemSet($item->id, $result['ItemSet']);
                    // }

                    // if (array_key_exists("TierList", $result)) {
                    //     $this->addTier($item->id, $result['TierList']);
                    // }

                    // if (array_key_exists("LootData", $result)) {
                    //     $loots = $result['LootData'];
                    //     foreach ($loots as $key => $loot) {
                    //         $item = Item::where('key_id', $loot['id'])->first();
                    //         if (!$monster->items()->where('item_id', $item->id)->exists()) {
                    //             $monster->items()->attach($item, ['qty' => $loot['num']]);
                    //         }
                    //     }
                    // }


                }
            }
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function modifySynth() {
        $items = Item::where('id', '>=', '2055')->get();
        foreach ($items as $key => $value) {
            $checkItem = Item::where('key_id', $value->key_id)->first();

            if($checkItem != null) {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$value->key_id);
                $result = json_decode($json, true);

                

                if (array_key_exists("AttrData", $result)) {
                    $item = Item::find($checkItem->id);
                    $item->stat = json_encode($result['AttrData']['Stat']);
                    $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    $item->stat_type = json_encode($result['AttrData']['Type']);
                    $item->can_equip = json_encode($result['AttrData']['CanEquip']);
                    $item->save();
                }

            }

        }
    }
 
    public function getSynth() {
        try {

            $results = ItemSynthesisEquipment::get();
           
            foreach ($results as $key => $value) {

                $checkItem = Item::where('key_id', $value->item_id)->first();

                if ($checkItem == null) {

                    $json = file_get_contents('https://www.romcodex.com/api/item/'.$value->item_id);
                    $result = json_decode($json, true);
                
                
                    copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['key_id'], '-').'_img.png'));
                    
                    $item = new Item();
                    $item->key_id = $result['key_id'];
                    $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                    $item->icon = Str::slug($result['key_id'], '-').'_img.png';
                    $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                    $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                    $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                    $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                    $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                    $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                    $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                    $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
                    $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;
                    if (array_key_exists("AttrData", $result)) {
                        $item->stat = json_encode($result['AttrData']['Stat']);
                        $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                        $item->stat_type = json_encode($result['AttrData']['Type']);
                        $item->can_equip = json_encode($result['AttrData']['CanEquip']);
                    }
    
                    $item->item_set = array_key_exists("ItemSet", $result);
                    $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
    
                    $item->synthesis_recipe = array_key_exists("SynthesisRecipe", $result);
                    $item->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;
                    $item->tier_list = array_key_exists("TierList", $result);
          
                    $item->unlock_effect = array_key_exists("UnlockEffect", $result) ? $this->saveUnlockDeposit($result['UnlockEffect']) : null;
                    $item->deposit_effect = array_key_exists("DepositEffect", $result) ? $this->saveUnlockDeposit($result['DepositEffect']) : null;
    
                    $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
                    $item->save();
    
                    if (array_key_exists("ItemSet", $result)) {
                        $this->addItemSet($item->id, $result['ItemSet']);
                    }

                    if (array_key_exists("TierList", $result)) {
                        $this->addTier($item->id, $result['TierList']);
                    }
    
                    // if (array_key_exists("SynthesisRecipe", $result)) {
                    //     $this->addSynthesis($item->id, $result['SynthesisRecipe']);
                    // }
    
                }
            }

        } catch (\Throwable $th) {
            throw $th;
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



    public function addSynthesis($id, $details) {
        $synthid = [];
        foreach ($details as $key => $output) {
            $check = ItemSynthesis::where('item_id', $id)->where('item_output', $output['output']['id'])->first();
            if ($check == null) {
                $itemsynth = new ItemSynthesis();
                $itemsynth->item_id = $id;
                $itemsynth->item_output = $output['output']['id'];
                $itemsynth->cost = $output['cost'];
                $itemsynth->isInput = $output['isInput'];
                $itemsynth->save();
    
                array_push($synthid, $itemsynth->id);
            }
        }

        if ($synthid != null) {
            foreach ($details as $key => $input) {

                foreach ($input['input']['weapons'] as $weapons) {
                    $itemsynth = new ItemSynthesisEquipment();
                    $itemsynth->item_syntheses_id = reset($synthid);
                    $itemsynth->item_id = $weapons['id'];
                    $itemsynth->tier = $weapons['tier'];
                    $itemsynth->save();

  

                }
    
                foreach ($input['input']['materials'] as $key => $materials) {
                    $itemsynth = new ItemSynthesisMaterial();
                    $itemsynth->item_syntheses_id = reset($synthid);
                    $itemsynth->item_id = $materials['id'];
                    $itemsynth->quantity = $materials['quantity'];
                    $itemsynth->save();

      
                    
                }
            }
        }

    }

    public function addItemSet($id, $details) {

        foreach ($details as $key => $item) {
            $check = ItemSet::find($id);
            if ($check == null) {
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
                }
            }

        }
    }

    public function getMissingCardImage() {
        $items = Item::whereIn('type_name', $this->cards__)->where('id', '>=', 3565)->get();

        foreach ($items as $item) {
            $_item = Item::where('key_id', $item->key_id)->first();

            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
                $result = json_decode($json, true);


                $_item->special = $result['Condition'];

                if (array_key_exists("AttrData", $result)) {
            
                    if (array_key_exists("CardPicture", $result['AttrData'])) {
                        $cardIconId = $result['AttrData']['CardPicture'];
                        copy('https://www.romcodex.com/pic/cards/'.$cardIconId.'.jpg', public_path('/uploads/items/'.Str::slug($_item->name_en, '-').'-img.jpg'));
                        $_item->icon = Str::slug($_item->name_en, '-').'-img.jpg';
                    }

                }

                $_item->save();

            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function getMissingInfoInHeadwears() {
        // $items = Item::whereIn('type_name', $this->headwears__)->get();
        $items = Item::whereIn('type_name', $this->headwears__)->where('stat_type', null)->get();

        foreach ($items as $item) {
            $_item = Item::where('key_id', $item->key_id)->first();

            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
                $result = json_decode($json, true);

                if (array_key_exists("AttrData", $result)) {
                    if (array_key_exists("Stat", $result['AttrData'])) {
                        $_item->stat = json_encode($result['AttrData']['Stat']);
                    }
                    if (array_key_exists("StatExtra", $result['AttrData'])) {
                        $_item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    }
                    if (array_key_exists("Type", $result['AttrData'])) {
                        $_item->stat_type = json_encode($result['AttrData']['Type']);
                    }
                }

                if (array_key_exists("UnlockEffect", $result)) {
                    foreach ($result['UnlockEffect'] as $unlock) {
                        $_item->unlock_effect = json_encode($unlock['Dsc__EN']);
                    }
                }

                if (array_key_exists("DepositEffect", $result)) {
                    foreach ($result['DepositEffect'] as $unlock) {
                        $_item->deposit_effect = json_encode($unlock['Dsc__EN']);
                    }
                }

                if (array_key_exists("ComposeRecipe", $result)) {
                    foreach ($result['ComposeRecipe']['composeTo'] as $key => $compose) {
                        $itemcompose = new ItemCompose();
                        $itemcompose->item_id = $item->id;
                        $itemcompose->is_input = $compose['isInput'];      
                        $itemcompose->cost = $compose['cost'];
                        $itemcompose->item_output = $compose['output'][0]['id'];
                        $itemcompose->save();
    
                        foreach ($compose['input'] as $key => $material) {
                            $checkitem = Item::where('key_id', $material['id'])->first();
    
                            if ($checkitem != null) {
                                $itemcomposematerial = new ItemComposeMaterial();
                                $itemcomposematerial->item_compose_id = $itemcompose->id;
                                $itemcomposematerial->item_id = $material['id'];
                                $itemcomposematerial->qty = $material['quantity'];
                                $itemcomposematerial->save();
                            }
                        }
                    }
                }

                $_item->save();

            } catch (\Throwable $th) {
                throw $th;
            }

            
        }
    }

    public function removeAsterisk() {
        $items = Item::where('name_en', 'LIKE', '%*%')->get();

        foreach ($items as $item) {
            $_item = Item::find($item->id);
            $_item->name_en = str_replace('*', '', $_item->name_en);
            $_item->save();
        }
    }

    public function addSlugInItems() {
        $items = Item::get();

        foreach ($items as $item) {
            $_item = Item::find($item->id);
            $_item->slug = $this->createSlug($item->name_en);
            $_item->save();
        }
    }
    
    public function renameSlots() {
        $items = Item::get();

        foreach ($items as $item) {
            $a = $item->name_en;
            if (strpos($a, '[1]') !== false) {
                $item->name_en = preg_replace('/\s+/', ' ',str_replace("[1]"," [1]",$a));
                $item->save();
            }
            if (strpos($a, '[2]') !== false) {
                $item->name_en = preg_replace('/\s+/', ' ',str_replace("[2]"," [2]",$a));
                $item->save();
            }
        }
    }

    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Item::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

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

    public function addJobClass() {

        $equipments = Item::whereIn('type_name', $this->equips__)->get();

        foreach ($equipments as $equip) {
            $jobs = [];
        
            $can_equip = is_array($equip->can_equip) ? $equip->can_equip : json_decode($equip->can_equip, true);
            if ($can_equip != null) {
                $anothecheck = is_array($can_equip) ? $can_equip : json_decode($can_equip, true);
                foreach($anothecheck as $v) {
                    $jobs[] = $this->jobs__[$v];
                }
            }

            $item = Item::find($equip->id);
            $item->jobclass = $jobs;
            $item->save();
        }

    }
    
    public function getTypeName() {

        $types = [];
        $items = Item::where('id', '>=', 3593)->distinct()->select('type_name')->get();

        foreach ($items as $key => $item) {
            $types[] = $item->type_name;
        }
      

        return $types;
    }

    public function addFurniture() {
       $json = '
{"code":0,"status":"success","timeStamp":1595759256,"staticDomain":"https:\/\/rostatic.zhaiwuyu.com","ip":"49.145.201.57","dbArea":4,"data":{"sql":"select sea_x_item.xid, sea_x_item.name, sea_x_item.icon, sea_furniture.type, sea_furniture.row, sea_furniture.col, sea_furniture.height, sea_furniture.score, sea_furniture.unlock, sea_furniture.adventure_buff, sea_furniture.is_compose from sea_furniture,sea_x_item where sea_furniture.status=0 and sea_furniture.xid=sea_x_item.xid order by sea_furniture.id desc limit 300,30","page":11,"pageSize":30,"pageCount":11,"total":312,"list":[{"id":"30011","name":"Bed - Deep Dream","icon":"item_30011","type":7,"row":0,"col":0,"height":0,"score":40,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +40","isCompose":1},{"id":"30010","name":"Desk - Carving Time","icon":"item_30010","type":3,"row":0,"col":0,"height":0,"score":16,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +60","isCompose":1},{"id":"30009","name":"Bedside Cupboard - Greenee","icon":"item_30009","type":3,"row":0,"col":0,"height":0,"score":16,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +60","isCompose":1},{"id":"30008","name":"Chair - Whisper in the Woods","icon":"item_30008","type":17,"row":0,"col":0,"height":0,"score":33,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +30, Atk +1.5","isCompose":1},{"id":"30007","name":"Chair - Pure Autumn","icon":"item_30007","type":2,"row":0,"col":0,"height":0,"score":8,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +20, Atk +1","isCompose":1},{"id":"30006","name":"Carpet - \u201cWelcome Home\u201d","icon":"item_30006","type":6,"row":0,"col":0,"height":0,"score":16,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Def +1.5, M.Def +1.5","isCompose":1},{"id":"30005","name":"Carpet - Chubby Fluffy","icon":"item_30005","type":6,"row":0,"col":0,"height":0,"score":16,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Def +1.5, M.Def +1.5","isCompose":1},{"id":"30004","name":"Wall Lamp - Glimmering","icon":"item_30004","type":4,"row":0,"col":0,"height":0,"score":8,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +20, M.Atk +1","isCompose":1},{"id":"30003","name":"Cabinet - Melodious","icon":"item_30003","type":3,"row":0,"col":0,"height":0,"score":16,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +60","isCompose":1},{"id":"30002","name":"Sofa - Time Rocking Chair","icon":"item_30002","type":2,"row":0,"col":0,"height":0,"score":33,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +30, Atk +1.5","isCompose":1},{"id":"30001","name":"Fireplace - Flowing Fire","icon":"item_30001","type":1,"row":0,"col":0,"height":0,"score":55,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +60, M.Atk +3","isCompose":1},{"id":"30000","name":"Bookshelf - Treasure","icon":"item_30000","type":10,"row":0,"col":0,"height":0,"score":29,"unlock":"Activate Home Functions to Unlock","adventureBuff":"Max HP +30, M.Atk +1.5","isCompose":1}]}}

';
     $ids = [];

     $d = json_decode($json, true);
     $results = $d['data']['list'];

     foreach ($results as $key => $result) {
         $ids[] = $result['id'];
        //  $ids['score'] = $result['score'];

        $item = Item::where('key_id', $result['id'])->first();
        $item->stat_type = $result['type'] == '' ? NULL : $result['type'];
        $item->save();
     }

    // return ($results);
    
    //  foreach ($ids as $id) {
    //     try {
            
    //         $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
    //         $result = json_decode($json, true);

    //         $checkItem = Item::where('key_id', $id)->first();

    //         if (!$checkItem) {

    //             copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug(array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : ''.'-'.$result['TypeName'], '-').'-img.jpg'));

    //             $item = new Item();
    //             $item->key_id = $result['key_id'];
    //             $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
    //             $item->icon = Str::slug(array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : ''.'-'.$result['TypeName'], '-').'-img.jpg';
    //             $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
    //             $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
    //             $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
    //             $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
    //             $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
    //             $item->quality = array_key_exists("Quality", $result) ? $result['Quality'] : null;
    //             $item->special = array_key_exists("Condition", $result) ? $result['Condition'] : null;
    //             $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
    //             $item->slug = array_key_exists("NameZh__EN", $result) ? $this->createSlug($result['NameZh__EN']) : null;
                
    //             $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
    //             $item->compose_output_id = array_key_exists("ComposeOutputID", $result) ? $result['ComposeOutputID'] : null;
    //             $item->compose_id = array_key_exists("ComposeID", $result) ? $result['ComposeID'] : null;

    //             if (array_key_exists("AttrData", $result)) {
    //                 if (array_key_exists("Stat", $result['AttrData'])) {
    //                     $item->stat = json_encode($result['AttrData']['Stat']);
    //                 }
    //                 if (array_key_exists("StatExtra", $result['AttrData'])) {
    //                     $item->stat_extra = json_encode($result['AttrData']['StatExtra']);
    //                 }
    //                 if (array_key_exists("Type", $result['AttrData'])) {
    //                     $item->stat_type = json_encode($result['AttrData']['Type']);
    //                 }
    //                 if (array_key_exists("CanEquip", $result['AttrData'])) {
    //                     $item->can_equip = json_encode($result['AttrData']['CanEquip']);
    //                 }
    //             }

    //             if (array_key_exists("UnlockEffect", $result)) {
    //                 foreach ($result['UnlockEffect'] as $unlock) {
    //                     $item->unlock_effect = json_encode($unlock['Dsc__EN']);
    //                 }
    //             }

    //             if (array_key_exists("DepositEffect", $result)) {
    //                 foreach ($result['DepositEffect'] as $unlock) {
    //                     $item->deposit_effect = json_encode($unlock['Dsc__EN']);
    //                 }
    //             }

    //             $item->compose_recipe = array_key_exists("ComposeRecipe", $result);
    //             $item->prior_equipment = array_key_exists("PriorEquipment", $result) ? $result['PriorEquipment']['key_id'] : null;

    //             $item->save();

    //             if (array_key_exists("ComposeRecipe", $result)) {

    //                 if ($result['TypeName'] == 'Furniture Blueprint') {
    //                     foreach ($result['ComposeRecipe']['composeTo'] as $key => $compose) {

    //                         $checkCompose = ItemCompose::where('item_id', $item->id)->first();
    //                         if ($checkCompose) {
    
    //                             $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
    //                             foreach ($$composematerials as $composematerial) {
    //                                 $composematerial->delete();
    //                             }
    
    //                             $checkCompose->delete();
    //                         }
    
    //                         $itemcompose = new ItemCompose();
    //                         $itemcompose->item_id = $item->id;
    //                         $itemcompose->is_input = $compose['isInput'];      
    //                         $itemcompose->cost = $compose['cost'];
    //                         $itemcompose->item_output = $compose['output'][0]['id'];
    //                         $itemcompose->save();
    
    //                         foreach ($compose['input'] as $key => $material) {
    //                             $checkitem = Item::where('key_id', $material['id'])->first();
    
    //                             $itemcomposematerial = new ItemComposeMaterial();
    //                             $itemcomposematerial->item_compose_id = $itemcompose->id;
    //                             $itemcomposematerial->item_id = $material['id'];
    //                             $itemcomposematerial->qty = $material['quantity'];
    //                             $itemcomposematerial->save();

    //                             if ($checkitem == null) {
    //                                 $this->getHeadwearss($material['id']);
    //                             }
                                
    //                         }
    //                     }
    //                 } else {
    //                     foreach ($result['ComposeRecipe']['composeFrom'] as $key => $compose) {

    //                         $checkCompose = ItemCompose::where('item_id', $item->id)->first();
    //                         if ($checkCompose) {
    
    //                             $composematerials = ItemComposeMaterial::where('item_compose_id', $checkCompose->id)->get();
    //                             foreach ($$composematerials as $composematerial) {
    //                                 $composematerial->delete();
    //                             }
    
    //                             $checkCompose->delete();
    //                         }
    
    //                         $itemcompose = new ItemCompose();
    //                         $itemcompose->item_id = $item->id;
    //                         $itemcompose->is_input = $compose['isInput'];      
    //                         $itemcompose->cost = $compose['cost'];
    //                         $itemcompose->item_output = $compose['output'][0]['id'];
    //                         $itemcompose->save();
    
    //                         foreach ($compose['input'] as $key => $material) {
    //                             $checkitem = Item::where('key_id', $material['id'])->first();
    
    //                             $itemcomposematerial = new ItemComposeMaterial();
    //                             $itemcomposematerial->item_compose_id = $itemcompose->id;
    //                             $itemcomposematerial->item_id = $material['id'];
    //                             $itemcomposematerial->qty = $material['quantity'];
    //                             $itemcomposematerial->save();

    //                             if ($checkitem == null) {
    //                                 $this->getHeadwearss($material['id']);
    //                             }
                                
    //                         }
    //                     }
    //                 }
    //             }
                
    //         }
   

    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    //  }
    //  foreach ($results as $key => $result) {
    //     // $ids[] = $result['id'];
    //    //  $ids['score'] = $result['score'];

    //    $item = Item::where('key_id', $result['id'])->first();
    //    $item->score = $result['score'] == '' ? NULL : $result['score'];
    //    $item->requirements = $result['unlock'] == '' ? NULL : $result['unlock'];
    //    $item->save();
    // }







    }


}
