<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/23/15
 * Time: 4:32 PM
 */

namespace App\Services\Klout;


use App\User;
use Carbon\Carbon;

class KloutScoreCalculatorService
{
    public function calculate()
    {

        $kloutService = new KloutService();

        $users = User::where('is_email','=',true)->get();
        foreach($users as $user){
            if($user->klout_id){
                $score = $kloutService->getScoreByKloutId($user->klout_id);
            }else{
                $kloutId = $kloutService->getKloutId($user->username);
                if($kloutId){
                    $user->klout_id = $kloutId;
                    $user->update();
                    $score = $kloutService->getScoreByKloutId($kloutId);
                }else{
                    continue;
                }

            }
            $this->increasePriceByKloutScore($user, $score);
        }
    }

    /**
     * @description Increase the share price by daily increment based on klout score
     * @param User $user
     * @param $score
     */
    private function increasePriceByKloutScore(User $user, $score)
    {
        $item = $user->items()->where('key', '=', 'main')->first();
        if($item->share_price < 100) {
            $item->share_price =  $item->share_price + (float)$this->getIncrement($score);
            $item->update();
        }

    }


    /**
     *@description Get per day price increment based on klout score
     * @param $score
     * @return float
     */
    private function getIncrement($score)
    {
        $percentage = (75 * (float)$score) / 100; // 75 % of klout score
        $increment = $percentage / (Carbon::now()->endOfMonth()->diffInDays(Carbon::now()->startOfMonth())); //per day increment
        return $increment;
    }
}