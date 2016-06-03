<?php

use App\SiteMeta;
use Illuminate\Database\Seeder;

class SiteMetaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteMeta::truncate();

        SiteMeta::create([
            'key' => 'crowd_coin_gift_popup_count',
            'value' => 0
        ]);
    }
}
