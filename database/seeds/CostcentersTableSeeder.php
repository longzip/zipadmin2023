<?php

use Illuminate\Database\Seeder;
use \NoiThatZip\Costcenter\Models\Costcenter;

class CostcentersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Costcenter::create([
    		'code' => 'HNI_TC',
    		'name' => 'HNI Time City'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_RC',
    		'name' => 'HNI Royal City'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_SVC',
    		'name' => 'HNI Savico'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_IPH',
    		'name' => 'HNI IPH'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_PNT',
    		'name' => 'HNI Phạm Ngọc Thạch'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_BTL',
    		'name' => 'HNI Bắc Từ Liêm'
    	]);
    	Costcenter::create([
    		'code' => 'HNI_ML',
    		'name' => 'HNI Mê Linh'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_TDN',
    		'name' => 'HCM Thảo Điền'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_Q9',
    		'name' => 'HCM Quận 9'
    	]);
    	Costcenter::create([
    		'code' => 'DNI_BH',
    		'name' => 'DNI Biên Hòa'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_SVH',
    		'name' => 'HCM Sư Vạn Hạnh'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_TDC',
    		'name' => 'HCM Thủ Đức'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_PVT',
    		'name' => 'HCM Phan Văn Trị'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_PT',
    		'name' => 'HCM Phú Thọ'
    	]);
    	Costcenter::create([
    		'code' => 'HCM_PQG',
    		'name' => 'HCM Phổ Quang'
    	]);
    	Costcenter::create([
    		'code' => 'LAN_LA',
    		'name' => 'LAN Vincom Long An'
    	]);
    	Costcenter::create([
    		'code' => 'CTO_XK_',
    		'name' => 'CTO Vincom Xuân Khánh'
    	]);
    	Costcenter::create([
    		'code' => 'AGG_AG',
    		'name' => 'AGG Vincom An Giang'
    	]);
    	Costcenter::create([
    		'code' => 'VLG_VL',
    		'name' => 'VLG Vincom Vĩnh Long'
    	]);
    	Costcenter::create([
    		'code' => 'KGG_RG',
    		'name' => 'KGG Vincom Rạch Giá'
    	]);
    	Costcenter::create([
    		'code' => 'CAU_CM',
    		'name' => 'CAU Vincom Cà Mau'
    	]);
    	Costcenter::create([
    		'code' => 'DNG_DN',
    		'name' => 'DNG Vincom Đà Nẵng'
    	]);
    	Costcenter::create([
    		'code' => 'KHA_NT',
    		'name' => 'KHA Nha Trang'
    	]);
    	Costcenter::create([
    		'code' => 'HUE',
    		'name' => 'HUE Vincom Huế'
    	]);
    	Costcenter::create([
    		'code' => 'HPG_HP',
    		'name' => 'HPG Vincom Hải Phòng'
    	]);
    	Costcenter::create([
    		'code' => 'THA_TH',
    		'name' => 'THA Vincom Thanh Hóa'
    	]);
    	Costcenter::create([
    		'code' => 'TBH_TB',
    		'name' => 'TBH Vincom Thái Bình'
    	]);
    	Costcenter::create([
    		'code' => 'HNM_PL',
    		'name' => 'HNM Phủ Lý'
    	]);
    	Costcenter::create([
    		'code' => 'QNH_HL',
    		'name' => 'QNH Vincom Hạ Long'
    	]);
    	Costcenter::create([
    		'code' => 'HTH_HT',
    		'name' => 'HTH Vincom Hà Tĩnh'
    	]);
    }
}
