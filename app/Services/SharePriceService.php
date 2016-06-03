<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/14/15
 * Time: 10:35 PM
 */

namespace App\Services;


use App\MonthlySharePrice;
use App\User;
use App\Item;
use Illuminate\Support\Facades\Log;

class SharePriceService
{
    public function saveMonthlyRecord(){
        $items = Item::get();
        foreach ($items as $item) {
            $data['share_price'] = 1;
            $item->update($data);
        }
        $users = User::where('is_email','=',true)->get();
        foreach($users as $user){

            try{
                $mainItem = $user->items()->where('key','=','main')->first();
                $category_1_item = $user->items()->where('key','=','category_1')->first();
                $category_2_item = $user->items()->where('key','=','category_2')->first();

                $monthlySharePrice = new MonthlySharePrice();
                $monthlySharePrice->user_id = $user->id;
                $monthlySharePrice->main_price = 0;
                $monthlySharePrice->category_1_price = 0;
                $monthlySharePrice->category_2_price = 0;
                $monthlySharePrice->save();
            }catch (\Exception $e){
                Log::error($e->getMessage());
            }
        }
    }
}