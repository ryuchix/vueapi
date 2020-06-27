<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCompose extends Model
{
    protected $table = 'item_composes';

    public function materials() {
        return $this->hasMany('App\ItemComposeMaterial', 'item_compose_id');
    }

    public function getItemOutputAttribute($value) {
        $item = Item::where('key_id', $value)->first();
        return $this->attributes['item_output'] = $item->name_en;
    }
}
