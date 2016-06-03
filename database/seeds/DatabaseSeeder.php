<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(HugTypeSeeder::class);
        $this->call(UserTypeTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(InterestTableSeeder::class);
        $this->call(TechCategoryTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PerkSeeder::class);
        $this->call(SiteMetaTableSeeder::class);
        $this->call(CommunitiesSeeder::class);
        Model::reguard();
    }
}
