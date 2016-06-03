<?php

use Illuminate\Database\Seeder;

class InterestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interests = \App\Helpers\DataUtils::$interests;

        foreach($interests as $key => $value){
            \App\Interest::create([
               'name' => $value
            ]);
        }
    }
}
