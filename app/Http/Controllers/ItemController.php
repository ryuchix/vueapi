<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Str;

class ItemController extends Controller
{
    public function getItem($id) {
        $json = file_get_contents('https://www.romcodex.com/api/item/'.$id);
        $result = json_decode($json, true);

        $checkItem = Item::where('key_id', $result['key_id'])->first();

        if ($checkItem == null) {
            $item = new Item();
            $item->key_id = $result['key_id'];
            $item->SellPrice = $result['SellPrice'];
            copy('https://www.romcodex.com/icons/item/item_'.$id.'.png', public_path('/uploads/items/'.Str::slug($result['NameZh__EN'], '-').'.png'));
            $item->Icon = Str::slug($result['NameZh__EN'], '-').'.png';
            $item->Desc = $result['Desc'];
            $item->Desc__EN = $result['Desc__EN'];
            $item->Type = $result['Type'];
            $item->NameZh = $result['NameZh'];
            $item->NameZh__EN = $result['NameZh__EN'];
            $item->AuctionPrice = $result['AuctionPrice'];
            $item->TypeName = $result['TypeName'];
            $item->Quality = $result['Quality'];
            $item->ComposeID = $result['ComposeID'];
            $item->ComposeOutputID = $result['ComposeOutputID'];
            $item->UnlockEffect = $result['UnlockEffect'];
            $item->DepositEffect = $result['DepositEffect'];
            $item->ComposeRecipe = $result['ComposeRecipe'];
            $item->Stat = $result['Stat'];
            $item->StatExtra = $result['StatExtra'];
            $item->StatType = $result['StatType'];
            $item->CanEquip = $result['CanEquip'];
            $item->PriorEquipment = $result['PriorEquipment'];
            $item->TierList = $result['TierList'];
            $item->ItemSet = $result['ItemSet'];
            $item->SynthesisRecipe = $result['SynthesisRecipe'];
            $item->CanEquip = $result['CanEquip'];
            $item->save();
        }

        return response()->json(['message' => 'added'], 200); 
    }

    public function uploadImage() {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalName();
            $destinationPath = public_path('/uploads/groups/' . $id . '/images');
            $upload = $image->move($destinationPath, $name);
            $user = Group::where('id', $id)->update(['image' => $name]);

            return response()->json([
                'message' => 'Success',
                'image' => url(PATH::GROUP) . '/' . $id . '/images/' . $name,
            ], 200);
        }
    }
}
