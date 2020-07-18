<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSet extends Model
{
    protected $table = 'item_sets';

    public function getItemsAttribute($value) {
        $item = Item::where('key_id', $value)->select('id', 'slug', 'type', 'name_en', 'icon')->first();
        $sets = json_decode($value, true);
        $arrayOfSets = [];
        if ($sets != null) {
            foreach ($sets as $key => $set) {
                $item = Item::where('key_id', $set['key_id'])->select('id', 'type', 'slug', 'name_en', 'icon')->first();
                $arrayOfSets[] = $item;
            }
        }

        return $this->attributes['items'] = $arrayOfSets;
    }
}
