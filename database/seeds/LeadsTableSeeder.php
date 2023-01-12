<?php

use Illuminate\Database\Seeder;
use App\User;
use NoiThatZip\Lead\Models\Lead;

class LeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user){
        	$user->leads()->saveMany(factory(\NoiThatZip\Lead\Models\Lead::class,2)->make());
        });
        Lead::all()->each(function($lead){
        	$lead->setStatus('New');
        	$lead->syncCategories(mt_rand(1, 60));
        	$lead->syncCostcenters(User::find($lead->creator_id)->resource->costcenter);
        });
    }
}
