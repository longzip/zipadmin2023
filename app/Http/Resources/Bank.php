<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;


class Bank extends JsonResource
{
    protected  $table =  "Bank";
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'journal_code'=> $this->journal_code,
            'journal_type'=> $this->journal_type,
            'abbreviation'=> $this->abbreviation,
            'bankdescription'=> $this->description,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,

        ];
    }

}
