<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
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


    public function monsters() {
        return $this->belongsToMany('App\Monster')->select('id', 'name_en', 'icon', 'element', 'size', 'race', 'type', 'star');
    }

    public function getIconAttribute($value) {
        return $this->attributes['icon'] = url('uploads/items/'.$value);
    }

    public function getStatExtraAttribute($value) {
        return $this->attributes['stat_extra'] = json_decode($value, true);
    }
    
    public function getStatAttribute($value) {
        return $this->attributes['stat'] = json_decode($value, true);
    }

    // public function getCanEquipAttribute($value) {
    //     return $this->attributes['can_equip'] = json_decode($value, true);
    // }

    public function getUnlockEffectAttribute($value) {
        $arrays = substr($value, 10);
        $removeArray = explode(', ', $arrays);
        $newArray = array_shift($removeArray);
        return $this->attributes['unlock_effect'] = implode('', $removeArray);
    }

    public function getDepositEffectAttribute($value) {
        $arrays = substr($value, 10);
        $removeArray = explode(', ', $arrays);
        $newArray = array_shift($removeArray);
        return $this->attributes['unlock_effect'] = implode('', $removeArray);
    }

    public function getTypeAttribute($value) {
        if (in_array($this->attributes['type_name'], $this->equips__)) {
            return $this->attributes['type'] = 'equips';
        }
        if (in_array($this->attributes['type_name'], $this->cards__)) {
            return $this->attributes['type'] = 'cards';
        }
        if (in_array($this->attributes['type_name'], $this->items__)) {
            return $this->attributes['type'] = 'items';
        }
    }
}
