<?php

use Illuminate\Database\Seeder;

class TechCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $techCategories = \App\Helpers\DataUtils::$techCategories;

        foreach($techCategories as $techCategory){
            \App\TechCategory::create([
                'name' => $techCategory
            ]);
        }
    }
}
