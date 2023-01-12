<?php

namespace NoiThatZip\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "task" => $this->task,
            "priority" => $this->priority,
            "duedate" => (string)$this->duedate,
            "completed" => (integer)$this->completed,
            "tasklog_type" => $this->tasklog_type,
            "tasklog_id" => $this->tasklog_id,
            "creator_type" => $this->creator_type,
            "creator_id" => $this->creator_id,
            "comments"   => $this->comments,
            "user_name"   => User::find($this->creator_id)->name,
            "created_at" => (string)$this->created_at
        ];
    }
}
