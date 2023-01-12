<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChiPhiDetail;

use App\Http\Resources\ChiPhiDetail as ChiPhiDetailResource;


class CPitemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $chiphi_detail = ChiPhiDetail::filter($request->all())->latest()->paginateFilter();
        return ChiPhiDetailResource::collection($chiphi_detail);
    }
    public function store(Request $request)
    {
        // $user = auth('api')->user();

        // $request->validate([
        //     'name' => ['required', 'string', 'max:255']
        // ],
        // [
        //     'name.required'=>'Bạn chưa nhập tên quyết định'
        // ]);

        // // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');
        // $lastRecord = ChiPhiDetail::all()->last();

        // $chiphi = new ChiPhiDetail;
        // // $quytrinh->id = $lastRecord['id'] + 1;
        // $chiphi->namespace = $request->name;
        // $chiphi->link = $request->link;
        // $chiphi->code = $request->code;
        // $chiphi->user_id = $user->id;
        // $quytrinh->save();
        // activity()
        // ->performedOn($quytrinh)
        // ->causedBy($user)
        // ->withProperties(['zip' => 'quytrinh','id' => $quytrinh->id])
        // ->log('Đã tạo Quy Trình :subject.name, bởi :causer.name');
        // return $chiphi;
    }
    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
               // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');

        // $quytrinh = QuyTrinh::findOrFail($id);
        // $quytrinh->name = $request->name;
        // $quytrinh->link = $request->link;
        // $quytrinh->code = $request->code;
        // $quytrinh->save();
        // return $quytrinh;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        // dd($id);
        $detail = ChiPhiDetail::find($id);
        $id_chiphi = $detail->chiphi_id;
         ChiPhiDetail::where('id',$id)->delete();
        return response()->json([
            'i'=>ChiPhiDetail::where('chiphi_id',$id_chiphi)->get(),
        ]);
    }
    function edit()
    {
        $items = [[
            "name"=> "SSD",
            "price"=>7,
          "soluong"=>2,
          "dprice"=>12,
          "muchdich"=>"thay máy",
          "ghichu"=>"32g"
       ],[
            "name"=> "man hinh",
            "price"=>10,
          "soluong"=>6,
          "dprice"=>12,
          "muchdich"=>"thay man hinh chiếu",
          "ghichu"=>"54 inch"
       ]
   ];

        return response()->json([
            'data'=>$items,


        ]);
    }

}