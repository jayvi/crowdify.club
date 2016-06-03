<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/23/15
 * Time: 1:10 PM
 */

namespace App\Services\Klout;


use Illuminate\Support\Facades\Config;

class KloutService
{

    private $kloutApi;

    public function __construct()
    {
        $kloutConfig = Config::get('services')['klout'];
        $this->kloutApi = new KloutAPIv2($kloutConfig['api_key'],$kloutConfig['api_version']);
    }

    public function getScoreByTwitterName($username)
    {
        $kloutId = $this->getKloutId($username);
        if(!is_null($kloutId)){
            return $this->getScoreByKloutId($kloutId);
        }
        return 0;
    }

    public function getKloutId($username)
    {
        return $this->kloutApi->KloutIDLookupByName('twitter',$username);
    }

    public function getScoreByKloutId($kloutId)
    {
        $kloutScore = $this->kloutApi->KloutScore($kloutId);
        if(!is_null($kloutScore)){
            return $kloutScore;
        }
        return 0;
    }



}