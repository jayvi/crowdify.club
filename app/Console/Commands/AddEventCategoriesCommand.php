<?php

namespace App\Console\Commands;

use App\Category;
use App\TechCategory;
use Illuminate\Console\Command;

class AddEventCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events-categories:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command add the event categories';

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
        try{
            $techCategories = TechCategory::all();
            foreach ($techCategories as $techCategory){
                if(!Category::where('name','=',$techCategory->name)->exists()){
                    $eventCategory = new Category();
                    $eventCategory->name = $techCategory->name;
                    $eventCategory->save();
                }
            }
        }catch(\Exception $e){

        }

    }
}
