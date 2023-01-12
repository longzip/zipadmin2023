<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Decision;
use App\User;
use App\Roles;
use App\QuyTrinh;
use App\DecisionPunishment;
use App\CheTai;

class ViPham extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $decision = Decision::where('id',$this->resource['decision_id'])->first();
        $user = User::where('id',$this->resource['user_id'])->first();
        $quytrinh = QuyTrinh::where('id',$decision->quytrinh_id)->first();
        $creator = User::where('id', $this->resource['creator_id'])->first();
        // $role = Roles::where('id', $this->resource['role_id'])->first();
        $role = \DB::table('roles')->where('id',$this->resource['role_id'])->first();
        // dd($quytrinh);
        if($quytrinh == null) {
            $quytrinh = new QuyTrinh();
        }
        $dp = DecisionPunishment::where('id', $this->resource['decision_id'])->first();
        $chetai1 = CheTai::where('id', $dp['chetai_id1'])->first();
        $chetai2 = CheTai::where('id', $dp['chetai_id2'])->first();
        $chetai3 = CheTai::where('id', $dp['chetai_id3'])->first();
        $chetai4 = CheTai::where('id', $dp['chetai_id4'])->first();
        $chetai5 = CheTai::where('id', $dp['chetai_id5'])->first();

        $chetai = new CheTai; 
        switch ($this->resource['so_lan']) {
            case 1:
                $chetai = $chetai1;
                break;
            case 2:
                $chetai = $chetai2;
                break;
            case 3:
                $chetai = $chetai3;
                break;
            case 4:
                $chetai = $chetai4;
                break;
            case 5:
                $chetai = $chetai5;
                break;   
        }
        if($user == null) {
            $user = new User();
        }
        $jsonapprover = json_decode($this->resource['approver']);
        $jsonbophan = json_decode($this->resource['role_id']);
        $approvers = [];
        $bophan= [];

        if($jsonapprover != null) {
            for($i = 0; $i < sizeof($jsonapprover); ++$i) {
                array_push($approvers, User::where('id',$jsonapprover[$i])->first());
            }
        }
        if($jsonbophan != null) {
            for($i = 0; $i < sizeof($jsonbophan); ++$i) {
                array_push($bophan, Roles::where('id',$jsonbophan[$i])->first());
            }
        }
        // dd($this);
        $images = json_decode($this->resource['file']);
        $videos = json_decode($this->resource['video']);
        if($this->resource['danh_gia'] == null) {
            $status = "Chờ xác minh";
        } else {
            $status = "Đã xác minh";
        }

        if($this->resource['danh_gia'] == null) {
            $this->resource['danh_gia'] = "Chưa đánh giá";
        } 

        $time = strtotime($this->resource['ngay_vi_pham']);

        $dateformated = date('d/m/Y',$time);
        return [
            'id' => $this->resource['id'],
            'decision' => $decision,
            'decision_sapo' => $decision->sapo,
            'solan' => $this->resource['so_lan'],
            'user' => $user,
            'role' => $role,
            'roleid'=>$bophan,
            'nvpheduyet' => $approvers,
            'chetai' => $this->resource['che_tai'],
            'quytrinh' => $quytrinh,
            'user_name' => $user->name,
            'creator' => $creator['name'],
            'tenvipham' => $this->resource['ten_vi_pham'],
            'tienphat' => $this->resource['money'],
            'danhgia' => $this->resource['danh_gia'],
            'ngayvipham' => $this->resource['ngay_vi_pham'],
            'timeapply' => $this->resource['time_apply'],
            'bienban' => $this->resource['bien_ban'],
            'pheduyet' => $this->resource['phe_duyet'],
            'emailed' => $this->resource['emailed'],
            'tuongtrinh' => $this->resource['tuong_trinh'],
            'isapprove' => $this->resource['isapprove'],
            'readonly' => $this->resource['readonly'],
            'status' => $status,
            'images' => $images,
            'videos' => $videos,
            'dateformated' => $dateformated,
            'created_at' => $this->resource['created_at'],
            'updated_at' => $this->resource['updated_at'],

            'chetai1_data' => isset($chetai1) ? $chetai1['name'] : '',
            'chetai2_data' => isset($chetai2) ? $chetai2['name'] : '',
            'chetai3_data' => isset($chetai3) ? $chetai3['name'] : '',
            'chetai4_data' => isset($chetai4) ? $chetai4['name'] : '',
            'chetai5_data' => isset($chetai5) ? $chetai5['name'] : '',

            'p' => $this->resource['p'],
            'stt' => $this->resource['stt'],
            'year' => $this->resource['year'],
        ];
    }

}
