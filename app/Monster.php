<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Monster extends Model
{
    public function maps() {
        return $this->belongsToMany('App\Map');
    }

    public function items() {
        return $this->belongsToMany('App\Item')->withPivot('qty')->select('id', 'slug', 'icon', 'name_en', 'name_ch', 'type_name', 'type');
    }

    public function getIconAttribute($value) {
        return $this->attributes['icon'] = url('uploads/monsters/'.$value);
    }

    public function getTypeAttribute($value) {
        
        $mons = Monster::where('id', $this->attributes['id'])->first();
        $isUndead = $value == 'MVP' && $mons->key_id >= 204000;

        $query = $isUndead ? 'undead' : ($value == 'Monster' && $mons->star != 1 ? 'Normal' : ($value == 'MVP' ? 'mvp' : ($value == 'MINI' ? 'mini' : ($mons->star == 1 ? 'star' : ''))));

        return $this->attributes['type'] = $query;
    }

    public function getSizeAttribute($value) {
        return $this->attributes['size'] = $value == 'L' ? 'Large' : ($value == 'M' ? 'Medium' : ($value == 'S' ? 'Small' : ''));
    }
}
