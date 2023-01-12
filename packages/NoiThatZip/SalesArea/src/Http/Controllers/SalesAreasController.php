<?php

namespace NoiThatZip\SalesArea\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\SalesArea\Models\SalesArea;
use NoiThatZip\SalesArea\Http\Resources\SalesArea as SalesAreaResource;

class SalesAreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $salesArea = SalesArea::filter($request->all())->latest()->paginateFilter();
        return SalesAreaResource::collection($salesArea);
    }

    public function salesAreasList(){
        return SalesArea::all();
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
            'name' => ['required', 'string', 'max:255', 'unique:sales_areas'],
            'city' => ['required']
        ]);

        return SalesArea::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salesArea = SalesArea::findOrFail($id);
        return new SalesAreaResource($salesArea);
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
        $request->validate([
            'name' => 'required|string|max:255|unique:sales_areas,name,'.$id,
            'city' => ['required']
        ]);
        $salesArea = SalesArea::find($id);
        $salesArea->update($request->all());
        return new SalesAreaResource($salesArea);
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
        // $salesArea = SalesArea::find($id);
        // return $salesArea->delete();
    }
}
