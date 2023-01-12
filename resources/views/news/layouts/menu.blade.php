<div id="wrapper" class="">
    <div id="loginAlertModal" class="modal fade pinch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="font-weight: bold;background-color:orange;border-top-left-radius:5px;border-top-right-radius:5px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="modaltitle" class="modal-title" style="color:white">Đăng nhập</h4>
            </div>
            <div class="modal-footer">
                <a id="modalaction" href="#" onclick="OpenRM()" class="btn green" style="background-color:orange;color:white">Đăng nhập</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <span class="glyphicon glyphicon-align-justify icon-menu-header-left-menu"></span>
            <div id="left-menu">
                <a href="{{route('home')}}">
                    AdminZip
                </a>
            </div>
            <a href="javascript:void(0);" data-wrapper="#menu-toggle" class="menu-toggle" data-toggled="toggled-left"><span class="fa fa-times icon-menu-header-left-close"></span></a>
        </li>
        <li>
            <a href="{{route('news.index')}}"><span class="fa fa-home"></span>&nbsp;Trang chủ</a>
        </li>
        <li>
            <a href="/bang-tin/thong-bao"><span class="fa fa-get-pocket"></span>&nbsp;Thông Báo</a>
        </li>
        <li>
            <a href="/bang-tin/khen-thuong"><span class="fa fa-newspaper-o" style="font-size:13px"></span>&nbsp;Khen Thưởng</a>
        </li>
        <li>
            <a href="/bang-tin/phat"><span class="fa fa-envelope"></span>&nbsp;Phạt</a>
        </li>
    </ul>
    <form method="post" action="/bang-tin/tukhoa">
        <div class="input-group">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input type="text" name="tukhoa" class="form-control main-search-text" placeholder="Tìm thông báo...">
            <span class="input-group-btn">
                <button class="btn btn-default glyphicon glyphicon-search main-search-btn" type="submit"></button>
            </span>
        </div>
    </form>
</div>