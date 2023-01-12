<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Paginator as Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\DecisionPunishment;
use App\CheTai;
use App\User;
use App\NghiPhep;
use App\Role;
use App\ThongBao;
use App\Http\Resources\NghiPhep as NghiphepResource;
use DB;
use DateTime;
use Mail;
use Response;
		/**
		 *
		 */
class NghiPhepController extends Controller
{
	function __construct()
	{
		$this->middleware('auth:api');
	}

	public function index(Request $request){

		$nghipheps = NghiPhep::filter($request->all())->latest()->paginateFilter();
        $user = auth('api')->user();
        $id = \DB::table('model_has_roles')->where('model_id',$user->id)->whereIn('role_id',[7,8])->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        $nghipheps = NghiPhep::where(function($query) use ($check,$user) {
                        if($check != 1){

                            $query->where('ten_nhan_vien',$user->id)->orwhere('nv_phe_duyet',$user->id)->orwhere('nguoi_nhan_cv',$user->id);
                        }
                    })->filter($request->all())->latest()->paginateFilter();
        // $nguoiduyet = NghiPhep:orwhere('ten_nhan_vien',$user->id)->filter($request->all())->latest()->paginateFilter();



		return NghiphepResource::collection($nghipheps);
	}
	public function store(Request $req){
		$user = auth('api')->user();
		 $this->validate($req,[
            'songaynghi' => 'required',
            'lydo' => 'required',
            'cvbangiao' => 'required',
            'phone' => 'required',
            'date' => 'required',
            'loainghi' => 'required',
            'role' => 'required',
        ],
        [
        	'role.required' => 'bạn chưa chọn vị trí',
            'songaynghi.required' => 'Bạn chưa nhập số ngày nghỉ',
            'lydo.required' => 'Bạn chưa nhập lý do nghỉ',
            'cvbangiao.required' => 'Bạn chưa nhập công việc bàn giao',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'date.required' => 'Bạn chưa chọn ngày bắt đầu',
            'loainghi.required' => 'Bạn chưa chọn loại nghỉ',
        ]);
		$nghiphep = new Nghiphep;
		$nghiphep->ten_nhan_vien = $req->user['id'];
		$nghiphep->vi_tri = $req->role['id'];
		$nghiphep->nv_phe_duyet = empty($req->nvpheduyet) ? null : $req->nvpheduyet['id'];
		$nghiphep->so_ngay_nghi = $req['songaynghi'];
		$nghiphep->ly_do = $req['lydo'];
		$nghiphep->cv_ban_giao = $req['cvbangiao'];
        $nghiphep->nguoi_nhan_cv =  empty($req->nvbangiao) ? null : $req->nvbangiao['id'];
		$nghiphep->phone = $req['phone'];
		$nghiphep->ngay_gui_don = $req['date'];
		$nghiphep->end_date = empty($req['dates']) ? null : $req['dates'];
		$nghiphep->loai_nghi = $req['loainghi'];
        $nghiphep->tinh_trang =  empty($req->nvbangiao) ? 2 : 0;
		$nghiphep->save();

        $checkns = \DB::table('model_has_roles')->where('role_id',7)->pluck('model_id')->toArray();
        foreach ($checkns as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $nghiphep->id;
            $user->type = 2;
            $user->noidung = $nghiphep->ly_do;
            $user->user_tao =$nghiphep->ten_nhan_vien;
            $user->user_see = empty($nghiphep->nv_phe_duyet) ? $value : $nghiphep->nv_phe_duyet;
            $user->action =1;
            $user->save();
        }
		activity()
        ->performedOn($nghiphep)
        ->causedBy($user)
        ->withProperties(['zip' => 'nghiphep','id' => $nghiphep->id])
        ->log('Đã tạo nghi phep  :subject.name, bởi :causer.name');
		 return $nghiphep;
	}
	public function show($id)
    {
        $nghiphep = Nghiphep::findOrFail($id);
        $nghiphepwithoutbienban = \DB::table('nghipheps')->whereNotNull('bien_ban')->get();
        return new NghiphepResource($nghiphep);
    }
     public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $nghiphep = Nghiphep::findOrFail($id);
		$nghiphep->ten_nhan_vien = $request->user['id'];
		$nghiphep->vi_tri = $request->role['id'];
        $nghiphep->nv_phe_duyet = empty($req->nvpheduyet) ? null : $req->nvpheduyet['id'];
		$nghiphep->so_ngay_nghi = $request['songaynghi'];
		$nghiphep->ly_do = $request['lydo'];
		$nghiphep->cv_ban_giao = $request['cvbangiao'];
        $nghiphep->nguoi_nhan_cv =  empty($req->nvbangiao) ? null : $req->nvbangiao['id'];
		$nghiphep->phone = $request['phone'];
        $nghiphep->tinh_trang =  empty($req->nvbangiao) ? 2 : 0;
		$nghiphep->ngay_gui_don = $request['date'];
		$nghiphep->end_date = empty($request['dates']) ? null : $request['dates'];
        $nghiphep->save();
        $checkns = \DB::table('model_has_roles')->where('role_id',8)->pluck('model_id')->toArray();
        foreach ($checkns as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $nghiphep->id;
            $user->type = 2;
            $user->noidung = $nghiphep->ly_do;
            $user->user_tao =$nghiphep->ten_nhan_vien;
            $user->user_see =empty($nghiphep->nv_phe_duyet) ? $value : $nghiphep->nv_phe_duyet;
            $user->action = 2;
            $user->save();
        }
        activity()
        ->performedOn($nghiphep)
        ->causedBy($nghiphep)
        ->withProperties(['zip' => 'nghiphep','id' => $nghiphep->id])
        ->log('Đã sửa nghi phep :subject.name, bởi :causer.name');
        return $nghiphep;
    }
    public function destroy($id)
    {
        $user = auth('api')->user();
        $nghiphep = Nghiphep::findOrFail($id)->delete();
        return ['message' => 'Xóa Quyết Định Thành Công'];
    }
    public function pheduyet(){
    	$role_id = [1,3,4,6];
    	$qr = DB::table('model_has_roles')->whereIn('role_id',$role_id)->pluck('model_id')->toArray();
    	$loc = array_unique($qr);
    	$us = User::whereIn('id',$loc)->get();
        $role_id_see=[7,8];
    	return $us;
    }
    public function comment(Request $request, $id){
    	// dd($request['body']);
	    $user = auth('api')->user();
	    $contact = Nghiphep::findOrFail($id);
	    $comment = $contact->comment([
	        'title' => $user->name,
	        'body' => $request['body'],
	    ], $user);

        $nv = $contact->ten_nhan_vien;
        $nv_pd=$contact->nv_phe_duyet;
        if($contact->nv_phe_duyet==null){
        $b=[$nv];
        }
        else {
            $b=[$nv,$nv_pd];
        }
        $check = \DB::table('model_has_roles')->where('role_id',7)->pluck('model_id')->toArray();
        $see = array_merge($b, $check);
        foreach ($see as $value) {
            $thongbao = new ThongBao;
            $thongbao->id_chuyenmuc = $contact->id;
            $thongbao->type = 5;
            $thongbao->noidung = $contact->ly_do;
            $thongbao->user_tao =$contact->ten_nhan_vien;
            $thongbao->user_see =$value;
            $thongbao->action = 1;
            $thongbao->save();
        }
	    return $comment;
	}
	public function comments($id){
	    $contact = Nghiphep::findOrFail($id);
	    return $contact->comments;
	}
    // public function nhansuduyet(Request $req,$id){
    //     // $role = 7;
    //     // $qr = DB::table('model_has_roles')->whereIn('role_id',$role)->pluck('model_id');
    //     // $ns = User::whereIn('id',$qr)->get();
    //     $nghiphepToUpdate = Nghiphep::findOrFail($id);
    //     $nghiphepToUpdate->tinh_trang = 1;
    //     $nghiphepToUpdate->save();
    //     // return redirect('/nghi-phep');
    //     $check = \DB::table('model_has_roles')->where('role_id',8)->pluck('model_id')->toArray();
    //     foreach ($check as $value)
    //     {
    //         $user = new ThongBao;
    //         $user->id_chuyenmuc = $id;
    //         $user->type = 1;
    //         $user->noidung = $nghiphepToUpdate->ly_do;
    //         $user->user_tao =\Auth::User()->id;
    //         $user->user_see = $value;
    //         $user->action = 5;
    //         $user->save();
    //     }
    // }
    public function xacnhanbg(Request $req,$id){
        $nghiphepToUpdate = Nghiphep::findOrFail($id);
        $nghiphepToUpdate->tinh_trang = 4;
        $nghiphepToUpdate->save();
    }
    public function duyetgd(Request $req,$id){
        $nghiphepToUpdate = Nghiphep::findOrFail($id);
        $nghiphepToUpdate->tinh_trang = 5;
        $nghiphepToUpdate->save();
    }
    public function postduyets(Request $req,$id){
        $nghiphepToUpdate = Nghiphep::findOrFail($id);
        $nghiphepToUpdate->tinh_trang = 1;
        $nghiphepToUpdate->save();
        $nghiphep = \DB::table('nghipheps')->where('id',$id)->first();
        $user = \DB::table('users')->where('id', $nghiphep->ten_nhan_vien)->first();
        $creator = \DB::table('users')->where('id', $nghiphep->nv_phe_duyet)->first();
        $role = \DB::table('roles')->where('id',$nghiphep->vi_tri)->first();
        $data = array(
            // 'id' => 'id',
            'tennhanvien'  => $user->name,
            'nvpheduyet'  => $creator->name,
            'songaynghi' => $nghiphep->so_ngay_nghi,
            'dates' =>  $nghiphep->ngay_gui_don,
            'ngayketthuc' =>$nghiphep->end_date,
            'vitri' =>$role->name,
            'ly' =>$nghiphep->ly_do,
        );
        // dd($data);
        $b=$nghiphep->ten_nhan_vien;
        if($nghiphep->nguoi_nhan_cv == null ){
         $check =[9003,$b];
        }
        else {
        $a=$nghiphep->nguoi_nhan_cv;
         $check =[9003,$a,$b];
        }
        foreach ($check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 2;
            $user->noidung = $nghiphep->ly_do;
            $user->user_tao =$nghiphep->ten_nhan_vien;
            $user->user_see = $value;
            $user->action =5;
            $user->save();
        }
        // Mail::send('MailPhep',$data,function($mes) use ($nghiphep,$user,$creator,$role){
        //     $mes->to($user->email);
        //     $mes->cc('ngocthang.win.mc@gmail.com');
        //     $mes->subject('Duyệt Đơn nghỉ Phép');
        //     $mes->attach('uploads/nghiphep/'.$nghiphep->bien_ban);
        // });

        $thongbao = ThongBao::where('id_chuyenmuc',$id)->where('action',4)->delete();
        return redirect('/nghi-phep');
	}
	public function khongduyet($id){
		$nghiphepUpdate = Nghiphep::findOrFail($id);
		$nghiphepUpdate->tinh_trang = 3;
		$nghiphepUpdate->save();
        $thongbao = ThongBao::where('id_chuyenmuc',$id)->where('action',1)->delete();
		return redirect('/nghi-phep');
	}

}

?>