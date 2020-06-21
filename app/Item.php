<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function monsters() {
        return $this->belongsToMany('App\Monster');
    }

    public function getIconAttribute($value) {
        return $this->attributes['icon'] = url('uploads/items/'.$value);
    }

    public function getStatExtraAttribute($value) {
        return $this->attributes['stat_extra'] = json_decode($value, true);
    }
    
    public function getStatAttribute($value) {
        return $this->attributes['stat_extra'] = json_decode($value, true);
    }

    public function getCanEquipAttribute($value) {
        return $this->attributes['can_equip'] = json_decode($value, true);
    }

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

    public function ItemList() {
        $equips = [
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
            'Off-hand - Shield'];
            
        return $equips;
    }

}
