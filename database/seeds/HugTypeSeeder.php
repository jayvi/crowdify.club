<?php

use App\HugType;
use Illuminate\Database\Seeder;

class HugTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        HugType::create([
            'hug_type' => 'Visit Url'
        ]);

        HugType::create([
            'hug_type' => 'Twitter Post'
        ]);

        HugType::create([
            'hug_type' => 'Facebook Post'
        ]);
    }
}
