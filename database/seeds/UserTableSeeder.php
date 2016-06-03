<?php

use App\Bank;
use App\Helpers\DataUtils;
use App\Hug;
use App\HugType;
use App\Item;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
        $itemsArray = array('main'=>'Main','city'=> 'City', 'category_1'=> 'Mobile Apps', 'category_2'=> 'Web Apps', 'interest'=> 'Information');



       for($i = 0; $i< 50; $i++){
           $user =  User::create([
                'first_name' => $faker->name,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->email,
                'username' => $faker->unique()->userName,
                'password' => bcrypt(str_random(10)),
                'remember_token' => str_random(10),
                'usertype_id' => 1,
                'is_email' => true,
               'avatar' => $faker->imageUrl(48, 48)
            ]);

           foreach($itemsArray as $key => $value){
               $item = new Item();
               $item->key = $key;
               if($key == 'city'){
                   $item->value = $faker->city;
               }else{
                   $item->value = $value;
               }
               $item->user_id = $user->id;
               $item->save();
           }


           $bank = new Bank();
           $bank->crowd_coins = DataUtils::INITIAL_CROWD_COINS;
           $bank->user_id = $user->id;
           $bank->save();

           $date = $faker->dateTimeBetween(\Carbon\Carbon::now()->subDays(48), \Carbon\Carbon::now());

           Hug::create([
               'title' => $faker->sentence,
               'link' => $faker->url,
               'description' => $faker->paragraph,
               'total_amount' => $faker->numberBetween(10000, 50000),
               'reward' => $faker->numberBetween(100, 1000),
               'approved' => 1,
               'user_id' => $user->id,
               'hug_type_id' => HugType::where('hug_type', '=', 'Visit Url')->first()->id,
               'status' => 'Active',
               'expired_date' => Carbon::now()->addHours(48),
               'photo' => $faker->imageUrl(600, 400),
               'created_at' => $date,
               'updated_at' => $date
           ]);

//           $hug = new Hug();
//           $hug->title = $faker->sentence;
//           $hug->link = $faker->url;
//           $hug->description = $faker->paragraph;
//           $hug->total_amount = $faker->numberBetween(10000, 50000);
//           $hug->reward = $faker->numberBetween(100, 1000);
//           $hug->approved = 1;
//           $hug->user_id = $user->id;
//           $hug->hug_type_id = HugType::where('hug_type', '=', 'Visit Url')->first()->id;;
//           $hug->status = 'Active';
//           $hug->expired_date = Carbon::now()->addHours(48);
//           $hug->photo = $faker->imageUrl(600, 400);
//           $hug->created_at = $date;
//           $hug->updated_at = $date;
//           $hug->save();

       }
    }
}
