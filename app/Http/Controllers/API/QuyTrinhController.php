<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuyTrinh;
use App\Http\Resources\QuyTrinh as QuyTrinhResource;

class QuyTrinhController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $quytrinh = QuyTrinh::filter($request->all())->latest()->paginateFilter();
        return QuyTrinhResource::collection($quytrinh);
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
        $lastRecord = QuyTrinh::all()->last();

        $quytrinh = new QuyTrinh;
        // $quytrinh->id = $lastRecord['id'] + 1;
        $quytrinh->name = $request->name;
        $quytrinh->link = $request->link;
        $quytrinh->code = $request->code;
        $quytrinh->user_id = $user->id;
        $quytrinh->save();

        activity()
        ->performedOn($quytrinh)
        ->causedBy($user)
        ->withProperties(['zip' => 'quytrinh','id' => $quytrinh->id])
        ->log('Đã tạo Quy Trình :subject.name, bởi :causer.name');

        return $quytrinh;
    }

    public function show(QuyTrinh $quytrinh)
    {
      return new QuyTrinhResource($quytrinh);
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

        $quytrinh = QuyTrinh::findOrFail($id);
        $quytrinh->name = $request->name;
        $quytrinh->link = $request->link;
        $quytrinh->code = $request->code;
        $quytrinh->save();
        return $quytrinh;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        // if (!$user->can('edit users')) {
        //     return response()->json([
        //         'message' => 'Bạn không có quyền xóa',
        //     ]);
        // }
        $user = QuyTrinh::where('id',$id)->delete();
        // return response()->json([
        //         'message' => 'Xóa Quy trình Thành Công',
        //     ]);
    }

}