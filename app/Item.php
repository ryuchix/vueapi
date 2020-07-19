<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $hidden = ['key_id', 'created_at', 'updated_at'];

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

    private $headwears__ = [
        'Face',
        'Costume',
        'Back',
        'Headwear',
        'Mouth',
        'Tail',
    ];


    public function monsters() {
        return $this->belongsToMany('App\Monster')->select('id', 'slug', 'name_en', 'icon', 'element', 'size', 'race', 'type', 'star');
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

    public function getUpdatedAtAttribute($value) {
        $dt = date_create($value);
        return $this->attributes['updated_at'] = date_format($dt, 'Y-m-d\TH:i:s.P');
    }

    // public function getCanEquipAttribute($value) {
    //     return $this->attributes['can_equip'] = is_array($value) ? $value : json_encode($value, true);
    // }

    public function getUnlockEffectAttribute($value) {
        return $this->attributes['unlock_effect'] = json_decode($value, true);
    }

    public function getDepositEffectAttribute($value) {
        return $this->attributes['deposit_effect'] = json_decode($value, true);
    }

    public function getStatTypeAttribute($value) {
        return $this->attributes['stat_type'] = json_decode($value, true);
    }

    public function getQualityAttribute($value) {
        if (in_array($this->attributes['type_name'], $this->cards__)) {
            return $this->attributes['quality'] = $value == 1 ? 'white' : ($value == 2 ? 'green' : ($value == 3 ? 'blue' : $value == 4 ? 'violet' : ''));
        }
    }

    // public function getTypeNameAttribute($value) {
    //     if (in_array($this->attributes['type_name'], $this->equips__)) {
    //         return $this->attributes['type'] = 'equips';
    //     }
    //     if (in_array($this->attributes['type_name'], $this->cards__)) {
    //         return $this->attributes['type'] = 'cards';
    //     }
    //     if (in_array($this->attributes['type_name'], $this->items__)) {
    //         return $this->attributes['type'] = 'items';
    //     }
    //     if (in_array($this->attributes['type_name'], $this->headwears__)) {
    //         return $this->attributes['type'] = 'headwears';
    //     }
    // }

    public function getTypeAttribute($value) {
        $item = Item::find($this->attributes['id']);
        if (in_array($item->type_name, $this->equips__)) {
            return $this->attributes['type'] = 'equips';
        } elseif (in_array($item->type_name, $this->cards__)) {
            return $this->attributes['type'] = 'cards';
        } elseif (in_array($item->type_name, $this->items__)) {
            return $this->attributes['type'] = 'items';
        } elseif (in_array($item->type_name, $this->headwears__)) {
            return $this->attributes['type'] = 'headwears';
        } else {
            return $this->attributes['type'] = null;
        }
    }
}
