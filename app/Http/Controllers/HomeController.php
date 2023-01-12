<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Auth;
use App\Resource;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     // return Auth::user()->resource;
    //     // Resource::UpdateOrCreate([
    //     //     'code' => 'ADMIN_MB',
    //     //     'warehouse' => 'A_MB',
    //     //     'costcenter' => 'KD',
    //     //     'user_id' => Auth::user()->id
    //     // ]);
    //     // Spatie\Permission\Models\Permission::create(['name' => 'import']);
    //     // $role = Spatie\Permission\Models\Role::create(['name' => 'import']);
    //     // $role->givePermissionTo('import');
    //     //Auth::user()->assignRole('import');
    //     //Auth::user()->removeRole('import');
    //     return view('home');
    // }

    public function index()
    {
        if(Auth::user()->status == 0) {
            return view('Reset', ['id' => Auth::user()->id]);
        }
        if(Auth::user()->status == 1) {
            return view('home');
        }
    }
}
