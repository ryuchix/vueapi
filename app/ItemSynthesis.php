<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSynthesis extends Model
{
    protected $table = 'item_syntheses';

    public function equipments() {
        return $this->hasMany('App\ItemSynthesisEquipment', 'item_syntheses_id');
    }

    public function materials() {
        return $this->hasMany('App\ItemSynthesisMaterial', 'item_syntheses_id');
    }

    public function getItemOutputAttribute($value) {
        $item = Item::where('key_id', $value)->select('id', 'name_en', 'icon', 'type', 'type_name')->first();
        return $this->attributes['item_id'] = $item;
    }
}
