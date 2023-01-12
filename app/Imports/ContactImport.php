<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use NoiThatZip\Lead\Models\Lead;
use App\ModelCostcenters;
class ContactImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $array)
    {
        // return new Lead([
        //     'last_name'     => $collection[0],
        //     'phone'    => $collection[1],
        //     'address' => $collection[2],
        //     'user_id' => $collection[3],
        //     'start_date' => '2019-04-22',
        //     'start_time' => '08:00:00',
        //     'end_time' => '08:00:00',
        //     'note' => 'khách đẩy từ file excel',
        // ]);
        // dd($array);
        foreach ($array as $key => $collection) {
	        $lead = new Lead;
	        $lead->last_name = $collection[0];
	        $lead->phone = $collection[1];
	        $lead->address = $collection[2];
	        $lead->sanpham = $collection[3];
	        $lead->user_id = $collection[4];
	        $lead->thread_id = 1;
	        $lead->start_date = '2019-02-25';
	        $lead->start_time = '08:00:00';
	        $lead->end_time = '08:00:00';
	        $lead->note = 'khách đẩy từ file excel';
	        $lead->save();
	        $cost = new ModelCostcenters;
	        $cost->costcenter_id = 2;
	        $cost->model_type = 'NoiThatZip\Lead\Models\Lead';
	        $cost->model_id = $lead->id;
	        $cost->save();
	    }
    }
}
