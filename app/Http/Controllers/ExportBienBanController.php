<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;
use App\ThongBao;
// use Dompdf\Dompdf as PDF;
use \PDF;
use App\ViPham;
use App\Nghiphep;
class ExportBienBanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
     // $user = auth('api')->user();
        $vipham = \DB::table('vi_pham')->where('id', $id)->first();
        $dataBinding = (array) $vipham;
        $dataBinding['user_name']=[];
        if ($vipham->user_id!=null) {
            $user = \DB::table('users')->where('id', $vipham->user_id)->first();
            $dt_user=$user->name;
            $dataBinding['user_name'] =(array)$dt_user;
            // dd($dataBinding['user_name']);
        }
        elseif ($vipham->user_id==null) {
            $x=$vipham->role_id;
            $a=trim($x,'[');
            $b=trim($a,']');
            $c=explode(',',$b);

            if (count($c)<2) {
                $user = \DB::table('roles')->where('id',$c)->first();
                $dt_user=$user->name;
                $dataBinding['user_name'] = (array)$dt_user;

            }
            else {
                foreach ($c as $value) {
                    $user = \DB::table('roles')->where('id',$value)->first();
                    $role=[];
                    $dt_user=$user->name;
                    array_push($role,$dt_user);
                    // $role= $user->name);;

                    // dd($user);
                }
                    $dataBinding['user_name']=$role;
                    // dd($dataBinding['user_name']);

            }
        }
        $viphamwithoutbienban = \DB::table('vi_pham')->whereNotNull('bien_ban')->get();
        $count = 1;
        foreach ($viphamwithoutbienban as $value) {
            $temp = strtotime($value->created_at);
            $date = date('Y', $temp);
            $now = date('Y');
            if($date == $now) {
                $count++;
            }
        }
        

        if($count < 10) {
            $countstring = '000'.$count;
        } else if ($count >= 10 && $count < 100) {
            $countstring = '00'.$count;
        } else if ($count >= 100 && $count < 1000) {
            $countstring = '0'.$count;
        }

        $time = strtotime($vipham->ngay_vi_pham);

        $newformat = date('d/m/Y',$time);
        // dd($newformat);

        
        
        $dataBinding['ngayvipham'] =  $newformat;
        $dataBinding['stt'] = $countstring;
        $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        while (file_exists("uploads/bienban/".$fileName)) {
            $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        }

        $pdf = PDF::loadView('PreviewBienBan',  compact('dataBinding'))->setPaper('a4', 'portrait');
        $pdf->save('uploads/bienban/'.$fileName);
        // dd($dataBinding);
        $viphamToUpdate = ViPham::findOrFail($id);
        $viphamToUpdate->bien_ban = $fileName;
        $viphamToUpdate->status_bb = 1;
        $viphamToUpdate->save();
        return redirect('/vi-pham');

    }
     public function indexs($id)
    {
     // $user = auth('api')->user();
        // dd('here');

        $nghiphep = Nghiphep::where('id', $id)->first();
        // dd($nghiphep);
        $user = \DB::table('users')->where('id', $nghiphep->ten_nhan_vien)->first();
        $users = \DB::table('users')->where('id', $nghiphep->nguoi_nhan_cv)->first();
        $role = \DB::table('roles')->where('id',$nghiphep->vi_tri)->first();
        // $newformat = datetime('d/m/Y hh/dd/cc',$data,$datas);
        // dd($newformat);
        $ldn = $nghiphep['loai_nghi'];
        $data = $nghiphep['ngay_gui_don'];
        $datas = $nghiphep['end_date'];
        $intdate = $nghiphep['so_ngay_nghi'];
        $phone = $nghiphep['phone'];
        $ld = $nghiphep['ly_do'];
        $cv = $nghiphep['cv_ban_giao'];

        $dataBinding = (array) $nghiphep;   
        $dataBinding['user_name'] = $user->name;  
        $dataBinding['nguoi_nhan_cv'] = empty($users) ? 0 : $users->name;
        $dataBinding['role_name'] = $role->name;  
        $dataBinding['phone'] =  $phone; 
        $dataBinding['cv_ban_giao'] =  $cv;
        $dataBinding['id'] = $nghiphep['id'];
        $dataBinding['so_ngay_nghi'] = $intdate;
        $dataBinding['ly_do'] = $ld;
        $dataBinding['ngay_gui_don'] = $data;
        $dataBinding['end_date'] = $datas;
        $dataBinding['loai_nghi'] = $ldn;
        $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        while (file_exists("uploads/nghiphep/".$fileName)) {
            $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        }

        $pdf = PDF::loadView('PreviewNghiPhep',  compact('dataBinding'))->setPaper('a4', 'portrait');
        $pdf->save('uploads/nghiphep/'.$fileName);
        // dd($dataBinding);
        $viphamToUpdate = Nghiphep::findOrFail($id);
        $viphamToUpdate->bien_ban = $fileName;
        $viphamToUpdate->save();
        $check = \DB::table('model_has_roles')->where('role_id',7)->pluck('model_id')->toArray();
        foreach ($check as $value)
        {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 2;
            $user->noidung = $nghiphep->ly_do;
            $user->user_tao =$nghiphep->ten_nhan_vien;
            $user->user_see =$value;
            $user->action = 4;
            $user->save();
        }
        $thongbao = ThongBao::where('id_chuyenmuc',$id)->where('action',1)->delete();
        return redirect('/nghi-phep');
    }
    public function NSDuyet($id)
    {
     // $user = auth('api')->user();
        // dd('here');

        $nghiphep = Nghiphep::where('id', $id)->first();
        // dd($nghiphep);
        $user = \DB::table('users')->where('id', $nghiphep->ten_nhan_vien)->first();
        $users = \DB::table('users')->where('id', $nghiphep->nguoi_nhan_cv)->first();
        $role = \DB::table('roles')->where('id',$nghiphep->vi_tri)->first();
        // $newformat = datetime('d/m/Y hh/dd/cc',$data,$datas);
        // dd($newformat);
        $ldn = $nghiphep['loai_nghi'];
        $data = $nghiphep['ngay_gui_don'];
        $datas = $nghiphep['end_date'];
        $intdate = $nghiphep['so_ngay_nghi'];
        $phone = $nghiphep['phone'];
        $ld = $nghiphep['ly_do'];
        $cv = $nghiphep['cv_ban_giao'];

        $dataBinding = (array) $nghiphep;   
        $dataBinding['user_name'] = $user->name;  
        $dataBinding['nguoi_nhan_cv'] = empty($users) ? 0 : $users->name;
        $dataBinding['role_name'] = $role->name;  
        $dataBinding['phone'] =  $phone; 
        $dataBinding['cv_ban_giao'] =  $cv;
        $dataBinding['id'] = $nghiphep['id'];
        $dataBinding['so_ngay_nghi'] = $intdate;
        $dataBinding['ly_do'] = $ld;
        $dataBinding['ngay_gui_don'] = $data;
        $dataBinding['end_date'] = $datas;
        $dataBinding['loai_nghi'] = $ldn;
        $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        while (file_exists("uploads/nghiphep/".$fileName)) {
            $fileName = str_random(10)."_".$dataBinding['id'].".pdf";
        }

        $pdf = PDF::loadView('PreviewNghiPhep',  compact('dataBinding'))->setPaper('a4', 'portrait');
        $pdf->save('uploads/nghiphep/'.$fileName);
        // dd($dataBinding);
        $viphamToUpdate = Nghiphep::findOrFail($id);
        $viphamToUpdate->bien_ban = $fileName;
        $viphamToUpdate->save();
        $b=$nghiphep->ten_nhan_vien;
        if($nghiphep->nguoi_nhan_cv == null ){
         $check =[9003,$b];
        }
        else {
        $a=$nghiphep->nguoi_nhan_cv;
         $check =[9003,$a,$b];
        }
        foreach ($check as $value) {
            $user = new ThongBao;
            $user->id_chuyenmuc = $id;
            $user->type = 2;
            $user->noidung = $nghiphep->ly_do;
            $user->user_tao =$nghiphep->ten_nhan_vien;
            $user->user_see = $value;
            $user->action =5;
            $user->save();
        }
        $thongbao = ThongBao::where('id_chuyenmuc',$id)->where('action',1)->delete();
        return redirect('/nghi-phep');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($fileName) {

        $file= '/home/noith733/admin.noithatzip.vn/uploads/bienban/'.$fileName;
        // dd(base_path());

        $headers = [
          'Content-Type' => 'application/pdf',
        ];

        return response()->download($file, 'bienban.pdf', $headers);
    }
}
