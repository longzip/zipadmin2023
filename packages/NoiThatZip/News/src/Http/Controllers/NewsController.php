<?php

namespace NoiThatZip\News\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\News\Models\News;
use App\Checknew;
use File;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = News::filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
        // dd($test);
    }

    public function khuyenmai(Request $request)
    {
        $news = News::where('slug','khuyen-mai')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    public function duan(Request $request)
    {
        $news = News::where('slug','du-an')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    public function thanhtich(Request $request)
    {
        $news = News::where('slug','thanh-tich')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    public function team(Request $request)
    {
        $news = News::where('slug','teambuilding')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    public function danhgia(Request $request)
    {
        $news = News::where('slug','danh-gia-cua-khach-hang')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    public function tintonghop(Request $request)
    {
        $news = News::where('slug','tin-tong-hop')->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();

        // $idUser = \Auth::user()->id;
        // $idread = Checknew::where('user_id',$idUser)->first();
        // $categoryread = array_merge(json_decode($idread->khuyenmai),json_decode($idread->danhgia)); 
        // $new = News::orderBy('id', 'desc')->whereIn('id',$categoryread)->get();
        // $read = [];
        // foreach ($new as $safety) {
        //     $read[] = [
        //         "id" => $safety->id ,
        //         "name" => $safety->name ,
        //         "sapo" => $safety->sapo,
        //         "ava" => $safety->ava ,
        //         "is_youtube" => $safety->is_youtube,
        //         "user_name" => $safety->user_name,
        //         "created_at" => $safety->created_at->format('H:i d-m-Y'),
        //         'read'       => 1,
        //     ];
        // }
        // $newnot = News::orderBy('id', 'desc')->whereNotIn('id',$categoryread)->get();
        // $notread = [];
        // foreach ($newnot as $safety) {
        //     $notread[] = [
        //         "id" => $safety->id ,
        //         "name" => $safety->name ,
        //         "sapo" => $safety->sapo,
        //         "ava" => $safety->ava ,
        //         "is_youtube" => $safety->is_youtube,
        //         "user_name" => $safety->user_name,
        //         "created_at" => $safety->created_at->format('H:i d-m-Y'),
        //         'read'       => 0,
        //     ];
        // }
        // $meger = array_merge($notread,$read);
        // $data = array('data'=>$meger);
        // return $data;

        // dd($categoryread);
    }

    public function create()
    {
        //
    }

    public function getthongbao(Request $request)
    {
        $pushid = News::where('id',$request->id)->get();
        $js = json_decode($pushid);

        $id = \Auth::user()->id;
        $check = Checknew::firstOrCreate(['user_id' => $id]);
        if($check->tintonghop == '') {
            $check->tintonghop = '[]';
        }
        if($check->khuyenmai == '') {
            $check->khuyenmai = '[]';
        }
        if($check->thanhtich == '') {
            $check->thanhtich = '[]';
        }
        if($check->duan == '') {
            $check->duan = '[]';
        }
        if($check->team == '') {
            $check->team = '[]';
        }
        if($check->danhgia == '') {
            $check->danhgia = '[]';
        }
        $check->save();
        $findid = Checknew::where('user_id' , $id)->first();

        if($js[0]->slug == 'tin-tong-hop') {
            $ar = json_decode($findid->tintonghop);
        }

        if($js[0]->slug == 'khuyen-mai') {
            $ar = json_decode($findid->khuyenmai);
        }

        if($js[0]->slug == 'thanh-tich') {
            $ar = json_decode($findid->thanhtich);
        }

        if($js[0]->slug == 'du-an') {
            $ar = json_decode($findid->duan);
        }

        if($js[0]->slug == 'teambuilding') {
        $ar = json_decode($findid->team);
        }

        if($js[0]->slug == 'danh-gia-cua-khach-hang') {
            $ar = json_decode($findid->danhgia);
        }

        $ss = array($js[0]->id);
        $diff = array_diff($ss , $ar);
        if($diff != array()) {
            $js = json_decode($pushid);
            $ar[] = $js[0]->id;
            if($js[0]->slug == 'tin-tong-hop') {
                $findid->tintonghop =json_encode($ar);
            }
            if($js[0]->slug == 'thanh-tich') {
                $findid->thanhtich =json_encode($ar);
            }
            if($js[0]->slug == 'khuyen-mai') {
                $findid->khuyenmai =json_encode($ar);
            }
            if($js[0]->slug == 'danh-gia-cua-khach-hang') {
                $findid->danhgia =json_encode($ar);
            }
            if($js[0]->slug == 'du-an') {
                $findid->duan =json_encode($ar);
            }
            if($js[0]->slug == 'teambuilding') {
                $findid->team =json_encode($ar);
            }
            $findid->save();
        }

        $news = News::where('id',$request->id)->filter($request->all())->latest()->paginateFilter();
        return $news->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'content' => ['required'],
            'sapo' => ['required']
        ],
        [
            'name.required'=>'Bạn chưa nhập tiêu đề',
            'content.required'=>'Bạn chưa nhập nội dung',
            'sapo.required'=>'Bạn chưa nhập tóm tắt',
        ]);
// dd($request);
        $name =  $request['name'];
        $news = new News;
        $news->name = $request['name'];
        $news->sapo = $request['sapo'];
        $news->content = $request['content'];
        $news->category = $request['category'];
        $news->role = $request['roles'];
        if($request['checkbox'] == 'true') {
            $news->is_youtube = 1;
        }
        if($request['checkbox'] == 'false') {
            $news->is_youtube = 0;
        }
        $news->slug = changeTitle($request['category']);
        $news->slug_title = changeTitle($request['name']);
        $news->user_id = \Auth::user()->id;
        $news->user_name = \Auth::user()->name;
        if($request->hasFile('file'))
        {
            $file=$request->file('file');
            $duoi =$file->getClientOriginalExtension();
            $name=$file->getClientOriginalName();
            $files=str_random(2)."_".$name;
            while (file_exists("uploads/news/".$files)) {
                $files=str_random(2)."_".$name;
            }
            $file->move("uploads/news",$files);
            $news->files=$files;
        }
        else
        {
            $news->files="";
        }

        if($request->hasFile('ava'))
        {
            $file=$request->file('ava');
            $duoi =$file->getClientOriginalExtension();
            $name=$file->getClientOriginalName();
            $files=str_random(2)."_".$name;
            while (file_exists("uploads/news/".$files)) {
                $files=str_random(2)."_".$name;
            }
            $file->move("uploads/news",$files);
            $news->ava=$files;
        }
        else
        {
            $news->ava = "";
        }
        $news->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        return $news;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $request->validate([
            'name' => ['required'],
            'content' => ['required'],
            'sapo' => ['required']
        ],
        [
            'name.required'=>'Bạn chưa nhập tiêu đề',
            'content.required'=>'Bạn chưa nhập nội dung',
            'sapo.required'=>'Bạn chưa nhập tóm tắt',
        ]);

        $news = News::find($id);
        $news->name = $request['name'];
        $news->sapo = $request['sapo'];
        $news->content = $request['content'];
        $news->category = $request['category'];
        $news->role = $request['role'];
        $news->slug = changeTitle($request['category']);
        $news->slug_title = changeTitle($request['name']);
        $news->save();
        return $news;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $new = News::find($id);
        $destinationPath = 'uploads/news/';
        File::delete($destinationPath.$new->files);
        return $new->delete();
    }

    public function add(Request $request)
    {
        $news = News::find($request->id);
        // dd($news);

        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $duoi = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/news/".$files)) {
                $files = str_random(2)."_".$name;
            }
            if($news->files != null) {
                unlink("uploads/news/".$news->files);
            }
            $file->move("uploads/news",$files);
            $news->files = $files;
        }
        else
        {
            $news->files = "";
        }
        $news->save();
        return $news;
    }
    public function addImage(Request $request)
    {
        $news = News::find($request->id);
        // dd($news);
        // dd($request['checkbox']);
        if($request['checkbox'] == 'true') {
            $news->is_youtube = 1;
        }
        if($request['checkbox'] == 'false') {
            $news->is_youtube = 0;
        }
        if($request->hasFile('ava'))
        {
            $file = $request->file('ava');
            $duoi = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/news/".$files)) {
                $files = str_random(2)."_".$name;
            }
            if($news->files != null) {
                unlink("uploads/news/".$news->files);
            }
            $file->move("uploads/news",$files);
            $news->ava = $files;
        }
        $news->save();
        return $news;
    }

    protected function checktin($slug,$column){ 
        $idUser = \Auth::user()->id;
        $idread = Checknew::where('user_id',$idUser)->first();
        $categoryread = $idread->$column;
        $new = News::where('slug',$slug)->orderBy('id', 'desc')->whereIn('id',json_decode($categoryread))->get();
        $read = [];
        foreach ($new as $safety) {
            $read[] = [
                "id" => $safety->id ,
                "name" => $safety->name ,
                "sapo" => $safety->sapo,
                "ava" => $safety->ava ,
                "is_youtube" => $safety->is_youtube,
                "user_name" => $safety->user_name,
                "created_at" => $safety->created_at->format('H:i d-m-Y'),
                'read'       => 1,
            ];
        }
        $newnot = News::where('slug',$slug)->orderBy('id', 'desc')->whereNotIn('id',json_decode($categoryread))->get();
        $notread = [];
        foreach ($newnot as $safety) {
            $notread[] = [
                "id" => $safety->id ,
                "name" => $safety->name ,
                "sapo" => $safety->sapo,
                "ava" => $safety->ava ,
                "is_youtube" => $safety->is_youtube,
                "user_name" => $safety->user_name,
                "created_at" => $safety->created_at->format('H:i d-m-Y'),
                'read'       => 0,
            ];
        }
        $meger = array_merge($notread,$read);
        $data = array('data'=>$meger);
        return $data;
    }
}
