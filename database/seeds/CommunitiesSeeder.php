<?php

use Illuminate\Database\Seeder;

class CommunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Community::create([
            'name' => 'General',
            'slug' => 'general',
            'description' => 'New to Crowdify.tech? Join the General community to meet helpful players who can answer your questions, help you jump into the game, and share tips and strategies that work.',
            'community_logo' => 'general-community.jpg',
            'status' => 1
        ]);

        \App\Community::create([
            'name' => 'Crowdify.tech Support',
            'slug' => 'crowdify-tech-support',
            'description' => "<p>Welcome back to Crowdify.tech.</p><p>Need help engaging other users?</p><p>More Established users/players,</br>Please help newer users with less experience here on EK or social media newbies/rookies too....</p><p>Let's keep this forum fun.</p>",
            'community_logo' => 'technical-support.jpg',
            'status' => 1
        ]);
    }
}
