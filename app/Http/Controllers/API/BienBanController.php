<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\BienBan;
use App\Http\Resources\BienBan as BienBanResource;
use DB;

class BienBanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
 
    public function index(Request $request)
    {
        $bienban = BienBan::filter($request->all())->latest()->paginateFilter();
        
        return BienBanResource::collection($bienban);
    } 
     
    public function store(Request $request)
    {

        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ],
        [
            'name.required'=>'Bạn chưa nhập tên biên bản',
        ]);

        $bienban = new BienBan;
        $bienban->name = $request->name;
        $bienban->decision_id = $request->decision['id'];
        $bienban->user_id = $request->resources['id'];
        $bienban->note = $request->note;
        $bienban->user_create = $user->id;
        $bienban->save();

        activity()
        ->performedOn($bienban)
        ->causedBy($user)
        ->withProperties(['zip' => 'bienban','id' => $bienban->id])
        ->log('Đã tạo Biên Bản :subject.name, bởi :causer.name');

        return $bienban;
    }

    public function show($id)
    {
        $decision = BienBan::findOrFail($id);
        return new BienBanResource($decision);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ],
        [
            'name.required'=>'Bạn chưa nhập tên biên bản',
        ]);

        $bienban = BienBan::findOrFail($id);
        $bienban->name = $request->name;
        $bienban->decision_id = $request->decision['id'];
        $bienban->user_id = $request->resources['id'];
        $bienban->note = $request->note;
        $bienban->save();
        
        activity()
        ->performedOn($bienban)
        ->causedBy($user)
        ->withProperties(['zip' => 'bienban','id' => $bienban->id])
        ->log('Đã sửa Biên Bản :subject.name, bởi :causer.name');

        return $bienban;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        if (!$user->can('edit users')) {
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
        $user = User::findOrFail($id);
        return ['message' => 'Xóa Quyết Định Thành Công'];
    }
    
}