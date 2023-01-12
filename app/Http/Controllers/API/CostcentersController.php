<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Costcenter\Models\Costcenter;
use Illuminate\Support\Facades\DB;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use App\Http\Resources\Lead as LeadResource;
use App\Http\Resources\Contact as ContactResource;

class CostcentersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Costcenter::latest()->paginate(25);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function costcentersList()
    {
        //
        return Costcenter::select('id','name')->get();
    }

    public function costcentersmergeList()
    {
        $array1 = [['id'=>100,'name'=>'Văn Phòng Miền Nam'],['id'=>101,'name'=>'Văn Phòng Miền Bắc'],['id'=>102,'name'=>'Nhà Máy']];
        $array2 = Costcenter::select('id','name')->get();
        foreach ($array1 as $key => $value) {
            $a = $array2->push($value);
            // dd($value);
        }
        return $a;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'code' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'warehouse' => 'required'
        ]);

        return Costcenter::create([
            'code' => strtoupper($request['code']),
            'name' => $request['name'],
            'warehouse' => $request['warehouse']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Costcenter $costcenter)
    {
        return $costcenter;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Costcenter $costcenter)
    {
        $this->validate($request,[
            'code' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'warehouse' => 'required'
        ]);

        $costcenter->code = $request['code'];
        $costcenter->name = $request['name'];
        $costcenter->warehouse = $request['warehouse'];
        $costcenter->save();
        return $costcenter;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return DB::table('costcenters')->select('id as key','name as label')->get()->toArray();
    }
    public function getSelect()
    {
        return DB::table('costcenters')->select('id as code','name')->get();
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contacts(Costcenter $costcenter)
    {
        $contacts = $costcenter->entries(Contact::class)->get();
        return ContactResource::collection($contacts);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leadsList($id)
    {
        $leads = Category::find($id)->entries(Lead::class)->get();
        return LeadResource::collection($leads);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function searchContacts($id)
    {
        $user = auth('api')->user();

        if ($user->can('import')) {
            $costcenter = Costcenter::find($id);
            $contacts = $costcenter->entries(Contact::class);
            
        }
        elseif ($user->can('asm')) {
            
        }
        elseif($user->can('sale')){
            $costcenter = Costcenter::find($user->resource->costcenter);
            $contacts = $costcenter->entries(Contact::class);
        }

        return ContactResource::collection($contacts->paginate(25));
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function searchLeads($id)
    {
        $user = auth('api')->user();

        if ($user->can('import')) {
            $costcenter = Costcenter::find($id);
            $leads = $costcenter->entries(Lead::class)->paginate(25);
        }
        elseif ($user->can('asm')) {

        }
        elseif($user->can('sale')){
            $costcenter = Costcenter::find($user->resource->costcenter);
            $leads = $costcenter->entries(Lead::class)->paginate(25);
        }

        return LeadResource::collection($leads);
    }

    public function dinhbien(Request $request){
        $costcenter = Costcenter::where('closed','>',$request->dates[0])->get()->toArray();
        return $costcenter;
    }

    public function adddinhbien(Request $request){
        $new = Costcenter::find($request->id);
        $new->sm = $request->sm;
        $new->sales = $request->sales;
        $new->save();
    }
}
