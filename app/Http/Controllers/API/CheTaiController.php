<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CheTai;
use App\Http\Resources\CheTai as CheTaiResource;
use DB;

class CheTaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
 
    public function index(Request $request)
    {
        $chetai = CheTai::filter($request->all())->latest()->paginateFilter();
        
        return CheTaiResource::collection($chetai);
    } 
     
    public function store(Request $request)
    {

        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ],
        [
            'name.required'=>'Bạn chưa nhập tên chế tài',
        ]);

        $chetai = new CheTai;
        $chetai->name = $request->name;
        $chetai->huong_dan = $request->huong_dan;
        $chetai->user_id = $user->id;
        $chetai->save();

        activity()
        ->performedOn($chetai)
        ->causedBy($user)
        ->withProperties(['zip' => 'chetai','id' => $chetai->id])
        ->log('Đã tạo Chế Tài  :subject.name, bởi :causer.name');

        return $chetai;
    }

    public function show($id)
    {
        $decision = CheTai::findOrFail($id);
        return new CheTaiResource($decision);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ],
        [
            'name.required'=>'Bạn chưa nhập tên chế tài',
        ]);

        $chetai = chetai::findOrFail($id);
        $chetai->name = $request->name;
        $chetai->huong_dan = $request->huong_dan;
        $chetai->user_id = $user->id;
        $chetai->save();
        
        activity()
        ->performedOn($chetai)
        ->causedBy($user)
        ->withProperties(['zip' => 'chetai','id' => $chetai->id])
        ->log('Đã sửa Chế Tài :subject.name, bởi :causer.name');

        return $chetai;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        if (!$user->can('edit users')) {
            return response()->json([
                'message' => 'Bạn không có quyền xóa',
            ]);
        }
        $user = CheTai::findOrFail($id)->delete();
        return ['message' => 'Xóa chế tài Thành Công'];
    }
    
}