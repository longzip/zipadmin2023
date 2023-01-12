<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Data;
use App\Http\Resources\DataKH as DataResource;
use DB;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Carbon\Carbon;
use DateTime;
use App\GiaiDoan;
use App\LogGiaiDoan;
class DataController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request){
        $data = Data::filter($request->all())->latest()->paginateFilter();
        return DataResource::collection($data);
    }

    public function update(Request $request,$id)
    {
        // dd($request->all());
        $user = auth('api')->user();
        $data = Data::find($id);
        $data->type = $request->picked;

        if ($request->picked == 3) {
            $lead = new Lead([
               'title' => 'Anh',
               'last_name' => $request['name'],
               'phone' => $request['phone'],
               'description' => $request['note'],
               'start_date' => Carbon::now('Asia/Ho_Chi_Minh')->toDateString(),
               'start_time' => '00:00:00',
               'end_time' => '00:00:00',
               'user_id' => $user->id,
               'type' => 1,
               
           ]);

            $id_salesarea[] = $request['kv'];
            if ($request->has('kv')) {
                $lead->salesarea_id = json_encode($id_salesarea);
            }
            $thread = Thread::create(
                [
                    'subject' => 'KH15#'  . $lead->id . $lead->last_name . ' được tạo bởi' . $user->name
                ]
            );
            $lead->thread_id = $thread->id;
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . ' đã thêm KH15' . $lead->last_name,
            ]);
            $thread = Thread::create([
                'subject' => 'KH15#'  . $lead->id . $lead->last_name . ' được tạo bởi' . $user->name
            ]);
            $lead->thread_id = $thread->id;
            $lead->save();
            $lead->setStatus('Mới tạo');
            Participant::create(
                [
                    'thread_id' => $thread->id,
                    'user_id'   => $user->id,
                    'last_read' => new Carbon,
                ]
            );
           
            activity()
            ->performedOn($lead)
            ->causedBy($user)
            ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
            ->log('Đã tạo KH15 :subject.last_name, bởi :causer.name');

            $idkh15 = Lead::where('user_id',$user->id)->orderBy('id','desc')->first();
            $data->kh15 = $idkh15['id'];
        }

        $data->save();

        $giaidoan = new GiaiDoan;
        $giaidoan->id_kh15 = $lead->id;
        $giaidoan->save();

        $gd = GiaiDoan::where('id_kh15',$lead->id)->first();
        $log = new LogGiaiDoan;
        $log->id_tt = 1;
        $log->id_giaidoan = $gd['id'];
        $log->save();

    }
    
}

?>