<?php

namespace NoiThatZip\Task\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\Task\Models\Task;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Task::all();
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
        $task = Task::findOrFail($id);
        return new TaskResource($task);
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
        $task = Task::findOrFail($id);
        $task->delete();
        return ['message' => 'Da xoa nhiem vu thanh cong!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function completed($id)
    {
        $task = Task::find($id);
        $task['completed'] = true;
        $task->save();
        return $task;
    }

    public function CommentStore(Request $request, $id)
    {
        $task = Task::find($id);
        $user = auth('api')->user();
        $comment = $task->comment([
            'title' => $user->name,
            'body' => $request['body'],
        ], $user);
        if($task->tasklog_type == 'App\Project') {
            activity($task->tasklog_id)
            ->performedOn($comment)
            ->causedBy($user)
            ->withProperties(['zip' => 'ttkhachduan','id' => $task->tasklog_id])
            ->log('Trả Lời Trong Kế Hoạch :subject.body, bởi :causer.name');
        }
        return $comment;
    }

    public function comments($id)
    {
        $task = Task::find($id);
        return $task->comments;
    }
}
