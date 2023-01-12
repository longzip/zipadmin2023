<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('auth:api');
		
	}

    public function index(){
    	$user = auth('api')->user();
    	if ($user->can('import')) {
    		return Activity::latest()->paginate(25);
    	}
    	else{
    		return $user->actions()->latest()->paginate(25);
    	}
    	
    }
}
