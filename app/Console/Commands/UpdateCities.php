<?php

namespace App\Console\Commands;

use App\City;
use App\CityInfo;
use Illuminate\Console\Command;

class UpdateCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cities:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cities = City::all();
        foreach($cities as $city){
            $info = CityInfo::where('city','=',$city->name)->first();
            if($info){
                $city->user_id = $info->user_id;
                $city->title = $info->title;
                $city->description = $info->description;
                $city->city_photo = $info->city_photo;
                $city->update();
            }
        }
    }
}
