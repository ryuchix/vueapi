<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemComposeMaterial extends Model
{
    protected $table = 'item_compose_materials';

    public function getItemIdAttribute($value) {
        $item = Item::where('key_id', $value)->select('id', 'name_en', 'icon', 'type', 'type_name')->first();
        return $this->attributes['item_id'] = $item;
    }
}
