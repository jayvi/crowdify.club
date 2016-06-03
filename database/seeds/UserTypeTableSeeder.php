<?php

use App\UserType;
use Illuminate\Database\Seeder;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        UserType::create([
            'role' => 'Free'
        ]);

        UserType::create([
            'role' => 'Paid'
        ]);

        UserType::create([
            'role' => 'Founding Member'
        ]);
        UserType::create([
            'role' => 'Sponsor'
        ]);

        UserType::create([
            'role' => 'Admin'
        ]);
    }
}