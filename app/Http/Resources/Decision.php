<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\GroupQuyDinh;
use App\CheTai;
use App\QuyTrinh;
use App\DecisionPunishment;
// use App\Decision;

class Decision extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // dd($request->all());
        $nameRole = \DB::table('roles')->whereIn('id',json_decode($this->role_id))->pluck('name');
        if($this->giamsat_id != null) {
          $giamSat = \DB::table('roles')->whereIn('id',json_decode($this->giamsat_id))->pluck('name');
        }
        
        // dd($this->giamSat);
        $quytrinh = QuyTrinh::where('id',$this->quytrinh_id)->first();
      
        // $code = GroupQuyDinh::where('id',$this->group_id)->first();
      
        $decision = Decision::where('id',$this->maqdgs)->first();
        // $chetai = CheTai::where('id',$this->chetai_id)->first();
        
        $dp = DecisionPunishment::where('id', $this->id)->first();
        $chetai1 = CheTai::where('id', $dp['chetai_id1'])->first();
        // dd($dp['chetai_id2']);
        $chetai2 = CheTai::where('id', $dp['chetai_id2'])->first();
        $chetai3 = CheTai::where('id', $dp['chetai_id3'])->first();
        $chetai4 = CheTai::where('id', $dp['chetai_id4'])->first();
        $chetai5 = CheTai::where('id', $dp['chetai_id5'])->first();

        return [
            'id' => $this->id,
            'detail' => $this->detail,
            'sapo' => $this->sapo,
            'date' => (string)$this->date,
            'monitoring' => $this->monitoring,
            'roles' => $nameRole,
            'stt' => $this->id_ma,
            // 'chetai' => $chetai,
            'quytrinh' => $quytrinh,
          //  'rolese' =>  $giamSat,
            'name' => $quytrinh['name'] ,
            'giamsat' => empty($giamSat) ? '' : $giamSat,
             // 'code' =>$quytrinh['code'] ,
             // 'group' => $code,
             // 'name_chetai'  => $chetai['name'],
              'id_ma'  => $this->maqdgs, 
              'masapo'  =>empty($decision) ? '' :$decision['sapo'],
              'madetail'  =>empty($decision) ? '' :$decision['detail'],
              // 'masolan'  =>$decision['solan'],

              'chetai1_data' => empty($chetai1) ? '' : $chetai1['name'],
              'chetai2_data' => empty($chetai2) ? '' : $chetai2['name'],
              'chetai3_data' => empty($chetai3) ? '' : $chetai3['name'],
              'chetai4_data' => empty($chetai4) ? '' : $chetai4['name'],
              'chetai5_data' => empty($chetai5) ? '' : $chetai5['name'],

              'chetai1' => empty($chetai1) ? '' : $chetai1,
              'chetai2' => empty($chetai2) ? '' : $chetai2,
              'chetai3' => empty($chetai3) ? '' : $chetai3,
              'chetai4' => empty($chetai4) ? '' : $chetai4,
              'chetai5' => empty($chetai5) ? '' : $chetai5,
        ];
    }

}
