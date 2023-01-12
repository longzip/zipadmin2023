<?php

namespace NoiThatZip\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\Warehouse\Http\Resources\Warehouse as WarehouseResource;
use NoiThatZip\Warehouse\Models\Warehouse;

class WarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $warehouses = Warehouse::all();
        return WarehouseResource::collection($warehouses);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function citiesList()
    {
        //
        return Warehouse::select('id','city')->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function citiesAll()
    {
        //
        return Warehouse::all()->pluck('city');
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
        //return $request->all();
        $request->validate([
            'city' => ['required', 'string', 'max:255', 'unique:warehouses'],
            'code' => ['required', 'string', 'max:255'],
            'version' => ['required', 'numeric']
        ]);

        $warehouse = Warehouse::create($request->all());
        return $warehouse;
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
        $warehouse = Warehouse::find($id);
        return new WarehouseResource($warehouse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        //
        return new WarehouseResource($warehouse);
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
        $request->validate([
            'city' => 'required|string|max:255|unique:warehouses,city,'.$id,
            'code' => ['required', 'string', 'max:255'],
            'version' => ['required', 'numeric']
        ]);
        $warehouse = Warehouse::find($id);
        $warehouse->update($request->all());
        return new WarehouseResource($warehouse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        //
        return $warehouse->delete();
    }
}
