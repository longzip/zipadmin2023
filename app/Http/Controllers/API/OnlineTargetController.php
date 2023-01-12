<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ChienDich;
use App\ChienDichTarget;
use App\Http\Resources\ChienDich as ChienDichResource;

class OnlineTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chiendich = ChienDich::filter($request->all())->latest()->paginateFilter();
        return ChienDichResource::collection($chiendich);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chiendich = new ChienDich;
        $chiendich->name = $request->form['name'];
        $chiendich->start = $request->form['start'];
        $chiendich->end = $request->form['end'];
        $chiendich->chiphi = $request->form['chiphi'];
        $chiendich->mess = $request->form['mess'];
        $chiendich->phone = $request->form['phone'];
        $chiendich->khtn = $request->form['khtn'];
        $chiendich->doanhthu = $request->form['doanhthu'];
        $chiendich->save();

        foreach ($request->form['products'] as $key => $value) {
            $target = new ChienDichTarget;
            $target->id_chiendich = ChienDich::select('id')->orderBy('id','desc')->first()['id'];
            $target->product = $value['groups'];
            $target->save();
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($id);
        // dd($request->all());
        $chiendich = ChienDich::find($id);
        $chiendich->name = $request->form['name'];
        $chiendich->start = $request->form['start'];
        $chiendich->end = $request->form['end'];
        $chiendich->chiphi = $request->form['chiphi'];
        $chiendich->mess = $request->form['mess'];
        $chiendich->phone = $request->form['phone'];
        $chiendich->khtn = $request->form['khtn'];
        $chiendich->doanhthu = $request->form['doanhthu'];
        $chiendich->save();

        ChienDichTarget::where('id_chiendich',$id)->delete();
        foreach ($request->form['products'] as $key => $value) {
            $target = new ChienDichTarget;
            $target->id_chiendich = $id;
            $target->product = $value['groups'];
            $target->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ChienDich::where('id',$id)->delete();
        ChienDichTarget::where('id_chiendich',$id)->delete();
        //
    }

    public function target()
    {
        return ChienDich::all();
    }

    public function allproduct(Request $request)
    {
        $target = ChienDichTarget::where('id_chiendich',$request->id)->get();
        $new = array();
        foreach ($target as $key => $value) {
            $new[$key]['mess'] = 0; 
            $new[$key]['phone'] = 0; 
            $new[$key]['khtn'] = 0; 
            $new[$key]['product'] = $value['product']; 
        }
        return $new;
    }

    public function productsList()
    {
        $collection = Product::all();
        $unique = $collection->unique('groups');
        // $slimmed_down = $unique->map(function ($item, $key) {
        //     return [
        //         'name' => $item->name_groups,
        //         'id' => $item->groups,
        //     ];
        // });

        return collect($unique->values()->all());
    }
}
