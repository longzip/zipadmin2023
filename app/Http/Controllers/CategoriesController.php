<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BrianFaust\Categories\Models\Category;
use NoiThatZip\Lead\Models\Lead;
use NoiThatZip\Contact\Models\Contact;

class CategoriesController extends Controller
{
    public function setCategory($id){
    	request()->session()->put('category_id', $id);
    }
    public function getCategory(){
    	$id = request()->session()->get('category_id');
    	return Category::find($id);
    }

    public function movedata()
    {
        $query = Lead::All();
        foreach ($query as $key => $value) {
            $update = Lead::find($value->id);
            if($value->salesarea_id != null){
	            $update->salesarea_id = json_encode(array((integer) $value->salesarea_id));
            }
            $update->save();
        }
    }

    public function movedatakhtn()
    {
        $query = Contact::All();
        foreach ($query as $key => $value) {
            $update = Contact::find($value->id);
            if($value->salesarea_id != null){
	            $update->salesarea_id = json_encode(array((integer) $value->salesarea_id));
            }
            $update->save();
        }
    }
}
