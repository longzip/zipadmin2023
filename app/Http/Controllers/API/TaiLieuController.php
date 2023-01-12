<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\TaiLieu;
use App\KhoAnh;

class TaiLieuController extends Controller
{
    public function index(Request $request)
    {
        return KhoAnh::filter($request->all())->latest()->get();   
    }/**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   
    public function addTaiLieu(Request $request)
    {
        $user = auth('api')->user();
        if($request->hasFile('file')){
            foreach ($request->file as $v) {
                $duoi = $v->getClientOriginalExtension();
                $name = $v->getClientOriginalName();
                $files = str_random(2)."_".$name;
                while (file_exists("uploads/tailieuanh/".$files)) {
                    $files = str_random(2)."_".$name;
                }
                $v->move("uploads/tailieuanh",$files);
                foreach ($request->list as $key => $value) {
                    $new = new KhoAnh;
                    $new->name = $request->phong;  
                    $new->loai = $request->loai;  
                    $new->sanpham = $value;  
                    $new->link= $files;
                    $new->save();
                }
            }
        }
        return ['message' => 'Thành công!'];
    }

    public function addroomTaiLieu(Request $request)
    {
        $new = KhoAnh::find($request->id);
        $new->name = $request->phong;  
        $new->save();
    }

    public function update(Request $request, User $user)
    {
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
       
    }

    public function groupTaiLieuPhong()
    {
        $user = auth('api')->user();
        $tailieu = TaiLieu::where('id_group',1)->get();
        $tailieus = $tailieu->groupBy(function($tl){
            return $tl->group_name;
        });
        $tl = $tailieus->keys()->map(function($key) use ($tailieus){
            return [
                'cat' => $key,
                'items' => $tailieus[$key]->map(function($t){
                    return (object) [
                        'id' => $t->id,
                        'name' => $t->name
                    ];
                })
            ];
        });
        return $tl;
    }

    public function groupTaiLieuSanPham()
    {
        $user = auth('api')->user();
        $tailieu = TaiLieu::where('id_group',2)->get();
        $tailieus = $tailieu->groupBy(function($tl){
            return $tl->group_name;
        });
        $tl = $tailieus->keys()->map(function($key) use ($tailieus){
            return [
                'cat' => $key,
                'items' => $tailieus[$key]->map(function($t){
                    return (object) [
                        'id' => $t->id,
                        'name' => $t->name
                    ];
                })
            ];
        });
        return $tl;
    }

    public function groupTaiLieuLoai()
    {
        $user = auth('api')->user();
        $tailieu = TaiLieu::where('id_group',3)->get();
        $tailieus = $tailieu->groupBy(function($tl){
            return $tl->group_name;
        });
        $tl = $tailieus->keys()->map(function($key) use ($tailieus){
            return [
                'cat' => $key,
                'items' => $tailieus[$key]->map(function($t){
                    return (object) [
                        'id' => $t->id,
                        'name' => $t->name
                    ];
                })
            ];
        });
        return $tl;
    }

    public function addthietke(Request $request)
    {
        $user = auth('api')->user();
        if($request->hasFile('file')){
            $v = $request->file;
            $duoi = $v->getClientOriginalExtension();
            $name = $v->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/tailieuanh/".$request->so_number.'/'.$files)) {
                $files = str_random(2)."_".$name;
            }
            $v->move("uploads/tailieuanh/".$request->so_number.'/',$files);
            $new = new KhoAnh;
            $new->name = $request->phong;  
            $new->loai = 22;  
            $new->link= $request->so_number;
            $new->file= $files;
            $new->save();
        }
        return ['message' => 'Thành công!'];
    }

    public function showdatatailieu(Request $request)
    {
        return KhoAnh::where('link',$request->link)->latest()->get();   
    }

}