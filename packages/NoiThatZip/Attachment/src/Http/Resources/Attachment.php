<?php

namespace NoiThatZip\Attachment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use Illuminate\Http\Request;

class Attachment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ex = explode('/', $this->src);
            $check = 0; 
        if ($_SERVER["REMOTE_ADDR"] == '14.232.214.90') {
            $check = 1;
        }
        return [
            'idfolder' => (int)$ex[2],
            'id' => $this->id,
            'ip' => $_SERVER["REMOTE_ADDR"],
            'src' => $this->src,
            'check' => $check,
            'created_at' => (string)$this->created_at,
            'user_name' => User::find($this->creator_id)->name
        ];
    }
}
