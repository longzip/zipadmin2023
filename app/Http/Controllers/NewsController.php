<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NoiThatZip\News\Models\News;
use Auth;
use App\Checknew;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::whereNull('role')->whereNull('deleted_at')->orderByDesc('id')->limit(20);
    	$khenthuong = $query->where('slug','khen-thuong')->get();
    	$phat = $query->where('slug','phat')->get();
    	$thongbao = $query->where('slug','thong-bao')->get();

        $getnamerole = $this->role();
        $rolenews = News::whereIn('role',json_decode($getnamerole))->limit(10)->get();

    	return view('news.import.index', ['thongbao' => $thongbao,'khenthuong' => $khenthuong,'phat' => $phat, 'rolenews' => $rolenews]);
    }

    public function category(Request $r)
    {
    	$slug = $r->category;
    	$category = News::where('slug',$slug)->pluck('category')->first();
        $getnamerole = $this->role();
    	$data = News::where('slug',$slug)->whereNull('role')->orWhereIn('role',json_decode($getnamerole))->whereNull('deleted_at')->orderByDesc('id')->paginate(1);

    	if ($r->ajax()) {
            $view = view('news.layouts.block',compact('data'))->render();
            return response()->json(['html'=>$view]);
        }

    	return view('news.import.category', ['category' => $category,'slug' => $slug,'data' => $data]);
    }

    public function detail(Request $r)
    {
    	$query = News::where('slug',$r->category)->where('slug_title',$r->title);
    	$detail = $query->get();
    	$id = $query->pluck('id');
        $getnamerole = $this->role();
    	$data = News::where('id','!=',$id)->limit(20)->orderBy('id', 'desc')->whereNull('role')->orWhereIn('role',json_decode($getnamerole))->whereNull('deleted_at')->get();
    	
    	return view('news.import.detail', ['detail' => $detail,'data' => $data]);
    }

    public function search(Request $r)
    {
        $tukhoa = $r->tukhoa;
        $data= News::where('name','like',"%$tukhoa%")->orWhere('content','like',"%$tukhoa%")->whereNull('deleted_at')->paginate(1);
        if ($r->ajax()) {
            $view = view('news.layouts.block',compact('data'))->whereNull('role')->whereNull('deleted_at')->render();
            return response()->json(['html'=>$view]);
        }

        return view('news.import.search',['data'=>$data,'tukhoa'=>$tukhoa]);
    }

    protected function role()
    {
        $getidrole = \DB::table('model_has_roles')->where('model_id',Auth::user()->id)->pluck('role_id');
        $getnamerole = \DB::table('roles')->whereIn('id',json_decode( $getidrole))->pluck('name');

        return $getnamerole;
    }

    public function test()
    {
        $id = \Auth::user()->id;
        $user_read = Checknew::where('user_id',$id)->first();

        $news_tth = News::whereNull('deleted_at')->where('slug','tin-tong-hop')->pluck('id');
        $news_km  = News::whereNull('deleted_at')->where('slug','khuyen-mai')->pluck('id');
        $news_tt  = News::whereNull('deleted_at')->where('slug','thanh-tich')->pluck('id');
        $news_da  = News::whereNull('deleted_at')->where('slug','du-an')->pluck('id');
        $news_tb  = News::whereNull('deleted_at')->where('slug','teambuilding')->pluck('id');
        $news_dg  = News::whereNull('deleted_at')->where('slug','danh-gia-cua-khach-hang')->pluck('id');

        if($user_read == '') {
            $tth = count($news_tth);
            $km = count($news_km);
            $tt = count($news_tt);
            $da = count($news_da);
            $tb = count($news_tb);
            $dg = count($news_dg);
            // dd($km);
        }else {
            $user_read_tth = json_decode($user_read->tintonghop);

            $new_tth =json_decode($news_tth);
            $tth = count(array_diff($new_tth,$user_read_tth));

            $new_km =json_decode($news_km);
            $km = count(array_diff($new_km,$user_read_tth));

            $new_tt =json_decode($news_tt);
            $tt = count(array_diff($new_tt,$user_read_tth));

            $new_da =json_decode($news_da);
            $da = count(array_diff($new_da,$user_read_tth ));

            $new_tb =json_decode($news_tb);
            $tb = count(array_diff($new_tb,$user_read_tth ));

            $new_dg =json_decode($news_dg);
            $dg = count(array_diff($new_dg,$user_read_tth ));
        }
    }
}