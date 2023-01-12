<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Resource;
use App\User;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ResourcesController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('resources.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function import(Request $request) 
    {
        if($request->hasFile('file_excell'))
        {
            $path = $request->file('file_excell')->getRealPath();

            $data = Excel::load($path, function($reader) {
                //$reader->dd();
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $row) {

                    $user = User::where('username','=',$row['username'])->first();
                    if ($user) {
                        $user->name = $row['cashier_name'];
                        $user->password = Hash::make($row['password']);
                        $user->save();
                    } else {
                       User::updateOrCreate([
                        'name' => $row['cashier_name'],
                        'email' => $row['username'] . '@noithatzip.com',
                        'password' => Hash::make($row['password']),
                        'username' => $row['username']
                    ]);
                    }
                }
            }
        }
        
        return redirect('/')->with('success', 'All good!');
    }
}
