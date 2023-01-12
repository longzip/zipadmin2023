<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Decision;
use App\GroupQuyDinh;
use App\QuyTrinh;
use App\CheTai;
use App\DecisionPunishment;
use App\ThongBao;
use App\Http\Resources\Decision as DecisionResource;

class DecisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
 
    public function index(Request $request)
    {
        $decision = Decision::filter($request->all())->latest()->paginateFilter();
        // dd($decision);
        return DecisionResource::collection($decision);
    }
    public function store(Request $request)
    {
        $user = auth('api')->user();
        // $request->validate([
        //     'sapo' => 'required',
        //     'detail' => 'required',
        //     'chetai1' => 'required', 
        // ],
        // [
        //     'sapo.required'=>'Bạn chưa nhập tên quyết định',
        //     'detail.required'=>'Bạn chưa nhập nội dung',
        //     'chetai1.required' => 'Bạn chưa có chế tài',
        // ]);
        // dd($request->all());
        $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');
        $ma_id = Decision::where('quytrinh_id',$request['quytrinh']['id'])->orderBy('id', 'desc')->first();
        if($request['giamsat'] == null) {
            $giamsat = null;
            $giamsat_id = null;
            $merge = array_merge([8],$role_id->toArray());

        }else {
            $giamsat = \DB::table('roles')->whereIn('name',$request['giamsat'])->pluck('id');
            $merge = array_merge([8],$giamsat->toArray(),$role_id->toArray());
            $giamsat_id = json_encode($giamsat);
        }
        $id = ($ma_id == null) ? 1 : $ma_id->id_ma + 1;
        $decision = new Decision;
        $decision->sapo = $request->sapo;
        $decision->detail = $request->detail;
        $decision->quytrinh_id = $request['quytrinh']['id'];
    //    $decision->date = $request->date;
        $decision->role_id = json_encode($role_id->toArray());
        $decision->giamsat_id = $giamsat_id;
        $decision->id_ma = $id;
        $decision->monitoring = $request->monitoring;
        $decision->maqdgs = empty($request->maqdgs) ? null : $request->maqdgs['id'];
        $decision->save();
        // dd($merge);
        $dp = new DecisionPunishment;
        // dd($request->all());
        $dp->id = $decision->id;
        $dp->chetai_id1 = empty($request->chetai1) ? null : $request->chetai1['id'];
        $dp->chetai_id2 = empty($request->chetai2) ? null : $request->chetai2['id'];
        $dp->chetai_id3 = empty($request->chetai3) ? null : $request->chetai3['id'];
        $dp->chetai_id4 = empty($request->chetai4) ? null : $request->chetai4['id'];
        $dp->chetai_id5 = empty($request->chetai5) ? null : $request->chetai5['id'];
        $dp->save();

        $check = \DB::table('model_has_roles')->whereIn('role_id',$merge)->pluck('model_id')->toArray();
        $fi_check=array_unique($check);
        foreach ($fi_check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $decision->id;
            $user->type = 4;
            $user->noidung = $decision->sapo;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 1;
            $user->save();
        }
        activity()
        ->performedOn($decision)
        ->causedBy($user)
        ->withProperties(['zip' => 'quyetdinh','id' => $decision->id])
        ->log('Đã tạo Quyết Định :subject.name, bởi :causer.name');

        return $decision;
    }

    public function show(Decision $decision)
    {
      return new DecisionResource($decision);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();

        $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');
        if($request['giamsat'] == null){
            $giamsat = null;
            $giamsat_id = null;
            $merge = array_merge([8],$role_id->toArray());
        }else {
            $giamsat = \DB::table('roles')->whereIn('name',$request['giamsat'])->pluck('id');
            $merge = array_merge([8],$giamsat->toArray(),$role_id->toArray());
            $giamsat_id = json_encode($giamsat);
        }
        // dd($giamsat);
        $decision = Decision::findOrFail($id);
        $decision->sapo = $request->sapo;
       // $decision->link = $request->link;
        $decision->detail = $request->detail;
      //  $decision->status = (count($role_id) > 1) ? 1 : 0;
        $decision->role_id = json_encode($role_id->toArray());
        $decision->quytrinh_id = $request['quytrinh']['id'];
        $decision->giamsat_id = $giamsat;
        $decision->monitoring = $request->monitoring;
        $decision->maqdgs = $request->maqdgs['id'];
        $decision->save();

        $dp = DecisionPunishment::findOrFail($id);
        // dd($request->all());
        $dp->chetai_id1 = $request->chetai1['id'];
        $dp->chetai_id2 = $request->chetai2['id'];
        $dp->chetai_id3 = $request->chetai3['id'];
        $dp->chetai_id4 = $request->chetai4['id'];
        $dp->chetai_id5 = $request->chetai5['id'];
        $dp->save();
        $check = \DB::table('model_has_roles')->whereIn('role_id',$merge)->pluck('model_id')->toArray();
        $checkf = array_unique($check);
        foreach ($checkf     as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $decision->id;
            $user->type = 4;
            $user->noidung = $decision->sapo;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 2;
            $user->save();
        }
        return $decision;
    }

    public function destroy($id)
    {
        $user = Decision::where('id',$id)->delete();
    }
    
    public function loadChung(Request $request) {
        $decision = $this->loadQuyDinh($request->all());
        
        return DecisionResource::collection($decision);
    }

    protected function loadQuyDinh($request) {
        $user = auth('api')->user();
        $roles = \DB::table('model_has_roles')->where('model_id',$user->id)->pluck('role_id');
        $decision = Decision::pluck('role_id','id');
        if($decision->isEmpty()) {
            $merge = array_merge($request,['id' => array()]);
        } else {
            foreach ($decision as $key => $value) {
                $diff = array_intersect(json_decode($roles), json_decode($value));
                $chung[] = (!empty($diff)) ? $key : '';
            }
            $merge = array_merge($request,['id' => $chung]);
        }
        $decision = Decision::filter($merge)->latest()->paginateFilter();
        return $decision;
    }

    public function QuyDinhList(){
        return Decision::all();
    }

    public function getGroup(){
        return GroupQuyDinh::all();
    }

    public function getCheTai()
    {
        return CheTai::all();
    }

    public function getQuyTrinh()
    {
        return QuyTrinh::all();
    }
    
    public function getmaquydinh()
    {
        $des = Decision::whereNull('giamsat_id')->get();
       //  $quytrinh_id = Decision::where('giamsat_id','')->pluck('quytrinh_id');
       //  // dd($des);
       // $array = $des->map(function($test){
       //      $quytrinh = QuyTrinh::where('id',$test->quytrinh_id)->first();
       //      if($test->id_ma > 0  && $test->id_ma <10 ){
       //          $test->id_ma = $quytrinh->code.'00'.$test->id_ma ; 
       //       }
       //      if($test->id_ma > 9  && $test->id_ma <100 ){
       //          $test->id_ma = $quytrinh->code.'0'.$test->id_ma ; 
       //        }
       //      if($test->id_ma > 99){
       //          $test->id_ma = $quytrinh->code.''.$test->id_ma ; 
       //        }

       //      return $test;
       // });
      return $des;
    }
    public function getQuyDinh() {
        return Decision::whereNotNull('maqdgs')->get();
    }

}