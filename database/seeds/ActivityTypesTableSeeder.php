<?php

use Illuminate\Database\Seeder;

class ActivityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_types')->insert([
            [
                'name' => 'Gửi báo giá'
            ],
            [
                'name' => 'Gửi hình ảnh'
            ],
            [
                'name' => 'Hỏi thăm khách'
            ],
            [
                'name' => 'Hẹn khách ra Showroom'
            ],
            [
                'name' => 'Hẹn đo nhà'
            ],
            [
                'name' => 'Lên đơn hàng'
            ],
            [
                'name' => 'Chốt đơn hàng'
            ],
            [
                'name' => 'Khác'
            ]

        ]);
    }
}
