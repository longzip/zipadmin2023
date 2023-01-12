<?php  
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

	class Nghiphep extends JsonResource
	{
		public function toArray($request){
		$user = User::where('id',$this->resource['ten_nhan_vien'])->first();
		$role = \DB::table('roles')->where('id',$this->resource['vi_tri'])->first();
		$nv = User::where('id',$this->resource['nv_phe_duyet'])->first();
		$bg = User::where('id',$this->resource['nguoi_nhan_cv'])->first();
		$bua = \Auth::user()->id;
		$id = \DB::table('model_has_roles')->where('model_id',$bua)->where('role_id',7)->get();
		$gd = \DB::table('model_has_roles')->where('model_id',$bua)->where('role_id',8)->get();
		$tp = \DB::table('model_has_roles')->where('model_id',$user)->where('role_id',27)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        $giamdoc = ($gd->isEmpty()) ? 0 : 1;
        $truongphong = ($tp->isEmpty()) ? 0 : 1;
			return [
	            'id' => $this->id,
	            'user' => $user,
	            'role' => $role,
	            'lydo' => $this->resource['ly_do'],
	            'songaynghi' => $this->resource['so_ngay_nghi'],
	            'cvbangiao' => $this->resource['cv_ban_giao'],
	            'nvbangiao' => $bg ,
	            'phone' => $this->resource['phone'],
	            'date' => $this->resource['ngay_gui_don'],
	            'dates' => $this->resource['end_date'],
	            'loainghi' =>$this->resource['loai_nghi'],
	            'nvpheduyet' =>$nv,
	            'nhanvien' =>$bua,
	            'comments'   =>  $this->comments,
	            'tinh_trang'   =>  $this->tinh_trang,
	            'bienban'  =>$this->bien_ban,
	            'nhansu' =>$this->resource['nhan_su'],
	            'check' =>$check,
	            'giamdoc'=>$giamdoc,
	            'truongphong'=>$truongphong,
	        ];
		}
	}