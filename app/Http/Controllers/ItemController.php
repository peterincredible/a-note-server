<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function items(){
        $user_id = request()->user()->id;
        $items = Item::where("user_id",$user_id)->get();
        return response()->json($items);
    }
    public function additem(){
        $user_id = request()->user()->id;
        $item = new Item;
        $item->note = request()->input('note');
        $item->user_id = $user_id;
        $item->save();
        return response()->json($item);
    }
    public function deleteitem($id){
        Item::destroy($id);
        return response()->json($id);
    }
    public function checkedTrigger($id){
         $item = Item::find($id);
         $item->checked = !$item->checked;
         $item->save();
         return response()->json($item);

    }
}
