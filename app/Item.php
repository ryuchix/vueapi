<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function monsters() {
        return $this->belongsToMany('App\Monster');
    }

}
