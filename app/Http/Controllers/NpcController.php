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

        '40057',
        '140057',
        '40058',
        '140058',
        '40059',
        '140059',
        '40066',
        '140066',
        '40359',
        '140359',
        '40360',
        '140360',
        '40361',
        '140361',
        '40660',
        '140660',
        '40661',
        '140661',
        '40662',
        '140662',
        '40663',
        '140663',
        '40671',
        '140671',
        '40676',
        '140676',
        '40766',
        '140766',
        '40767',
        '140767',
        '40944',
        '140944',
        '40945',
        '140945',
        '41254',
        '141254',
        '41255',
        '141255',
        '41256',
        '141256',
        '41257',
        '141257',
        '41258',
        '141258',
        '41565',
        '141565',
        '41566',
        '141566',
        '41567',
        '141567',
        '41568',
        '141568',
        '41867',
        '141867',
        '41868',
        '141868',
        '41869',
        '141869',
        '41870',
        '141870',
        '41871',
        '141871',
        '44308',
        '144308',
        '44309',
        '144309',
        '44310',
        '144310',
        '62540',
        '162540',
        '62541',
        '162541',
        '62542',
        '162542',
        '162551',
        '62840',
        '162840',
        '62844',
        '162844',
        '63140',
        '163140',
        '63144',
        '163144',
        '63440',
        '163440',
        '63444',
        '163444',
        '42086',
        '142086',
        '142087',
        '42087',
        '42088',
        '142088',
        '42089',
        '142089',
        '42090',
        '142090',
        '42091',
        '142091',
        '42092',
        '142092',
        '42093',
        '142093',
        '42094',
        '142094',
        '42095',
        '142095',
        '42096',
        '142096',
        '42097',
        '142097',
        '42098',
        '142098',
        '42099',
        '142099',
        '42100',
        '142100',
        '42101',
        '142101',
        '42102',
        '142102',
        '42103',
        '142103',
        '42104',
        '142104',
        '42105',
        '142105',
        '42106',
        '142106',
        '42107',
        '142107',
        '42113',
        '142113',
        '44047',
        '144047',
        '44048',
        '144048',
        '44049',
        '144049',
        '44050',
        '144050',
        '44051',
        '144051',
        '44052',
        '144052',
        '44053',
        '144053',
        '44054',
        '144054',
        '44055',
        '144055',
        '44056',
        '144056',
        '44057',
        '144057',
        '44058',
        '144058',
        '44059',
        '144059',
        '44060',
        '144060',
        '44061',
        '144061',
        '44062',
        '144062',
        '44063',
        '144063',
        '44066',
        '144066',
        '44069',
        '144069'
    ];

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
}
