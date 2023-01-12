<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'id' => 501,
            'name' => 'Lỗ Văn Long',
            'phone' => '0374638603',
            'address_line1' => 'Tự lập, Mê Linh, Hà Nội'
        ]);
    }
}
