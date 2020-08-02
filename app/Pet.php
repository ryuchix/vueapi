<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $hidden = ['created_at', 'key_id'];


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

    public function getElementAttribute($value) {
        if ($value == 1) {
            $value = 'Earth';
        }
        if ($value == 3) {
            $value = 'Water';
        }
        if ($value == 5) {
            $value = 'Holy';
        }
        if ($value == 7) {
            $value = 'Ghost';
        }
        if ($value == 9) {
            $value = 'Poison';
        }
        if ($value == 0) {
            $value = 'Neutral';
        }
        if ($value == 2) {
            $value = 'Wind';
        }
        if ($value == 4) {
            $value = 'Fire';
        }
        if ($value == 6) {
            $value = 'Shadow';
        }
        if ($value == 8) {
            $value = 'Undead';
        }

        return $value;
    }

    public function getRaceAttribute($value) {
        if ($value == 1) {
            $value = 'DemiHuman';
        }
        if ($value == 3) {
            $value = 'Brute';
        }
        if ($value == 5) {
            $value = 'Fish';
        }
        if ($value == 7) {
            $value = 'Demon';
        }
        if ($value == 9) {
            $value = 'Dragon';
        }
        if ($value == 0) {
            $value = 'Formless';
        }
        if ($value == 2) {
            $value = 'Plant';
        }
        if ($value == 4) {
            $value = 'Insect';
        }
        if ($value == 6) {
            $value = 'Angel';
        }
        if ($value == 8) {
            $value = 'Undead';
        }

        return $value;
    }

    public function getSizeAttribute($value) {
        if ($value == 1) {
            $value = 'Small';
        }
        if ($value == 2) {
            $value = 'Medium';
        }
        if ($value == 3) {
            $value = 'Large';
        }

        return $value;
    }

    public function getUpdatedAtAttribute($value) {
        $dt = date_create($value);
        return $this->attributes['updated_at'] = date_format($dt, 'Y-m-d');
    }
}
