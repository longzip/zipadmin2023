@extends('news.master')

@section('news')
<div class="container body-content" style="margin-top: 50px;">
    <div class="pinch">
        <div class="col-md-8 col-xs-12">
            <div class="border-title" style="border-bottom: 0px;">
                <h4 class="tv-title-1 pc-title" style="border-left: 0px;padding-left: 0px;text-align: center;">
                    {{$detail[0]->name}}
                </h4>
            </div>
            <div class="content-box">
                <div class="col-md-12 col-xs-12">
                    @if($detail[0]->files != null)
                    <a href="/uploads/news/{{$detail[0]->files}}" class="pull-right px15">
                        <span class="button-ef">
                            <i class="fa fa-download green"></i>
                            Tải File Đính Kèm
                        </span>
                    </a>
                    @endif
                </div>
                <div class="pinch">
                    <div class="content1">
						<?php echo $detail[0]->content; ?>
					</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="border-title">
                <h4 class="tv-title-1" style="border-left: 0px;padding-left: 0px;">
                    Xem Thêm
                </h4>
            </div>
            <div class="content-box">
                <div class="pinch">
                    @include('news.layouts.block')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection