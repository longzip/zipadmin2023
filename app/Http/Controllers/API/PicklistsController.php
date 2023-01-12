<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use NoiThatZip\Costcenter\Models\Costcenter;
use NoiThatZip\Lead\Models\Lead;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Quote\Models\Quote;
use NoiThatZip\SalesArea\Models\SalesArea;
use App\Order;
use App\RolesTarget;

class PicklistsController extends Controller
{
  public function activityTypes()
  {
    return DB::table('activity_types')->select('id','name')->get()->toArray();
  }
  public function users()
  {
    $loc = \DB::table('model_has_roles')->where('role_id',2)->orwhere('role_id',4)->pluck('model_id');
    $user = auth('api')->user();
    if ($user->can('import')) {
      return DB::table('users')->select('id','name')->whereIn('id',$loc)->get()->toArray();
    }
    elseif ($user->can('asm')) {
      return DB::table('users')->select('id','name')->whereIn('id',$loc)->get()->toArray();
    }
    elseif ($user->can('sale')) {
      return DB::table('users')->select('id','name')->where('id',$user->id)->get()->toArray();
    }
    else{
    }
  }
  public function resourcePicklists()
  {
    return DB::table('resources')->select('id','last_name')->get()->toArray();
  }
  public function costcenterPicklists()
  {
    $user = auth('api')->user();
    if ($user->id == 269 || $user->id == 9318 || $user->id == 9320 || $user->id == 9367  || $user->id == 9368) {
       return DB::table('costcenters')->where('id','39')->select('id','name')->get()->toArray();
    }else{
      if ($user->id == 9335) {
        return DB::table('costcenters')->whereIn('id',[1,2,3])->select('id','name')->get()->toArray();
      }else{
        if ($user->can('import')) {
          $SR = DB::table('costcenters')->where('closed','2090-01-01')->select('id','name')->get()->toArray();
          $hanoi = DB::table('costcenters')->whereIn('id',[1,2,3,4])->where('closed','2090-01-01')->select('id','name')->get()->toArray();
          $hcm = DB::table('costcenters')->whereIn('id',[8,9,11,13])->where('closed','2090-01-01')->select('id','name')->get()->toArray();
          array_push($SR,$hanoi,$hcm);
          // dd($SR);
          return $SR;
        }
        elseif ($user->can('asm')) {
    
        }
        elseif ($user->can('sale')) {
          return $user->costcentersList();
        }
        else{
          return $user->costcentersList();
          return ['message' => 'Bạn cần đăng nhập'];
        }
      }
    }

  }

  public function costcenterPicklistss()
  {
    $user = auth('api')->user();
    if ($user->id == 9335) {
      return DB::table('costcenters')->whereIn('id',[1,2,3])->select('id','name')->get()->toArray();
    }else{
      if ($user->can('import')) {
        $SR = DB::table('costcenters')->where('closed','2090-01-01')->select('id','name')->get()->toArray();
        $hanoi = DB::table('costcenters')->whereIn('id',[1,2,3,4])->where('closed','2090-01-01')->select('id','name')->get()->toArray();
        $hcm = DB::table('costcenters')->whereIn('id',[8,9,11,13])->where('closed','2090-01-01')->select('id','name')->get()->toArray();
        array_push($SR,$hanoi,$hcm);
        return $SR;
      }
      elseif ($user->can('asm')) {
  
      }
      elseif ($user->can('sale')) {
        return $user->costcentersList();
      }
      else{
        return $user->costcentersList();
        return ['message' => 'Bạn cần đăng nhập'];
      }
    }
  }
  public function contactPicklists()
  {
    $picklist = [];
    $picklist['users'] = DB::table('users')->select('id','name')->get()->toArray();
    $picklist['activity_types'] = DB::table('activity_types')->select('id','name')->get()->toArray();
    $picklist['costcenters'] = DB::table('costcenters')->select('id','name')->get()->toArray();
    return $picklist;
  }

  public function dashboardList(Request $request){
    return [
      'lead_count' => Lead::filter($request->all())->count(),
      'contact_count' => Contact::filter($request->all())->count(),
      'quote_count' => Quote::all()->count(),
      'order_count' => Order::all()->count(),
    ];
  }

  public function areasPicklists()
  {
    $user = auth('api')->user();
    return SalesArea::select('id','name')->get()->toArray();
  }

  public function asm()
  {
    $loc = \DB::table('model_has_roles')->where('role_id',4)->orwhere('role_id',6)->pluck('model_id');
    // dd(array_unique($loc->toArray()));
    return DB::table('users')->select('id','name')->whereIn('id',array_unique($loc->toArray()))->get()->toArray();
  }

  public function store(Request $request)
  {
    // dd($request->all());
    if($request->i != null){
      $data = \DB::table('calendar')->where('p_i',$request->year)->where('i',$request->i)->get()->toArray();
      foreach ($data as $key => $value) {
        $new = new RolesTarget;
        $new->user_id = $request->user['id'];
        $new->calendar_id = $value->id;
        $new->name = $request->user['name'];
        $new->p = $value->p;
        $new->z = $value->z;
        $new->d = $value->start;
        $new->save();
      }
    }else{
      $data = \DB::table('calendar')->where('z',$request->year)->where('p',$request->pt)->get()->toArray();
      foreach ($data as $key => $value) {
        $new = new RolesTarget;
        $new->user_id = $request->user['id'];
        $new->name = $request->user['name'];
        $new->calendar_id = $value->id;
        $new->p = $value->p;
        $new->d = $value->start;
        $new->z = $value->z;
        $new->save();
      }
    }

  }

  public function p(Request $request)
  {
    if($request->all() == []){
      $data = RolesTarget::where('z',14)->get();
    }else{
      $data = RolesTarget::where('z',$request->year)->get();
    }
    $map = $data->groupBy(function ($group){
      return $group->p;
    });
    return $map;
  }

  public function login()
  {
    return \Auth::user();
  }
}