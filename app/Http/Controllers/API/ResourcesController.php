<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resource;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ResourcesController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Resource::latest()->paginate(25);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists()
    {
        return Resource::all()->pluck("id", "name");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Resource::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function profile()
    {
        $user = auth('api')->user();

        $resource = Resource::updateOrCreate([
            'user_id' => $user->id,
            'last_name' => $user->name,
            'email' => $user->email
        ]);

        $resource->user_id = $user->id;
        $resource->save();
        return $resource;
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $resource = Resource::updateOrCreate([
            'user_id' => $user->id
        ]);
        $resource->user_id = $user->id;

        $this->validate($request,[
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        if(!empty($request->last_name)){
            $user->name = $request['last_name'];
            $user->save();
        }

        if(!empty($request->password)){
            $user->password = Hash::make($request['password']);
            $user->save();
        }
        $resource->update($request->all());
        //Auth::user()->assignRole('sales');
        return ['message' => "Success"];
    }

    public function findUserbyCostcenter(Request $request){

        $resources = Resource::select('user_id as id', 'last_name as name')->whereIn('costcenter', $request['costcenters'])->get();
        return $resources;
    }
}
