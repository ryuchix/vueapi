<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTier extends Model
{
    protected $table = 'item_tiers';

    public function materials() {
        return $this->hasMany('App\ItemTierMaterial', 'item_tier_id');
    }
}
