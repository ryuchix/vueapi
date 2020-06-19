<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    public function maps() {
        return $this->belongsToMany('App\Map');
    }

    public function items() {
        return $this->belongsToMany('App\Item')->withPivot('qty');
    }

    public function getIconAttribute($value) {
        return $this->attributes['icon'] = url('uploads/monsters/'.$value);
    }

    public function getTypeAttribute($value) {
        return $this->attributes['type'] = $value != 'Monster' ? strtolower($value) : '';
    }

    public function getStarAttribute($value) {
        return $this->attributes['type'] = $value == 1 ? 'star' : '';
    }

    public function getSizeAttribute($value) {
        return $this->attributes['size'] = $value == 'L' ? 'Large' : ($value == 'M' ? 'Medium' : ($value == 'S' ? 'Small' : ''));
    }
}
