<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = \App\Helpers\DataUtils::$cities;

        foreach($cities as $city){
            \App\City::create([
                'name' => $city
            ]);
        }
    }
}
