<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSynthesisMaterial extends Model
{
    protected $table = 'item_synthesis_materials';

    public function getItemIdAttribute($value) {
        $item = Item::where('key_id', $value)->select('id', 'slug', 'name_en', 'icon', 'type', 'type_name')->first();
        return $this->attributes['item_id'] = $item;
    }
}
