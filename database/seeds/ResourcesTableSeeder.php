<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Resource;
use Faker\Generator as Faker;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user){
        	Resource::Create([
        		'last_name' => $user->name,
        		'user_id' => $user->id,
        		'costcenter' => mt_rand(1, 30)
        	]);
        });
    }
}
