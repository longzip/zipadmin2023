<?php

use Illuminate\Database\Seeder;
use App\User;
use NoiThatZip\Contact\Models\Contact;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user){
        	$user->contacts()->saveMany(factory(\NoiThatZip\Contact\Models\Contact::class,3)->make());
        });
        Contact::all()->each(function($contact){
        	$contact->setStatus('New');
        	$contact->syncCategories(mt_rand(1, 60));
        	$contact->syncCostcenters(User::find($contact->user_id)->costcentersList()->pluck('id')[0]);
        });
    }
}
