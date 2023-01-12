<?php

namespace NoiThatZip\ActivityType\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\ActivityType\Models\ActivityType;

class ActivityTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ActivityType::latest()->paginate(25);
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
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:activity_types'],
            'description' => 'required|string|min:10'
        ]);
        return ActivityType::Create($request->all());
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
        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name,' . $id,
            'description' => 'required|string|min:10'
        ]);
        $activityType = ActivityType::find($id);
        $activityType->update($request->all());
        return $activityType;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activityType = ActivityType::find($id);
        $activityType->delete();
        return ['message' => 'Activity Type Deleted'];
    }

    public function activitiesList()
    {
        return ActivityType::all()->pluck('name');
    }
}
