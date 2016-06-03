<?php

use Illuminate\Database\Seeder;

class PerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        App\PerkType::create(['type' => 'Free']);

       $users = App\User::take(15)->get();
        foreach($users as $user){

           $date = $faker->dateTimeBetween(\Carbon\Carbon::now()->subDays(48), \Carbon\Carbon::now());
            App\Perk::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'value' => $faker->numberBetween(100, 10000),
                'user_id' => $user->id,
                'type_id'  => 1,
                'logo_url' => $faker->imageUrl(600, 400),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
