@extends('news.master')

@section('news')
<div class="container body-content" style="margin-top: 70px;">
    <div class="pinch">
        @if(!$rolenews->isEmpty())
        <h4 class="tv-title-1 pc-title" style="border-left: 0px;padding-left: 0px;text-align: center;">
            Thông Báo Theo Phòng Ban
        </h4>
        @endif
        @foreach($rolenews as $item)
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
                    &nbsp;|&nbsp;
                    <span class="color-dabiet">Phòng Ban: </span>
                    {{$item->role}}
                    &nbsp;|&nbsp;
                    <span class="color-dabiet">Loại: </span>
                    <a href="/bang-tin/{{$item->slug}}">
                        {{$item->category}}
                    </a>
                </div>
            </li>
            <li class="clearfix"></li>
        </ul>
        @endforeach
        @if(!$rolenews->isEmpty())
        <div class="col-md-12 col-xs-12 text-center">
            <a href="/bang-tin/thong-bao" type="button" class="btn btn-info">Xem Thêm</a>
        </div>
        @endif
    </div>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tb">Thông Báo</a></li>
        <li><a data-toggle="tab" href="#kt">Khen Thưởng</a></li>
        <li><a data-toggle="tab" href="#p">Phạt</a></li>
    </ul>

    <div class="tab-content">
        <div id="tb" class="tab-pane fade in active">
            <div class="pinch">
                @foreach($thongbao as $item)
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
                <div class="col-md-12 col-xs-12 text-center">
                    <a href="/bang-tin/thong-bao" type="button" class="btn btn-info">Xem Thêm</a>
                </div>
            </div>
        </div>
        <div id="kt" class="tab-pane fade">
            <div class="pinch">
                @foreach($khenthuong as $item)
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
                <div class="col-md-12 col-xs-12 text-center">
                    <a href="/bang-tin/khen-thuong" type="button" class="btn btn-info">Xem Thêm</a>
                </div>
            </div>
        </div>
        <div id="p" class="tab-pane fade">
            <div class="pinch">
                @foreach($phat as $item)
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
                <div class="col-md-12 col-xs-12 text-center">
                    <a href="/bang-tin/phat" type="button" class="btn btn-info">Xem Thêm</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection