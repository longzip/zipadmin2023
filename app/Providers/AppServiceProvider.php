<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use NoiThatZip\News\Models\News;
use App\Checknew;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view)
        {
            if (\Auth::check()){

                $id = \Auth::user()->id;
        // dd($id);
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
                }else {
                    $user_read_tth = json_decode($user_read->tintonghop);
                    $user_read_tt = json_decode($user_read->thanhtich);
                    $user_read_km = json_decode($user_read->khuyenmai);
                    $user_read_dg = json_decode($user_read->danhgia);
                    $user_read_tb = json_decode($user_read->team);
                    $user_read_da = json_decode($user_read->duan);

                    $new_tth =json_decode($news_tth);
                    $tth = count(array_diff($new_tth,$user_read_tth));

                    $new_km =json_decode($news_km);
                    $km = count(array_diff($new_km,$user_read_km));

                    $new_tt =json_decode($news_tt);
                    $tt = count(array_diff($new_tt,$user_read_tt));

                    $new_da =json_decode($news_da);
                    $da = count(array_diff($new_da,$user_read_da ));

                    $new_tb =json_decode($news_tb);
                    $tb = count(array_diff($new_tb,$user_read_tb ));

                    $new_dg =json_decode($news_dg);
                    $dg = count(array_diff($new_dg,$user_read_dg ));
                }
                View::share(['tth' => $tth, 'km' => $km, 'tt' => $tt, 'da' => $da, 'tb' => $tb, 'dg' => $dg]);
            }
        });
    }
}
