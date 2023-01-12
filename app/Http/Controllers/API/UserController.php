<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use App\Http\Resources\Contact as ContactResource;
use App\Http\Resources\Lead as LeadResource;
use App\Http\Resources\Costcenter as CostcenterResource;
use App\Http\Resources\User as UserResource;
use NoiThatZip\Costcenter\Models\Costcenter;
use DB;
use Carbon\Carbon;
use DateTime;
use App\ChamCong;
use DatePeriod;
use DateInterval;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use App\Order;
use App\ChotCong;
use App\ChotCongNew;
use App\DoanhSo;
use App\Product;
use App\LuongKhac;

class UserController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->middleware(['role_or_permission:super-admin|edit users']);
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        // app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        // $role = Role::firstOrCreate(['name' => 'asm']);
        // $role = Role::firstOrCreate(['name' => 'super-admin']);
        // Permission::firstOrcreate(['name' => 'delete leads']);
        // Permission::firstOrcreate(['name' => 'edit users']);
        // Permission::firstOrcreate(['name' => 'update users']);
        // Permission::firstOrcreate(['name' => 'delete contacts']);
        // Permission::firstOrcreate(['name' => 'edit users']);
        // Permission::firstOrcreate(['name' => 'delete videos']);
        // Permission::firstOrcreate(['name' => 'delete orders']);
        // $role = Role::firstOrcreate(['name' => 'qa']);
        // $role->givePermissionTo('delete videos');
        // $role->revokePermissionTo('delete leads');
        // $role->revokePermissionTo('delete contacts');
        // $user = User::find(9001);
        // $user->syncPermissions(['delete orders']);
        // $role->givePermissionTo('sale');
        // $role->givePermissionTo('edit users');
        // $user = User::find(253);
        // $user->syncRoles(['imports','qa','sales','asm']);
        // \App\Customer::find(10001)->delete();
        // DB::table('customers')->insert([
        //     'id' => 10001,
        //     'name' => 'Lỗ Văn Long',
        //     'phone' => '0374638603',
        //     'address_line1' => 'Tự lập, Mê Linh, Hà Nội'
        // ]);
        // User::find(9016)->syncCostcenters(Costcenter::all()->pluck('id'));
        // User::find(2)->syncCostcenters([]);
        $users = User::filter($request->all())->latest()->paginateFilter();
        return UserResource::collection($users);
    }/**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function usersList()
    {
        $users = User::select('id', 'name')->get();
    // $users->map(function($user) {
    //     $user->user_type = $user->getRoleNames();
    // });
        return $users;
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = auth('api')->user();
        if (!$user->can('edit users')) {
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'username' => $request['username'],
        ]);
        $user->syncRoles($request['roles']);
        $user->syncCostcenters(collect($request['costcenters'])->pluck('id'));
        return $user;
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(User $user)
    {
      return new UserResource($user);
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {
        // $user1 = auth('api')->user();
        // if (!$user1->can('edit users')) {
        //     return response()->toJson([
        //         'message' => 'Bạn không có quyền xóa',
        //     ], 404);
        // }
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);
        if(!empty($request->password)){
            $user->password = Hash::make($request['password']);
        }
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->date_off = $request['off'];
        $user->save();
        $user->syncRoles($request['roles']);
        $user->syncCostcenters(collect($request['costcenters'])->pluck('id'));
        return ['message' => 'Updated the user info'];
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $user = auth('api')->user();
        if (!$user->can('edit users')) {
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
    //$this->authorize('isAdmin');
        $user = User::findOrFail($id);
    // delete the user
        //$user->delete();
        return ['message' => 'User Deleted'];
    }
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);
        $currentPhoto = $user->photo;
        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);
            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return ['message' => "Success"];
    }
    public function profile()
    {
        return auth('api')->user();
    }
    public function search(){
        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(25);
        }
        return $users;
    }
    public function getRoles($id){
        return User::find($id)->getRoleNames();
    }

    public function getAllRoles(){
        return Role::all()->pluck('name');
    }

    public function getAllObjectRoles(){
        return Role::all();
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function searchContacts($id)
    {
        $user = auth('api')->user();
        if ($user->can('import')) {
            $contacts = Contact::where('user_id',$id)->paginate(25);
        }
        elseif ($user->can('asm')) {
        }
        elseif($user->can('sale')){
            $contacts = Contact::where('user_id',$user->id)->paginate(25);
        }
        return ContactResource::collection($contacts);
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function searchLeads($id)
    {
        $user = auth('api')->user();
        if ($user->can('import')) {
            $leads = Lead::where('creator_id',$id)->paginate(25);
        }
        elseif ($user->can('asm')) {
        }
        elseif($user->can('sale')){
            $leads = Lead::where('creator_id',$user->id)->paginate(25);
        }
        else{
        }
        return LeadResource::collection($leads);
    }
    public function groupCostcenters()
    {
        $user = auth('api')->user();
        $costcenters = [];
        if ($user->id == 269 || $user->id == 9318 || $user->id == 9320) {
            $costcenters = Costcenter::where('id','39')->get();
        }else{
            if ($user->id == 9335) {
                $costcenters = Costcenter::whereIn('id',[1,2,3])->get();
            }else{
                if ($user->can('import')) {
                    $costcenters = Costcenter::where('closed','2090-01-01')->get();
                }else{
                    $costcenters = $user->costcenters()->get();
                }
            }
        }
        $costcenters = $costcenters->groupBy(function($costcenter){
            return $costcenter->warehouse;
        });
        $costcenters = $costcenters->keys()->map(function($key) use ($costcenters){
            return [
                'cat' => $key,
                'items' => $costcenters[$key]->map(function($costcenter){
                    return (object) [
                        'id' => $costcenter->id,
                        'name' => $costcenter->name
                    ];
                })
            ];
        });
        return $costcenters;
    }
    public function groupCostcentersNew()
    {
        $user = auth('api')->user();
        $costcenters = [];
      
        if ($user->can('import')) {
            $costcenters = Costcenter::all();
        }else{
            $costcenters = $user->costcenters()->get();
        }
        $costcenters = $costcenters->groupBy(function($costcenter){
            return $costcenter->warehouse;
        });
        $costcenters = $costcenters->keys()->map(function($key) use ($costcenters){
            return [
                'cat' => $key,
                'items' => $costcenters[$key]->map(function($costcenter){
                    return (object) [
                        'id' => $costcenter->id,
                        'name' => $costcenter->name
                    ];
                })
            ];
        });
        return $costcenters;
    }
    public function costcenters()
    {
        $user = auth('api')->user();
        $costcenters = [];
        if ($user->can('import')) {
            $costcenters = Costcenter::select('id', 'name')->get();
        }else{
            $costcenters = $user->costcentersList();
        }
        return $costcenters;
    }
    public function findUserbyCostcenter(Request $request){
        $loc = \DB::table('model_has_roles')->where('role_id',2)->pluck('model_id');
        if (!$request['costcenters']) {
            return User::whereIn('id',$loc);
        }
        $user = User::select('id', 'name')
            ->where('id','>',0)->whereIn('id',$loc)
            ->where(function ($query) use ($request){
                foreach ($request['costcenters'] as $costcenter) {
                    $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                }
            })
        ->distinct()
        ->get();
        return $user;
    }
    public function findFriends(User $user){
        $users = User::select('id', 'name')
            ->where(function ($query) use ($user){
                foreach ($user->costcentersList()->pluck('id') as $costcenter) {
                    $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                }
            })
        ->distinct()
        ->get();
        return $users;
    }

    public function getAllUsers() {
        return User::all();
    }

    public function getThiCongUsers() {
        $thicong = \DB::table('model_has_roles')->where('role_id',14)->pluck('model_id');
        return User::whereIn('id',$thicong)->get();
    }

    public function getUserById($id) {
        return User::findOrFail($id);
    }

    public function UserSR()
    {
        $user = \Auth::user()->id;
        $sm = \DB::table('model_has_costcenters')->where('model_id',$user)->where('model_type','App\User')->pluck('costcenter_id');
        $id = \DB::table('model_has_costcenters')->whereIn('costcenter_id',$sm)->where('model_type','App\User')->pluck('model_id');
        $sales = \DB::table('model_has_roles')->whereIn('model_id',$id)->where('role_id',2)->pluck('model_id');
        return User::whereIn('id',$sales)->get();

    }
    public function updateContact($id,$iduser)
    {
        $Contact = Contact::find($id);
        $Contact->user_id = $iduser;
        $Contact->save();
    }

    public function sm()
    {
        $user = User::find(\Auth::user()->id);
        $check = $user->hasRole('sm');
        if ($check) {
            return 1;
        }else{
            return 0;
        }
    }

    public function hr()
    {
        $user = User::find(\Auth::user()->id);
        $check = $user->hasRole('Nhân Sự');
        if ($check) {
            return 1;
        }else{
            return 0;
        }
    }

    public function asm()
    {
        $user = User::find(\Auth::user()->id);
        $check = $user->hasRole('asm');
        if ($check) {
            return 1;
        }else{
            return 0;
        }
    }

    public function qa()
    {
        $user = User::find(\Auth::user()->id);
        $check = $user->hasRole('QA');
        if ($check) {
            return 1;
        }else{
            return 0;
        }
    }

    public function listcongsale(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $date = [$calendar->start,$calendar->end];
        $p = \DB::table('calendar')->where('p',$calendar->p - 1)->where('z',$calendar->z)->first();
        $user = User::find(\Auth::user()->id);
        $users = User::where('date_off','>',$p->start)
            ->where(function ($query) use ($user){
                foreach ($user->costcentersList()->pluck('id') as $costcenter) {
                    $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                }
            })
        ->distinct()
        ->get();

        $list = $users->map(function ($value) use ($date){
            if($value->hasRole('sales') || $value->hasRole('Marketing')){
                $nv = 2;
                if($value->hasRole('sm')){
                    $nv = 6;
                }
                if($value->hasRole('Marketing')){
                    $nv = 9;
                }
                return [
                    'name' => $value['name'],
                    'id' => $value['id'],
                    'username' => $value['username'],
                    'congapp' => ChamCong::where('ma_nv',$value['username'])->whereBetween('date',$date)->count(),
                    'cong' => 0,
                    'note' => '',
                    'sm' => $nv,
                    'showroom' => $value->costcentersList()->first()->name,
                ];
            }
        });
        $l = $list->filter(function($value, $key) {
            return  $value != null;
        });
        return $l->values();
    }

    public function listcongvp(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $date = [$calendar->start,$calendar->end];
        $p = \DB::table('calendar')->where('p',$calendar->p - 1)->where('z',$calendar->z)->first();
        $cc = ChamCong::whereIn('type',[1,2])->whereBetween('date',$date)->get();
        $users = $cc->unique('name')->values();

        $list = $users->map(function ($value) use ($date){
            $name = $value['info'];
            $nv = Role::where('name', 'LIKE', "%$name%")->first();
            return [
                'name' => $value['name'],
                'id' => $value['ma_nv'],
                'username' => $value['ma_nv'],
                'congapp' => ChamCong::where('ma_nv',$value['ma_nv'])->whereNotNull('in')->whereBetween('date',$date)->count(),
                'cong' => 0,
                'note' => '',
                'sm' => $nv->id,
                'showroom' => $name,
            ];
            
        });
        $l = $list->filter(function($value, $key) {
            return  $value != null;
        });
        return $l->values();
    }

    public function updatecong(Request $request)
    {

        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        if($request->p > 8){
            $check = ChotCongNew::where('id_nv',$request->id)->get();
            if($check->isEmpty()){
                $new = new ChotCong;
                $new->name = $request->name;
                $new->username = $request->ma;
                $new->cong = $request->cong;
                $new->congapp = $request->congapp;
                $new->duyet = $request->duyet;
                $new->note = $request->note;
                $new->id_nv = $request->id;
                $new->sm = $request->sm;
                $new->showroom = $request->sr;
                $new->id_calendar = $calendar->id;
                $new->save();
            }else{
                $new = ChotCongNew::where('id_nv',$request->id)->first();
                
                $new->cong = $request->cong;
                
                $new->save();
            }
        }else{
            if ($request->cong > 0) {   
                $new = new ChotCong;
                $new->name = $request->name;
                $new->username = $request->ma;
                $new->cong = $request->cong;
                $new->congapp = $request->congapp;
                $new->duyet = $request->duyet;
                $new->note = $request->note;
                $new->id_nv = $request->id;
                $new->sm = $request->sm;
                $new->showroom = $request->sr;
                $new->id_calendar = $calendar->id;
                $new->save();
            }
        }
    }

    public function luongsale(Request $request)
    {
        $fil = $request->vp;
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $u = User::find(\Auth::user()->id);
        $p = $request->p;
        $z = $request->z;

        if ($fil == 0) {
            if (\Auth::user()->id == 9074 || \Auth::user()->id == 270 || \Auth::user()->id == 9003 || \Auth::user()->id == 9097 || \Auth::user()->id == 9334 || \Auth::user()->id == 9015 || \Auth::user()->id == 9202) {
                if ($fil == 0) {
                    $cc = ChotCongNew::where('id_calendar',$calendar->id)->whereIn('sm',[2,6,9])->get();
                }else{
                    if (\Auth::user()->id == 9015 || \Auth::user()->id == 9097) {
                        $cc = ChotCong::where('id_calendar',$calendar->id)->where('id_nv',\Auth::user()->id)->get();
                    }else{
                        $cc = ChotCong::where('id_calendar',$calendar->id)->whereNotIn('sm',[2,6,9])->get();
                    }
                }
            }else{
                if (\Auth::user()->hasRole('sales') || \Auth::user()->hasRole('sm')) {
                    $cc = ChotCongNew::where('id_calendar',$calendar->id)->where('id_nv',\Auth::user()->id)->get();
                }else{
                    $cc = ChotCong::where('id_calendar',$calendar->id)->where('id_nv',\Auth::user()->id)->get();
                }
            }
        }else{
            if (\Auth::user()->id == 9074 || \Auth::user()->id == 270 || \Auth::user()->id == 9003 || \Auth::user()->id == 9097 || \Auth::user()->id == 9334 || \Auth::user()->id == 9015 || \Auth::user()->id == 9202) {
                if ($fil == 0) {
                    $cc = ChotCong::where('id_calendar',$calendar->id)->whereIn('sm',[2,6,9])->get();
                 }else{
                    if (\Auth::user()->id == 9015 || \Auth::user()->id == 9097) {
                        $cc = ChotCong::where('id_calendar',$calendar->id)->where('id_nv',\Auth::user()->id)->get();
                    }else{
                        $cc = ChotCong::where('id_calendar',$calendar->id)->whereNotIn('sm',[2,6,9])->get();
                    }
                }
            }else{
                $cc = ChotCong::where('id_calendar',$calendar->id)->where('id_nv',\Auth::user()->id)->get();
            }
        }
// dd($cc);
        $list = $cc->map(function ($value) use ($p,$z,$fil,$calendar){
            $check_ds = DoanhSo::where('p',$p)->where('z',$z)->get(); 
            $amount = 0;$dsrieng = 0;
            $saletarget = 0;
            $congdoan = 20000;
            $point = 0;
            if($check_ds->isNotEmpty()){
                $saletarget = 0;
                if ($value['sm'] == 2) {
                    $amount = DoanhSo::where('p',$p)->where('sale1',$value['id_nv'])->orWhere('sale2',$value['id_nv'])->where('p',$p)->sum('amount');
                    $dsrieng = $amount;
                    $point = DoanhSo::where('p',$p)->where('sale1',$value['id_nv'])->orWhere('sale2',$value['id_nv'])->where('p',$p)->sum('point');
                    
                    $saletarget = ($amount > 60000000) ? (($amount - 60000000) * 0.9 / 100) : 0;
                }else{
                    $amount1 = DoanhSo::where('p',$p)->where('sr1',$value['showroom'])->sum('amount');
                    $amount2 = DoanhSo::where('p',$p)->Where('sr2',$value['showroom'])->sum('amount');
                    $amount = $amount1 + $amount2;
                    $dsrieng = DoanhSo::where('p',$p)->where('sale1',$value['id_nv'])->orWhere('sale2',$value['id_nv'])->where('p',$p)->sum('amount');
                    $point = 0;
                    $saletarget = $amount*0.01;
                    if ($p == 7) {
                        if ($value['id_nv'] == 9207 || $value['id_nv'] == 9247) {
                            $saletarget = $dsrieng*0.01;
                        }
                    }
                    if ($value['id_nv'] == 9254 || $value['id_nv'] == 9260 || $value['id_nv'] == 9253) {
                        $saletarget = 0;
                    }
                    
                    
                }
            }
            $codinh = LuongKhac::where('name',$value['name'])->where('type',0)->orwhere('id_nv',$value['sm'])->where('type',0)->orderBy('id','desc')->first();
            $phatsinh = LuongKhac::where('name',$value['name'])->where('type',1)->whereBetween('date',[$calendar->start,$calendar->end])->orwhere('id_nv',$value['sm'])->where('type',1)->whereBetween('date',[$calendar->start,$calendar->end])->orderBy('id','desc')->first();

            $kpi = empty($phatsinh->luong1) ? 0 : $phatsinh->luong1;
            $luongcung = empty($codinh->luong1) ? 0 : $codinh->luong1;

            $bhxh = empty($phatsinh->luong3) ? 0 : $phatsinh->luong3 ;
            $luongkhac = empty($phatsinh->luong2) ? 0 : $phatsinh->luong2;

            $tienan = empty($codinh->luong2) ? 0 : floor($codinh->luong2) * (int)$value['cong'];
            $trachnhiem = empty($codinh->luong3) ? 0 : $codinh->luong3;

            $xangxe = empty($codinh->luong4) ? 0 : $codinh->luong4;
            $congtac = empty($phatsinh->luong4) ? 0 : $phatsinh->luong4;

            $loi = empty($phatsinh->luong5) ? 0 : $phatsinh->luong5;
            $phone = empty($codinh->luong5) ? 0 : $codinh->luong5;

            if ($p == 8) {
                if ($value['id_nv'] == 9287 || $value['id_nv'] == 9288 || $value['id_nv'] == 9276 || $value['id_nv'] == 9295 || $value['id_nv'] == 9279 || $value['id_nv'] == 9289 || $value['id_nv'] == 9294 || $value['id_nv'] == 9274) {
                    $luongcung = $luongcung*0.85;
                }
                if($value['id_nv'] == 9197 ){
                    $saletarget = $saletarget - 954800;
                }
                if($value['id_nv'] == 9246 ){
                    $point = $point + 750000;
                }
            }

            if ($p==9) {
                if ($value['id_nv'] == 9301 || $value['id_nv'] == 9289 || $value['id_nv'] == 9321 || $value['id_nv'] == 9322 || $value['id_nv'] == 9290 || $value['id_nv'] == 9291 || $value['id_nv'] == 9294 || $value['id_nv'] == 9299 || $value['id_nv'] == 9296 || $value['id_nv'] == 9300 || $value['id_nv'] == 9302 || $value['id_nv'] == 9304 || $value['id_nv'] == 9287 || $value['id_nv'] == 9297 || $value['id_nv'] == 9288 || $value['id_nv'] == 9303 || $value['id_nv'] == 9317 || $value['id_nv'] == 9305) {
                    $luongcung = $luongcung*0.85;
                }
                if ($value['id_nv'] == 9304 ){
                    $luongcung = 3700000;
                }
                if($value['id_nv'] == 9241 ){
                    $saletarget = 249000;
                }
                if($value['id_nv'] == 9023 ){
                    $saletarget = 0;
                }
                if($value['id_nv'] == 9156 ){
                    $saletarget = $saletarget - 249000;
                }
           
            }
            
            $congapp = ChamCong::where('ma_nv',$value['id_nv'])->whereBetween('date',[$calendar->start,$calendar->end])->count();

            if ($p > 8) {
                if ($value['cong'] == 0) {
                    $luongtime = $luongcung / 24 * $congapp;
                }
            }

            if ($p == 10) {
                
                if ($value['id_nv'] == 9134 && $value['showroom'] == 'HNI Time City') {
                    $saletarget = 1472085;
                }
                if ($value['id_nv'] == 9332 && $value['showroom'] == 'Online Miền Bắc') {
                    $kpi = 0;
                }
                if ($value['id_nv'] == 9134 && $value['showroom'] == 'HNI Time City') {
                    $luongcung = 5000000;
                    $loi = 0;
                }
                if ($value['id_nv'] == 9332 || $value['id_nv'] == 9329 || $value['id_nv'] == 9331 || $value['id_nv'] == 9328 || $value['id_nv'] == 9305  || $value['id_nv'] == 9319 || $value['id_nv'] == 9327 || $value['id_nv'] == 9333 || $value['id_nv'] == 9330) {
                    $luongcung = $luongcung*0.85;
                }
                if ($value['id_nv'] == 9320 || $value['id_nv'] == 9332 || $value['id_nv'] == 9318 || $value['id_nv'] == 9330){
                    $saletarget = $dsrieng*0.01;
                }
                if ($value['id_nv'] == 9332 || $value['id_nv'] == 9318){
                    $saletarget = ($amount > 60000000) ? (($amount - 60000000) * 0.9 / 100) : 0;
                }
                if ($value['id_nv'] == 9328){
                    $saletarget = 82500;
                    $luongcung = 6500000*0.85;
                }
                if ($value['id_nv'] == 9332 && $value['showroom'] == 'HNI Royal City') {
                    $dsrieng = 0;
                    $saletarget = 0;
                    $point = 0;
                    $loi = 0;
                }
                if ($value['id_nv'] == 9197){
                    $saletarget = 353500;
                }
                if ($value['id_nv'] == 9304){
                    $luongcung = 3700000;
                    $saletarget = 0;
                    $kpi = 975000;
                    $point = 220000;
                }
                if ($value['id_nv'] == 9320 && $value['showroom'] == 'Online Miền Bắc') {
                    $congdoan = 0;
                    $congapp = 0;
                    $luongtime = 0;
                    $value['cong'] = 0;
                    $loi = 0;
                    $saletarget = 0;
                }

            }

            if ($p == 11) {
                if ($value['id_nv'] == 9337 || $value['id_nv'] == 9338 || $value['id_nv'] == 9336 || $value['id_nv'] == 9341 || $value['id_nv'] == 9340 || $value['id_nv'] == 9339) {
                    $luongcung = $luongcung*0.85;
                }

                if ($value['id_nv'] == 9328){
                    $saletarget = 321470;
                    $luongcung = 6500000*0.85;
                }
            }

            if ($p == 12) {
                if ($value['id_nv'] == 9343 || $value['id_nv'] == 9346 || $value['id_nv'] == 9347 || $value['id_nv'] == 9352) {
                    $luongcung = $luongcung*0.85;
                }

                if ($value['id_nv'] == 9328){
                    $saletarget = 844315;
                    $luongcung = 6500000;
                }

                if ($value['id_nv'] == 9336){
                    $luongcung = 6500000;
                }
            }
            
            if ($p == 13) {
                if ($value['id_nv'] == 9353 || $value['id_nv'] == 9349 || $value['id_nv'] == 9346 || $value['id_nv'] == 9354 || $value['id_nv'] == 9355) {
                    $luongcung = $luongcung*0.85;
                }

                if ($value['id_nv'] == 9350){
                    $saletarget = 140362;
                    $luongcung = 6500000;
                }

                if ($value['id_nv'] == 9351 ){
                    $luongcung = 6500000;
                }
            }

            if ($p == 1) {
                if ($value['id_nv'] == 9359 || $value['id_nv'] == 9355 || $value['id_nv'] == 9362 || $value['id_nv'] == 9358 || $value['id_nv'] == 9356 || $value['id_nv'] == 9357 ) {
                    $luongcung = $luongcung*0.85;
                }

                if ($value['id_nv'] == 9350){
                    $saletarget = 177710;
                    $luongcung = 6500000;
                }

                if ($value['id_nv'] == 9351){
                    $luongcung = 6500000;
                }
            }

            if ($p == 2) {
                if ($value['id_nv'] == 9329) {
                    $luongcung = 3700000;
                    $saletarget = 0;
                }                    


                if ($value['id_nv'] == 9350){
                    $saletarget = 105420;
                    $luongcung = 6500000;
                }

                if ($value['id_nv'] == 9351){
                    $luongcung = 6500000;
                }
            }
            
            if ($p == 3) {

                
                if ($value['id_nv'] == 9367 || $value['id_nv'] == 9368 || $value['id_nv'] == 9369 ) {
                    $luongcung = 6000000;
                    $point = 0;
                }
                
                if ($value['id_nv'] == 9364){
                    $saletarget = 736050;
                    $luongcung = 6500000;
                }
                
                if ($value['id_nv'] == 9322){
                    $point = 1100000;
                }

                if ($value['id_nv'] == 9351){
                    $luongcung = 6500000;
                }
                
                if ($value['id_nv'] == 9372 || $value['id_nv'] == 9371 ) {
                    $luongcung = $luongcung*0.85;
                }
            }


            if ($value['id_nv'] == 9254 || $value['id_nv'] == 9260 || $value['id_nv'] == 9253) {
                $luongtime = $luongcung / 30 * $value['cong'];
                $congdoan = 0;
            }else{
                $luongtime = $luongcung / 24 * $value['cong'];
            }

            if ($value['id_nv'] == 9003) {
                $congdoan = 0;
            }

            return (object)[
                'name' => $value['name'],
                'id_nv' => $value['id_nv'],
                'id' => $value['id'],
                'username' => $value['username'],
                'congapp' => $congapp,
                'cong' => $value['cong'],
                'note' => $value['note'],
                'point' => $point,
                'vp' => $fil,
                'kpi' => $kpi,
                'amount' => $amount,
                'dsrieng' => $dsrieng,
                'saletarget' =>  $saletarget ,
                'sm' => $value['sm'],
                'duyet' => $value['duyet'],
                'showroom' => $value['showroom'],
                'congdoan' => $congdoan,
                'luongtime' => $luongtime,
                'bhxh' => $bhxh,
                'loi' => $loi,
                'xang' => $xangxe,
                'phone' => $phone,
                'luongcung' => $luongcung,
                'congtac' => $congtac,
                'luongkhac' => $luongkhac,
                'tienan' => $tienan,
                'tong' => $kpi + $tienan + $luongkhac + $congtac + $point +  $phone + $saletarget + $xangxe + $trachnhiem + $luongtime - $bhxh - $loi - $congdoan
            ];
        });
        
        $ds = $list->groupBy(function ($value){
            return $value->showroom;
        });

        $grouped = $list->groupBy('showroom')
        ->map(function ($item,$key) use ($fil) {
            return (object)[
                $key => [
                    'showroom' => $key,
                    'name' => $key,
                    'congapp' => $item->sum('congapp'),
                    'cong' => $item->sum('cong'),
                    'point' => $item->sum('point'),
                    'vp' => $fil,
                    'kpi' => $item->sum('kpi'),
                    'amount' => $item->sum('amount'),
                    'dsrieng' => $item->sum('dsrieng'),
                    'saletarget' =>  $item->sum('saletarget'),
                    'congdoan' => $item->sum('congdoan'),
                    'luongtime' => $item->sum('luongtime'),
                    'bhxh' => $item->sum('bhxh'),
                    'loi' => $item->sum('loi'),
                    'xang' => $item->sum('xangxe'),
                    'phone' =>$item->sum('phone'),
                    'luongcung' =>$item->sum('luongcung'),
                    'congtac' =>$item->sum('congtac'),
                    'luongkhac' =>$item->sum('luongkhac'),
                    'tienan' =>$item->sum('tienan'),
                    'tong' =>$item->sum('tong'),
                ],
            ];
        });

        $tong = $ds->map(function ($item,$key) use ($fil) {
            return (object)[
                'showroom' => $key,
                'name' => $key,
                'congapp' => $item->sum('congapp'),
                'cong' => $item->sum('cong'),
                'point' => $item->sum('point'),
                'vp' => $fil,
                'kpi' => $item->sum('kpi'),
                'amount' => $item->sum('amount'),
                'dsrieng' => $item->sum('dsrieng'),
                'saletarget' =>  $item->sum('saletarget'),
                'congdoan' => $item->sum('congdoan'),
                'luongtime' => $item->sum('luongtime'),
                'bhxh' => $item->sum('bhxh'),
                'loi' => $item->sum('loi'),
                'xang' => $item->sum('xangxe'),
                'phone' =>$item->sum('phone'),
                'luongcung' =>$item->sum('luongcung'),
                'congtac' =>$item->sum('congtac'),
                'luongkhac' =>$item->sum('luongkhac'),
                'tienan' =>$item->sum('tienan'),
                'tong' =>$item->sum('tong'),
            ];
        });
        foreach ($grouped->toArray() as $key => $value) {
            $new[] = $value;
            $new[] = $ds[$key];
        }

        return ['new' => $new,'tong' => $tong->values() ];
    }

    public function deletesr(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        ChotCong::where('id_calendar',$calendar->id)->where('showroom',$request->sr)->delete();
    }

    public function duyetcong(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $new = ChotCong::where('id_calendar',$calendar->id)->update(['duyet' => 1]);

    }

    public function chot(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $new = ChotCong::where('id_calendar',$calendar->id)->where('username',\Auth::user()->username)->get();
        if ($new->isEmpty()) {
            return 0; 
        }else{
            return 1;
        }
    }

    public function asmchot(Request $request)
    {
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $new = ChotCong::where('id_calendar',$calendar->id)->where('duyet',1)->get();
        if ($new->isEmpty()) {
            return 0; 
        }else{
            return 1;
        }
    }

    public function dayds(Request $request)
    {
        DoanhSo::where('p',$request->p)->delete();
        $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->first();
        $order = Order::whereBetween('start_date',[$calendar->start,$calendar->end])->get();
        foreach ($order as $key => $value) {
            $line = Order::find($value['id'])->orderlines;
            $point = 0;
            foreach ($line->pluck('quantity','product_id') as $k => $v) {
                $pr = Product::find($k);
                $point += $v * $pr['point'];
            }
            $ds = new DoanhSo;
            $ds->sale1 = $value['user_id'];
            $ds->p = $request->p;
            $ds->so_number = $value['so_number'];
            $ds->amount = $line->sum('amount');
            $ds->point = $point;
            $ds->sr1 = Costcenter::where('code',$value['costcenter'])->first()->name;
            $ds->save();
        }
    }

    public function timdon(Request $request){
        return DoanhSo::where('p',$request->p)->get();
    }

    public function timdonhuy(Request $request){
        return Order::all();
    }

    public function updatedon(Request $request){
        // dd($request->all());    
        $d = DoanhSo::where('so_number',$request->or)->first();
        if ($request->old > 0) {
            $id = Order::where('so_number',$request->old)->first();
            $line = Order::find($id->id)->orderlines;
            $point = 0;
            foreach ($line->pluck('quantity','product_id') as $k => $v) {
                $pr = Product::find($k);
                $point += $v * $pr['point'];
            }
            $ds = DoanhSo::find($d->id);
            $ds->amount = $d->amount - $line->sum('amount');
            $ds->point = $d->point - $point;
            $ds->save();
        }
        if ($request->amount > 0 && $request->point > 0 ) {
            $ds = DoanhSo::find($d->id);
            $ds->amount = $request->amount;
            $ds->point = $request->point ;
            $ds->save();
        }
        if (isset($request->sr) && $request->sale > 0) {
            $ds = DoanhSo::find($d->id);
            $ds->amount = $d->amount / 2;
            $ds->sr2 = $request->sr;
            $ds->sale2 = $request->sale;
            $ds->point = $d->point / 2;
            $ds->save();
        }

    }

    public function addluongkhac(Request $request){
        // dd($request->all());
        $newYear = new Carbon('first day of January 2018');
        if($request->bophan == 'null' && $request->nv == 'null'){
            return response()->json([
                'message' => 'Bạn chưa chọn bộ phận hoặc nhân viên',
            ], 404);
        }
        if($request->bophan != 'null' && $request->nv != 'null'){
            return response()->json([
                'message' => 'Bạn chỉ cần chọn bộ phận hoặc nhân viên',
            ], 404);
        }
        if($request->bophan != 'null'){
            $new = new LuongKhac;
            $new->id_nv = $request->bophan;
            $new->name = Role::find($request->bophan)->name;
            $new->luong1 = $request->luong;
            $new->luong2 = $request->tienan;
            $new->luong3 = $request->trachnhiem;
            $new->luong4 = $request->xang;
            $new->luong5 =$request->phone;
            $new->type = 0;
            $new->date = empty($request->date) ? $newYear->toDateString() : $request->date ;
            $new->save();
        }
        if($request->nv != 'null'){
            $new = new LuongKhac;
            $new->id_nv = $request->nv;
            $new->name = User::find($request->nv)->name;
            $new->luong1 = $request->luong;
            $new->luong2 = $request->tienan;
            $new->luong3 = $request->trachnhiem;
            $new->luong4 = $request->xang;
            $new->luong5 = $request->phone;
            $new->type = 0;
            $new->date = empty($request->date) ? $newYear->toDateString() : $request->date ;
            $new->save();
        }
    }

    public function addluongphu(Request $request){
        $newYear = new Carbon('first day of January 2018');
        if($request->bophan == 'null' && $request->nv == 'null'){
            return response()->json([
                'message' => 'Bạn chưa chọn bộ phận hoặc nhân viên',
            ], 404);
        }
        if($request->bophan != 'null' && $request->nv != 'null'){
            return response()->json([
                'message' => 'Bạn chỉ cần chọn bộ phận hoặc nhân viên',
            ], 404);
        }
        if($request->bophan != 'null'){
            $new = new LuongKhac;
            $new->id_nv = $request->bophan;
            $new->name = Role::find($request->bophan)->name;
            $new->luong1 = $request->kpi;
            $new->luong2 = $request->luongkhac;
            $new->luong3 = $request->trachnhiem;
            $new->luong4 = $request->congtac;
            $new->luong5 =$request->loi;
            $new->type = 1;
            $new->date = empty($request->date) ? $newYear->toDateString() : $request->date ;
            $new->save();
        }
        if($request->nv != 'null'){
            $new = new LuongKhac;
            $new->id_nv = $request->nv;
            $new->name = User::find($request->nv)->name;
            $new->luong1 = $request->kpi;
            $new->luong2 = $request->luongkhac;
            $new->luong3 = $request->bhxh;
            $new->luong4 = $request->congtac;
            $new->luong5 = $request->loi;
            $new->type = 1;
            $new->date = empty($request->date) ? $newYear->toDateString() : $request->date ;
            $new->save();
        }
    }

    public function showluongkhac(Request $request){
        return LuongKhac::where('type',$request->type)->get();
    }

    public function thuctrang(Request $request){
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $salesTargets = $sales->map(function($sale) use($dates){
            $name = $sale->name;
            $check = \App\NghiPhep::where('loai_nghi',3)->where('ten_nhan_vien',$sale->id)->orderBy('id','desc')->get();
            if ($check->isNotEmpty()) {
                $nghi = 1;
                $ngaynghi = $check[0]->end_date;
                $sm = $sale->hasRole('sm') ? 1 : 0;
                $s = $sale->hasRole('sales') ? 1 : 0;
            }else{
                $nghi = 0;
                $sm = 0;
                $s = 0;
                $ngaynghi = '';
            }
            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'nghi' => $nghi,
                'date' => $ngaynghi,
                'sm' => $sm,
                'sale' => $s,
            ];
        });
        $showroom = $salesTargets->groupBy(function($salet){
            return $salet->costcenter;
        });

        return $showroom;
    }

    public function dexuat(Request $request){
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $salesTargets = $sales->map(function($sale) use($dates){
            $name = $sale->name;
            $check = \App\NghiPhep::where('loai_nghi',3)->where('ten_nhan_vien',$sale->id)->orderBy('id','desc')->first();

            if (!empty($check)) {
                $nghi = 1;
                $ngaynghi = $check->end_date;
                $sm = $sale->hasRole('sm') ? 1 : 0;
                $s = $sale->hasRole('sales') ? 1 : 0;
            }else{
                $nghi = 0;
                $sm = 0;
                $s = 0;
                $ngaynghi = '';
            }
            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'nghi' => $nghi,
                'date' => $ngaynghi,
                'sm' => $sm,
                'sale' => $s,
            ];
        });
        $showroom = $salesTargets->groupBy(function($salet){
            return $salet->costcenter;
        })->map(function ($value,$key){
            return (object)[
                'name' => $key,
                'nghi' => $value->sum('nghi'),
                'sm' => $value->sum('sm'),
                'sale' => $value->sum('sale') - $value->sum('sm'),
            ];
        })->filter(function ($value, $key) {
            return $value->nghi > 0;
        })->values();

        return $showroom;
    }
}