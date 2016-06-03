<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 5/4/16
 * Time: 7:54 PM
 */

namespace App\Services;


use App\City;

class CityUpdaterService
{

    public function update()
    {
        $cities = $this->getNewCities();
        foreach ($cities as $cityData){
            if(!City::where('title','=',$cityData['title'])->exists()){
                $city = new City($cityData);
                $city->save();
                try{
                    $this->updateCityInfo($city);
                }catch(\Exception $e){
                    
                }

            }
        }
    }

    private function getNewCities()
    {
        return [
            ['name' => 'Durban, South Africa','title' => 'Durban'],
            ['name' => 'Adelaide, Australia','title' => 'Adelaide'],
        ];
    }

    private function updateCityInfo($city)
    {
        if(!$city)
            return;

        $citytitle = $city->title;
        $cityurl = urlencode($citytitle);
        $url = 'https://en.wikipedia.org/w/api.php?action=query&titles='.$cityurl.'&prop=pageimages&format=json&pithumbsize=400&redirects';
        $pics = file_get_contents($url);
        $pics = json_decode($pics);
        $pics = $pics->query->pages;
        if($pics) {
            foreach ($pics as $pic) {
                if (isset($pic->thumbnail)) {
                    $pic = $pic->thumbnail->source;
                    $city->city_photo = $pic;
                    $city->update();
                    break;
                }
            }

        }

    }


}