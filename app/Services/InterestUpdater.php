<?php
/**
 * Created by PhpStorm.
 * User: Adre
 * Date: 10/19/15
 * Time: 9:51 AM
 */

namespace App\Services;


use App\Interest;
use App\Item;
use App\User;

class InterestUpdater
{
    public function update(){
        $interests = Interest::all();

        foreach($interests as $interest){
            $interest->name = ucwords($interest->name);
            $interest->update();
        }

//        $users = User::all();

        $items = Item::where('key', '=', 'interest')->get();

        foreach($items as $item){
            $item->value = ucwords($item->value);
            $item->update();
        }
    }
}