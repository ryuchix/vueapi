<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $hidden = ['created_at', 'key_id'];

    protected $table = 'pets';

    public function skills() {
        return $this->hasMany('App\PetSkill', 'pet_id');
    }

    public function getIconAttribute($value) {
        return $this->attributes['icon'] = url('uploads/pets/'.$value);
    }

    public function getComposeAttribute($value) {
        $json = json_decode($value, true);
        
        if ($json != null) {
            $ids = [];
            foreach ($json as $value) {
                $ids[] = $value['id'];
            }
    
            $pets = Pet::whereIn('key_id', $ids)->select('id','slug','icon', 'name')->get();
    
            return $pets;
        }
    }

    public function getFoodAttribute($value) {
        $json = json_decode($value, true);
        
        if ($json != null) {
            $ids = [];
            foreach ($json as $value) {
                $ids[] = $value['id'];
            }
    
            $pet = Item::where('key_id', $ids)->select('id','slug','icon', 'name_en')->first();
    
            return $pet;
        }
    }

    public function getUpdatedAtAttribute($value) {
        $dt = date_create($value);
        return $this->attributes['updated_at'] = date_format($dt, 'Y-m-d');
    }
}
