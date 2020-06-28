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
}
