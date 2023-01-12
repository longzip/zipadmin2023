<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Lỗ Văn Long',
            'email' => 'longlv@noithatzip.com',
            'password' => bcrypt('123456'),
            'username' => 'long'
        ]);
        User::find(1)->assignRole('qa','imports');
        // factory(App\User::class, 200)->create()->each(function($user){
        //     $user->syncCostcenters(mt_rand(1, 30));
        //     $user->assignRole('sales');
        // });
        // User::all()->each(function($user) {
        //     $user->syncCostcenters(mt_rand(2,30));
        // });
    }
}
