<?php 
namespace App\Http\Controllers\API;
    use App\User;
    use Carbon\Carbon;
    use Cmgmyr\Messenger\Models\Message;
    use Cmgmyr\Messenger\Models\Participant;
    use Cmgmyr\Messenger\Models\Thread;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Resources\ThongBao as ThongBaoResource;
    use App\Http\Resources\Decision as DecisionResource;
    use App\Http\Resources\ViPham as ViPhamResource;
    use App\Http\Resources\NghiPhep as NghiPhepResource;
    use App\ThongBao;
    use App\Decision;
    use App\ViPham;
    use App\NghiPhep;

    /**
     *
     */
    class NghiPhepListController extends Controller
    {
        public function home(Request $request){
        $list = ThongBao::where('user_see',\Auth::User()->id)->latest()->paginateFilter();
        return ThongBaoResource::collection($list);

        }
        public function loadDetailQD(Request $request){
        $detail = Decision::where('id',$request->id)->get();
        return DecisionResource::collection($detail);
        }
        public function loadDetailVP(Request $request){
        $detail = ViPham::where('id',$request->id)->get();
        return ViPhamResource::collection($detail);
        }
        public function loadDetailNP(Request $request){
        $detail = NghiPhep::where('id',$request->id)->get();
        return NghiPhepResource::collection($detail);

        }
        public function delThongBao(Request $request){
        $thongbao =ThongBao::where('id',$request->id)->delete();
        $check =ThongBao::where('id_chuyenmuc',$request->id_chuyenmuc)->delete();

        }
        protected function checksales($id) {
        $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',2)->get();
        $check = ($id->isEmpty()) ? 0 : 1;
        return $check;
        }
        public function store(Request $req){

            $input = Input::all();
            $threads = Threads::create([
            'subject' => $input['subject'],
        ]);
        // Message
        Message::create([
            'id' => $threads->id,
            'nv_phe_duyet' => Auth::id(),
            'body' => $input['message'],
        ]);
        // Sender
        Participant::create([
            'id' => $threads->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);
        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }
        return redirect()->route('nghipheps');
        }

        public function create()
        {
        $users = User::where('id', '!=', Auth::id())->get();
        return $users;
        }

        public function show($id)
        {
        try {
            $thread = Threads::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return ['error_message' => 'The thread with ID: ' . $id . ' was not found.'];
        }
        $userId = Auth::id();
        $thread->markAsRead($userId);
        }
    }

?>