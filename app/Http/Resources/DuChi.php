<?php

namespace App\Http\Resources;

use NoiThatZip\Costcenter\Models\Costcenter;
use Illuminate\Http\Resources\Json\JsonResource;
use App\DongChi;
use App\DaChi;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\User;

class DuChi extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_dx' => User::find($this->user_id)->name,
            'pos' => $this->type_bophan,
            'start_chi' => $this->start_chi,
            'end_chi' => $this->end_chi,
            'note' => $this->note,
            'amount' => (int) $this->amount,
            'date_dx' => $this->date_dx,
            'bophan' => $this->bophan,
            'duyet' => $this->duyet,
            'tao' => $this->user_id,
            'login' => \Auth::user()->id,
            'tai_lieu'   => $this->tai_lieu,
            'attachs'     =>  AttachmentResource::collection($this->attachs),
            'costcenters'  => Costcenter::where('name',$this->bophan)->get(),
            'dachi' => round(DaChi::where('id_duchi',$this->id)->sum('money')),
        ];
    }
}
