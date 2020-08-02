<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pet;
use App\PetSkill;
use App\Item;
use Str;

class PetController extends Controller
{

    public function index() {
        return Pet::with('skills')->orderBy('key_id', 'desc')->paginate();
    }

    public function getPet($id) {
        return Pet::where('slug', $id)->with('skills')->first();
    }

    public function filterPet(Request $request) {
        $q = Pet::query();

        if ($request->has('race')) {
            if ($request->race == 'All') {
                $q->whereIn('race', $this->races__);
            } else {
                $q->where('race', $request->race);
            }
        }

        if ($request->has('element')) {
            if ($request->element == 'All') {
                $q->whereIn('element', $this->elements__);
            } else {
                $q->where('element', $request->element);
            }
        }

        if ($request->has('size')) {
            if ($request->size == 'All') {
                $q->whereIn('size', ['S', 'M', 'L']);
            } else {
                $q->where('size', $request->size[0]);
            }
        }

        if ($request->has('type')) {
            if ($request->type == 'All') {
                $q->whereIn('type', ['MINI', 'MVP', 'Monster']);
            } elseif ($request->type == 'Star') {
                $q->where('star', 1);
            } elseif ($request->type == 'Monster') {
                $q->where('type', 'Monster');
            } elseif ($request->type == 'MINI') {
                $q->where('type', 'MINI');
            } elseif ($request->type == 'Undead') {
                $q->where('type', 'MVP')->where('element', 'Undead');
            } else {
                $q->where('type', $request->type);
            }
        }

        if ($request->has('order')) {
            if ($request->order == 'Name ASC') {
                return $q->orderBy('name_en', 'ASC')->paginate()->appends($request->all());
            }
            if ($request->order == 'Name Desc') {
                return $q->orderBy('name_en', 'DESC')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Level ASC') {
                return $q->orderByRaw('LENGTH(level) asc')->paginate()->appends($request->all());
            }
            if ($request->order == 'Level Desc') {
                return $q->orderByRaw('LENGTH(level) desc')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Base Exp') {
                return $q->orderByRaw('LENGTH(base_exp) desc')->paginate()->appends($request->all());
            } 
            if ($request->order == 'Job Exp') {
                return $q->orderByRaw('LENGTH(job_exp) desc')->paginate()->appends($request->all());
            }
        } else {
            return $q->paginate()->appends($request->all());
        }
        
    }

    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Pet::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function createPetItemSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedPetItemSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedPetItemSlugs($slug, $id = 0)
    {
        return Item::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }



   //  public function savePet() {

   //    $json = file_get_contents('https://api.npoint.io/76712a3acbbbf41d10f3');

   //           $ids = [];
        
   //           $d = json_decode($json, true);
   //           $results = $d['data']['list'];
        


   //          foreach ($results as $key => $result) {
   //              $ids[] = $result['id'];
                
   //                $checkpet = Pet::where('key_id', $result['id'])->first();

   //                if ($checkpet == null) {
   //                   try {
                        
                        
   //                      $pet = new Pet();
   //                      $pet->name = $result['name'];
   //                      $pet->slug = $this->createSlug($result["name"]);
   //                      $pet->icon = Str::slug($result["name"], '-').'-img.jpg';
   //                      $pet->element = $result['nature'];
   //                      $pet->race = $result['race'];
   //                      $pet->size = $result['size'];
   //                      $pet->key_id = $result['id'];
   //                      $pet->description = $result['intro'];
   //                      $pet->star = $result['star'];
   //                      $pet->unlock = $result['adventureBuff'];
   //                      $pet->capture_type = $result['accessway'];
   //                      $pet->food = json_encode($result['hobby']);
   //                      $pet->avatar = $result['avatar'];
                        
   //                      if ($result['hobby'] != null) {
   //                          foreach ($result['hobby'] as $food) {
   //                             $this->addPetItem($food['id']);
   //                          }
   //                      }
    
   //                      $pet->compose = json_encode($result['cost']);
        
                        
   //                      $pet->save();

   //                      if ($result['skill'] != null) {
   //                         foreach ($result['skill'] as $skill) {
   //                            $petSkill = new PetSkill();
   //                            $petSkill->pet_id = $pet->id;
   //                            $petSkill->name = $skill['name'];
   //                            $petSkill->description = $skill['intro'];
   //                            $petSkill->lvl = $skill['lv'];
   //                            $petSkill->save();
   //                         }
   //                      }

   //                      try {
   //                         copy('https://rostatic.zhaiwuyu.com/face/'.$result["avatar"].'.png', public_path('/uploads/pets/'.Str::slug($result["name"], '-').'-img.jpg'));
   //                      } catch (\Throwable $th) {
   //                         //throw $th;
   //                      }
    
   //                  } catch (\Throwable $th) {
   //                      throw $th;
   //                  }
   //                }


   //           }
   //  }



    public function addPetItem($id) {

        $checkItem = Item::where('key_id', $id)->first();

        if ($checkItem == null) {
            try {
                $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
                $result = json_decode($json, true);
            
                copy('https://www.romcodex.com/icons/item/'.$result['Icon'].'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN']. '-' .$result['TypeName'], '-').'_img.png'));
    
                
                $item = new Item();
    
                $item->key_id = $result['key_id'];
                $item->slug = $this->createPetItemSlug($result["NameZh__EN"]);
                $item->sell_price = array_key_exists("SellPrice", $result) ? $result['SellPrice'] : null;
                $item->icon = Str::slug($result['NameZh__EN']. '-' .$result['TypeName'], '-').'_img.png';
                $item->desc = array_key_exists("Desc", $result) ? $result['Desc'] : null;
                $item->desc_en = array_key_exists("Desc__EN", $result) ? $result['Desc__EN'] : null;
                $item->type = array_key_exists("Type", $result) ? $result['Type'] : null;
                $item->name_ch = array_key_exists("NameZh", $result) ? $result['NameZh'] : null;
                $item->name_en = array_key_exists("NameZh__EN", $result) ? $result['NameZh__EN'] : null;
                $item->auction_price = array_key_exists("AuctionPrice", $result) ? $result['AuctionPrice'] : null;
                $item->type_name = array_key_exists("TypeName", $result) ? $result['TypeName'] : null;
                $item->save();
            } catch (\Throwable $th) {
                throw $th;
            }
        }

    }













}
