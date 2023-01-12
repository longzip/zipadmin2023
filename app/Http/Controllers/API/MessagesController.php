<?php

namespace App\Http\Controllers\API;

use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Thread as ThreadResource;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // All threads, ignore deleted/archived participants
        // return $threads = Thread::getAllLatest()->get();
        // All threads that user is participating in
        // return $threads = Thread::forUser(auth('api')->user()->id)->latest('updated_at')->paginate(25);
        // All threads that user is participating in, with new messages
        $user = \Auth::User();
        $date = $request->dates;
        if($this->checksales($user->id) == 0){
            if ($request->costcenters) {
                $data = collect();
                $users = User::select('id', 'name')
                    ->where(function ($query) use ($request){
                        foreach ($request['costcenters'] as $costcenter) {
                            $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                        }
                    })
                ->distinct()
                ->get();

                foreach ($users as $key => $value) {
                    $thread = Thread::forUserWithNewMessages($value->id)->get();
                    $data = $thread->merge($data);
                }
                $threadss = $data->pluck('id');
                $threads = Thread::whereBetween('updated_at', [Carbon::parse($date[0])->startOfDay(), Carbon::parse($date[1])->endOfDay()])->whereIn('id',$threadss)->latest('updated_at')->paginate(25);
            }else{
                $threads = Thread::whereBetween('updated_at', [Carbon::parse($date[0])->startOfDay(), Carbon::parse($date[1])->endOfDay()])->latest('updated_at')->paginate(25);
            }
        }
        else {
            $threads = Thread::forUserWithNewMessages(\Auth::id())->whereBetween('updated_at', [Carbon::parse($date[0])->startOfDay(), Carbon::parse($date[1])->endOfDay()])->latest('updated_at')->paginate(25);
        }

        return ThreadResource::collection($threads);
    }

    protected function checksales($id) {
        $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',2)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        return $check;
    }

    protected function checkasm($id) {
        $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',4)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        return $check;
    }

    protected function checksm($id) {
        $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',6)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        return $check;
    }

    protected function checkimport($id) {
        $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',1)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        return $check;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = Input::all();
        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);
        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);
        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }
        return redirect()->route('messages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return ['error_message' => 'The thread with ID: ' . $id . ' was not found.'];
        }
        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();
        // don't show the current user in list
        $userId = Auth::id();
        //$users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $thread->markAsRead($userId);
        //return view('messenger.show', compact('thread', 'users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return $users;
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
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('messages');
        }
        $thread->activateAllParticipants();
        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Input::get('message'),
        ]);
        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant(Input::get('recipients'));
        }
        return redirect()->route('messages.show', $id);
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
