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

}
