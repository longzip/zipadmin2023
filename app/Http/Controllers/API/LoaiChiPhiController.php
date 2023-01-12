<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LoaiChiPhi;
use App\Http\Resources\LoaiChiPhi as LoaiChiPhiResource;
class LoaiChiPhiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $loaichiphi = LoaiChiPhi::filter($request->all())->latest()->paginateFilter();
        return LoaiChiPhiResource::collection($loaichiphi);
    }
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ],
        [
            'name.required'=>'Bạn chưa nhập tên quyết định'
        ]);
        // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');
        $lastRecord = LoaiChiPhi::all()->last();

        $loaichiphi = new LoaiChiPhi;
        $loaichiphi->name = $request->name;
        $loaichiphi->save();
        // activity()
        // ->performedOn($loaichiphi)
        // ->causedBy($user)
        // ->withProperties(['zip' => 'quytrinh','id' => $quytrinh->id])
        // ->log('Đã tạo Quy Trình :subject.name, bởi :causer.name');
        return $loaichiphi;
    }
    public function show(LoaiChiPhi $loaichiphi)
    {
      // return new LoaiChiPhiResource($loaichiphi);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ],
        [
            'name.required'=>'Bạn chưa nhập tên quyết định'
        ]);

        // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');

        $loaichiphi = LoaiChiPhi::findOrFail($id);
        $loaichiphi->name = $request->name;
        $loaichiphi->save();
        return $loaichiphi;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        // if (!$user->can('edit users')) {
        //     return response()->json([
        //         'message' => 'Bạn không có quyền xóa',
        //     ]);
        // }
        $user = LoaiChiPhi::where('id',$id)->delete();
        // return response()->json([
        //         'message' => 'Xóa Quy trình Thành Công',
        //     ]);
    }
    public function getAllLoaiChiPhi(){
        return LoaiChiPhi::all();
    }

}