@extends('news.master')

@section('news')
<div class="container body-content" style="margin-top: 70px;">
    <div class="pinch">
        <div class="col-md-12">
            <div class="border-title">
                <h4 class="tv-title-1 pc-title" style="border-left: 0px;padding-left: 0px;text-align: center;">
                    {{$tukhoa}}
                </h4>
            </div>
            <div>
                <div class="pinch" id="post-data">
                    <ul class="item-event">
                        <li >
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <a href="/cong-van/tai-chinh-nha-nuoc/Cong-van-3385-BYT-KH-TC-2019-thanh-toan-chi-phi-kham-benh-chua-benh-bao-hiem-y-te-416566.aspx" title="C&#244;ng văn 3385/BYT-KH-TC năm 2019 về thanh to&#225;n chi ph&#237; kh&#225;m bệnh chữa bệnh bảo hiểm y tế theo gi&#225; dịch vụ y tế do Bộ Y tế ban h&#224;nh">
                                C&#244;ng văn 3385/BYT-KH-TC năm 2019 về thanh to&#225;n chi ph&#237; kh&#225;m bệnh chữa bệnh bảo hiểm y tế theo gi&#225; dịch vụ y tế do Bộ Y tế ban h&#224;nh
                            </a>
                        </li>
                        <li class="body">
                            <div class="note" style="text-align:right;">
                                <span class="color-bh-hl">Ngày Đăng: </span>17/06/2019
                            </div>
                        </li>
                        <li class="clearfix"></li>
                    </ul>
                    <ul class="item-event">
                        <li >
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <a href="/cong-van/thuong-mai/Cong-van-4286-BCT-TTTN-2019-dieu-hanh-kinh-doah-xang-dau-416531.aspx" title="C&#244;ng văn 4286/BCT-TTTN năm 2019 về điều h&#224;nh kinh doanh xăng dầu do Bộ C&#244;ng thương ban h&#224;nh">
                                C&#244;ng văn 4286/BCT-TTTN năm 2019 về điều h&#224;nh kinh doanh xăng dầu do Bộ C&#244;ng thương ban h&#224;nh
                            </a>
                        </li>
                        <li class="body">
                            <div class="note" style="text-align:right;">
                                <span class="color-bh-hl">Ngày Đăng: </span>17/06/2019
                            </div>
                        </li>
                        <li class="clearfix"></li>
                    </ul>
                    <ul class="item-event">
                        <li >
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <a href="/cong-van/bo-may-hanh-chinh/Cong-van-5255-VPCP-V-I-2019-lam-ro-vu-viec-bao-chi-phan-anh-ve-Thanh-tra-Bo-Xay-dung-416698.aspx" title="C&#244;ng văn 5255/VPCP-V.I năm 2019 về điều tra l&#224;m r&#245; vụ việc li&#234;n quan đến b&#225;o ch&#237; phản &#225;nh về Thanh tra Bộ X&#226;y dựng do Văn ph&#242;ng Ch&#237;nh phủ ban h&#224;nh">
                                C&#244;ng văn 5255/VPCP-V.I năm 2019 về điều tra l&#224;m r&#245; vụ việc li&#234;n quan đến b&#225;o ch&#237; phản &#225;nh về Thanh tra Bộ X&#226;y dựng do Văn ph&#242;ng Ch&#237;nh phủ ban h&#224;nh
                            </a>
                        </li>
                        <li class="body">
                            <div class="note" style="text-align:right;">
                                <span class="color-bh-hl">Ngày Đăng: </span>14/06/2019
                            </div>
                        </li>
                        <li class="clearfix"></li>
                    </ul>
                    <ul class="item-event">
                        <li >
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <a href="/cong-van/van-hoa-xa-hoi/Cong-van-5253-VPCP-CN-2019-ve-lap-Quy-hoach-chung-xay-dung-Khu-du-lich-quoc-gia-Mui-Ca-Mau-416697.aspx" title="C&#244;ng văn 5253/VPCP-CN năm 2019 về lập Quy hoạch chung x&#226;y dựng Khu du lịch quốc gia Mũi C&#224; Mau do Văn ph&#242;ng Ch&#237;nh phủ ban h&#224;nh">
                                C&#244;ng văn 5253/VPCP-CN năm 2019 về lập Quy hoạch chung x&#226;y dựng Khu du lịch quốc gia Mũi C&#224; Mau do Văn ph&#242;ng Ch&#237;nh phủ ban h&#224;nh
                            </a>
                        </li>
                        <li class="body">
                            <div class="note" style="text-align:right;">
                                <span class="color-bh-hl">Ngày Đăng: </span>14/06/2019
                            </div>
                        </li>
                        <li class="clearfix"></li>
                    </ul>
                    @foreach($data as $item)
                    <ul class="item-event">
                        <li >
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <a href="/bang-tin/{{$item->slug}}/{{$item->slug_title}}" title="{{$item->name}}">
                                {{$item->name}}
                            </a>
                        </li>
                        <li class="body">
                            <div class="note" style="text-align:right;">
                                <span class="color-bh-hl">Ngày Đăng: </span>
                                <?php echo date('d-m-Y', strtotime($item->created_at)); ?>
                            </div>
                        </li>
                        <li class="clearfix"></li>
                    </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var page = 1;
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            loadMoreData(page);
        }
    });
    function loadMoreData(page){
      $.ajax(
            {
                url: '?page=' + page,
                type: "get",
            })
            .done(function(data)
            {
                if(data.html == " "){
                    $('.ajax-load').html("No more records found");
                    return;
                }
                $("#post-data").append(data.html);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('server not responding...');
            });
    }
</script>

@endsection