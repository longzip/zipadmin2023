<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Auth;
use App\User;
use App\Product;
use Carbon\Carbon;
use DB;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use App\GiaiDoan;
use App\LogGiaiDoan;

class TeleproController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = 'https://api.telepro.me/api/v1/jobs/1149/calls?&search=&call_converted=1&conversation_status=dongytraodoi&error=&keywords=&limit=&package=&page='.$request->page.'&period=alltime&search=&status=success';
        $token = 'XyBIK37CF8zUvXR9rVzu';
        $ch = curl_init( $url );
		$authorization = "Authorization: Bearer ".$token;
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$res=json_decode(curl_exec($ch),true);
		// return json_encode($response)->calls;
		// return $response;
		return $res['calls'];
		// dd(json_decode($response));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = 'https://api.telepro.me/api/v1/jobs/1149/calls?&search=&call_converted=1&conversation_status=dongytraodoi&error=&keywords=&limit=1000&package=&page=1&period=today&search=&status=success';
        $token = 'XyBIK37CF8zUvXR9rVzu';
        $ch = curl_init( $url );
		$authorization = "Authorization: Bearer ".$token;
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		// $response = curl_exec($ch);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$res=json_decode(curl_exec($ch),true);
		// return $res['calls']['data'];
        $user = auth('api')->user();

        foreach ($res['calls']['data'] as $key => $request) {
	        $lead = new Lead([
	           'last_name' => $request['contact'],
	           'email' => $request['email'],
	           'phone' => $request['phone'],
	           'address' => $request['data_provided']['diachi'],
	           'start_date' => Carbon::now('Asia/Ho_Chi_Minh')->toDateString(),
	           'note'     => $request['notes'],
	           'user_id' => 269,
	           'type' => 1,
	           'salesarea_id' => '[299]',
	           'description' => 'Vị trí: '.$request['results_parsed']['canhocuakhotoathapnaotaioceanpark'].',số người: '.$request['results_parsed']['canhokhcobaonhieunguoio'].',email+zalo: '.$request['results_parsed']['diachiemailzalocuakhachhanglagi'].',loại nhà: '.$request['results_parsed']['loainhacuakhachla'].',nhu cầu: '.$request['results_parsed']['nhucaucuthecuakhachhanglagi'],
	       ]);
	        $lead->save();
	        $thread = Thread::create(
	            [
	                'subject' => 'KH15#'  . $lead->id . $lead->last_name . ' được tạo bởi Lữ Thị Điểm'
	            ]
	        );
	        $lead->thread_id = $thread->id;
	        $lead->save();
	        //
	        $lead->setStatus('Mới tạo');
	        // Message
	            Message::create([
	                'thread_id' => $thread->id,
	                'user_id' => 269,
	                'body' => 'Lữ Thị Điểm' . ' đã thêm KH15' . $lead->last_name,
	            ]);
	        // Sender
	        // Sender
	        Participant::create(
	            [
	                'thread_id' => $thread->id,
	                'user_id'   => 269,
	                'last_read' => new Carbon,
	            ]
	        );
	       
	        activity()
	        ->performedOn($lead)
	        ->causedBy($user)
	        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
	        ->log('Đã tạo KH15 :subject.last_name, bởi :causer.name');

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
