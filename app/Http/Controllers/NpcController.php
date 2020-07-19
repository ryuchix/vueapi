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

    public function getHeadwears(Request $request) { 
        $url = $request->url;
        $removedString = str_replace('https://www.romcodex.com/item/', '', $url);
        $id = strstr($removedString, '/', true);

        try {
            
            $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
            $result = json_decode($json, true);

            $checkItem = Item::where('key_id', $id)->first();

            if (!$checkItem) {

                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.time(), '-').'-img.jpg'));
                
                $item = new Item();
                $item->key_id = $result['key_id'];
                $item->slug = $this->createSlug($result['NameZh__EN']);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN'].'-'.time(), '-').'-img.jpg';
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

                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'].'-'.time(), '-').'-img.jpg'));
                
                $item = new Item();
                $item->key_id = $result['key_id'];
                $item->slug = $this->createSlug($result['NameZh__EN']);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN'].'-'.time(), '-').'-img.jpg';
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
        $items = Item::whereIn('type_name', $this->cards__)->get();

        foreach ($items as $item) {
            $_item = Item::where('key_id', $item->key_id)->first();

            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$item->key_id);
                $result = json_decode($json, true);


                $_item->special = $result['Condition'];

                if (array_key_exists("AttrData", $result)) {
                    if (array_key_exists("Stat", $result['AttrData'])) {
                        $_item->stat = json_encode($result['AttrData']['Stat']);
                    }
                    if (array_key_exists("StatExtra", $result['AttrData'])) {
                        $_item->stat_extra = json_encode($result['AttrData']['StatExtra']);
                    }
                    if (array_key_exists("CardPicture", $result['AttrData'])) {
                        $cardIconId = $result['AttrData']['CardPicture'];
                        copy('https://www.romcodex.com/pic/cards/'.$cardIconId.'.jpg', public_path('/uploads/items/'.Str::slug($_item->name_en, '-').'-img.jpg'));
                        $_item->icon = Str::slug($_item->name_en, '-').'-img.jpg';
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

    public function getMissingInfoInHeadwears() {
        $items = Item::whereIn('type_name', $this->headwears__)->get();

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



}
