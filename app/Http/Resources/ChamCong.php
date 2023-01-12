<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class ChamCong extends JsonResource
{
    public function toArray($request)
    {
    	if($this->type == 1) {
			$type =  "Văn Phòng Miền Bắc";
		}
    	if($this->type == 2) {
			$type =  "Văn Phòng Miền Nam";
		}
    	if($this->type == 3) {
			$type =  "Nhà Máy";
		}
    	if($this->type == 4) {
			$type =  "Showroom";
    	}
        if ($this->domain == 1) {
            $domain = 'http://noithatzip.com.vn/00khtn/chamcong/';
        }else{
            $domain = '/uploads/chamcong/';
        }
        return [
            'name' => $this->name,
            'ma_nv' => (int)$this->ma_nv,
            'id' => $this->id,
            'info' => $this->info,
            'img' => $this->img,
            'img_out' => $this->img_out,
            'domain' => $domain,
			'in' => $this->in,
			'out'   => $this->out,
			'date'  => $this->date,
			'type' => $type,
			'user_login' => \Auth::user()->id,
        ];
    }
}
