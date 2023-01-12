<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChiPhi;
use App\LoaiChiPhi;
use App\ChiPhiDetail;
use App\User;
use App\ThongBao;

use App\Http\Resources\ChiPhi as ChiPhiResource;
use App\Http\Resources\ChiPhiDetail as ChiPhiDetailResource;


class ChiPhiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $chiphi = ChiPhi::filter($request->all())->latest()->paginateFilter();
        return ChiPhiResource::collection($chiphi);

    }
    public function store(Request $request)
    {
        $user = auth('api')->user();
        // $this->validate($request,[
        //     'tendexuat' => 'required',
        //     'nguoitao' => 'required',
        //     'bophan' => 'required',
        //     'ngayapdung' => 'required',
        //     'tinhtrang' => 'required',
        // ],
        // [
        //     'tendexuat.required' => 'bạn chưa nhập tên đề xuất',
        //     'nguoitao.required' => 'Bạn chưa nhập người tạo',
        //     'bophan.required' => 'Bạn chưa chọn bộ phận',
        //     'ngayapdung.required' => 'Bạn chưa nhập ngày áp dụng',
        //     'tinhtrang.required' => 'Bạn chưa chọn tình trạng',
        // ]);
        $chiphi = new ChiPhi;
        $chiphi->name = $request->tendexuat;
        $chiphi->user_id = $request['user']['id'];
        $chiphi->role_id = $request['role']['id'];
        $chiphi->TinhTrang =$request->tinhtrang;
        $chiphi->TongChiPhi =$request->tongchiphi;
        $chiphi->loaichiphi_id = $request['loaichiphi']['id'];
        $chiphi->TongTienThuc =$request->tongchiphi;
        $chiphi->save();
        $last_cp =ChiPhi::orderBy("id","desc")->first();
        $chiphidetail = ChiPhiDetail::where('chiphi_id',null)->where('user_id',$user->id)->get();
        foreach ($chiphidetail as $value) {
            $val = ChiPhiDetail::find($value->id);
            $val->chiphi_id = $last_cp["id"];
            $val->save();
        }
    }
    public function updateChiPhi(Request $request )
    {
        $user = auth('api')->user();
        $chiphi =ChiPhi::find($request->chiphiall['id']);
        $chiphi->name = $request['chiphiall']['tendexuat'];
        $chiphi->user_id = $request['chiphiall']['user']['id'];
        $chiphi->role_id = $request['chiphiall']['role']['id'];
        $chiphi->TinhTrang =$request['chiphiall']['tinhtrang'];
        $chiphi->TongChiPhi =$request['chiphiall']['tongchiphi'];
        $chiphi->TongTienThuc =$request['chiphiall']['tongtienthuc'];
        $chiphi->loaichiphi_id = $request['chiphiall']['loaichiphi']['id'];
        $chiphi->duyet = 0 ;
        $chiphi->save();
        $chiphidetail = ChiPhiDetail::where('chiphi_id',null)->where('user_id',$user->id)->get();
        ChiPhiDetail::where('chiphi_id',$request->chiphiall['id'])->delete();
        foreach ($request->item as $value) {
            $val = new ChiPhiDetail;
            $val->chiphi_id = $request->chiphiall['id'];
            $val->user_id = $request['chiphiall']['user']['id'];
            $val->soluong=$value['SoLuong'];
            $val->giatien=$value['GiaTien'];
            $val->noidung=$value['NoiDung'];
            $val->mucdich=$value['MucDich'];
            $val->tongtien=$value['TongTien'];
            $val->tienthucte=$value['TienThucTe'];
            $val->ghichu=$value['GhiChu'];
            $val->save();
            }
        return response()->json([
            'item'=>$request->item,
            'chiphiall'=>$request->chiphiall,
        ]);
    }
    public function updateChiPhiThuc(Request $request)
    {

        $user = auth('api')->user();
        foreach ($request->item as $value) {
            $val = ChiPhiDetail::find($value['id']);
            $val->TienThucTe =$value['TienThucTe'];
            $val->save();
        }
        $val = ChiPhi::find($request->chiphi['id']);
        $val->duyet=2;
        $val->TongTienThuc =$request->chiphi['tongtienthuc'];
        $val->save();
        $check = \DB::table('model_has_roles')->where('role_id',8)->pluck('model_id')->toArray();
        foreach ($check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $request->chiphi['id'];
            $user->type = 1;
            $user->noidung = 'thuc te '.$val->name;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 'BPKT da sua de xuat';
            $user->save();
        }
    }
    public function destroy($id)
    {
        $user = auth('api')->user();
        $del = ChiPhi::where('id',$id)->delete();
        $del_detail = ChiPhiDetail::where('chiphi_id',$id)->delete();

    }
   function edit()
    {
        $Cp = ChiPhiDetail::whereNull("Chiphi_id")->Where('User_id',\Auth::User()->id)->get();
        return response()->json([
            'data'=>[],
        ]);
    }
    function addItem(Request $request){
        // $this->validate($request,[
        //     'noidung' => 'required',
        //     'soluong' => 'required',
        //     // 'mucdich' => 'required',
        // ],
        // [
        //     'noidung.required' => 'bạn chưa nhập nội dung',
        //     'soluong.required' => 'Bạn chưa nhập số lượng',
        //     // 'mucdich.required' => 'Bạn chưa nhập mục đích',
        // ]);
            $Chiphiitem = new ChiPhiDetail;
            $Chiphiitem->soluong=$request->soluong;
            $Chiphiitem->giatien=$request->giatien;
            $Chiphiitem->noidung=$request->noidung;
            $Chiphiitem->mucdich=$request->mucdich;
            $Chiphiitem->tongtien=$request->giatien*$request->soluong;
            $Chiphiitem->tienthucte=$request->giatien*$request->soluong;
            $Chiphiitem->ghichu=$request->ghichu;
            $Chiphiitem->user_id=\Auth::User()->id;
            $Chiphiitem->save();
        return response()->json([
            'soluong'=>$request->soluong,
            'giatien'=>$request->giatien,
        ]);

    }
    public function guidexuat(Request $req,$id){
        $duyet = ChiPhi::find($id);
        $duyet->duyet = 1;
        $duyet->save();
        $check = \DB::table('model_has_roles')->where('role_id',10)->pluck('model_id')->toArray();
        foreach ($check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 1;
            $user->noidung = $duyet->name;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 'da them de xuat';
            $user->save();
        }
    }
    public function duyetkt(Request $req,$id){
        $duyet = ChiPhi::find($id);
        $duyet->duyet = 2;
        $duyet->save();
        $check = \DB::table('model_has_roles')->where('role_id',8)->pluck('model_id')->toArray();
        foreach ($check as $value)
        {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 1;
            $user->noidung = 'thuc te '.$duyet->name;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 'BPKT da duyet';
            $user->save();
        }
    }
    public function duyetgd(Request $req,$id){
        $duyet = ChiPhi::find($id);

        $duyet->duyet = 3 ;
        $duyet->save();
        $check = \DB::table('model_has_roles')->where('role_id',10)->pluck('model_id')->toArray();
        foreach ($check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 1;
            $user->noidung = $duyet->name;
            $user->user_tao =\Auth::User()->id;
            $user->user_see = $value;
            $user->action = 'BPGD da duyet';
            $user->save();
        }
    }
    public function khongduyet($id){
        $duyet = ChiPhi::find($id);
        $duyet->duyet = 5;
        $duyet->save();
    }
    public function pheduyet(){
        $role_id = [1,3,4,6];
        $qr = DB::table('model_has_roles')->whereIn('role_id',$role_id)->pluck('model_id')->toArray();
        $loc = array_unique($qr);
        $us = User::whereIn('id',$loc)->get();
        return $us;
    }
    public function comment(Request $request, $id){
        $user = auth('api')->user();
        $contact = ChiPhi::findOrFail($id);
        $comment = $contact->comment([
            'title' => $user->name,
            'body' => $request['body'],
        ], $user);

        return $comment;
    }
    // public function comments($id){
    //     $contact = ChiPhi::findOrFail($id);
    //     return $contact->comments;
    // }

}