<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Item;

class ItemTierMaterial extends Model
{
    protected $table = 'item_tier_materials';

    public function getTierItemIdAttribute($value) {
        $item = Item::where('key_id', $value)->select('id', 'slug', 'name_en', 'icon', 'type', 'type_name')->first();
        return $this->attributes['tier_item_id'] = $item;
    }
}
