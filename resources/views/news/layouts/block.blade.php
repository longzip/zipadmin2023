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
            @if($item->role != '')
            &nbsp;|&nbsp;
            <span class="color-dabiet">Phòng Ban: </span>
            {{$item->role}}
            @endif
        </div>
    </li>
    <li class="clearfix"></li>
</ul>
@endforeach