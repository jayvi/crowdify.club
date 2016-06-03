<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/25/15
 * Time: 6:06 PM
 */

namespace App\Repositories;


use App\City;
use App\Interest;
use App\Talent;
use App\TechCategory;
use App\talentcategory;

class DataRepository
{

    public function getCountries($withSelect = false){

        return [
            'Bangladesh' => 'Bangladesh'
        ];
    }

    public function getCities($withSelect = false){
        if($withSelect)
            return ['' => '--select--'] + City::orderBy('name','asc')->lists('name', 'name')->toArray();
        return City::orderBy('name','asc')->lists('name', 'name');
    }


    public function getInterests($withSelect = false){
        if($withSelect)
            return ['' => '--select--'] + Interest::orderBy('name','asc')->lists('name', 'name')->toArray();
        return Interest::orderBy('name','asc')->lists('name', 'name');
    }

    public function getCategories($withSelect = false){
        if($withSelect)
            return ['' => '--select--'] + TechCategory::orderBy('name','asc')->lists('name', 'name')->toArray();
        return TechCategory::orderBy('name','asc')->lists('name', 'name');
    }
    public function getTalents($withSelect = false){
        if($withSelect)
            return ['' => '--select--'] + talentcategory::orderBy('name','asc')->lists('name', 'name')->toArray();
        return talentcategory::orderBy('name','asc')->lists('name', 'name');
    }
}