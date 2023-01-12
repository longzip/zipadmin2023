<?php 

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\TrungGian;
use NoiThatZip\Costcenter\Models\Costcenter;

class TuyenDung extends JsonResource
{
	public function toArray($request){
		$role = \DB::table('roles')->where('name',$this->vitri)->first();
		$costcenters = Costcenter::where('id',$this->showroom)->first();
            $ctv = TrungGian::where('id_trung',$this->id)->where('type',0)->pluck('id_user');
		return [
            'id' => $this->id,
            'soluong' => $this->soluong,
            'tenbophan' => $this->vitri,
            'roles' => $role,
            'costcenters' => $costcenters,
            'start' => $this->start,
            'end' => $this->end,
            'login' => \Auth::user()->id,
            'create' => $this->user_id,
            'nguoitao' => User::find($this->user_id)->name,
            'ctv' => count($ctv) > 0 ? User::whereIn('id',$ctv)->get() : '',
            'showroom' => !empty($costcenters) ? $costcenters->name : '',
            'chuy'=>$this->note,
        ];
	}
}