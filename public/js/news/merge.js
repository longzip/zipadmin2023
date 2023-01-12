

    function SetHeight() {
        var selectsObj = $('.body-content .row .col-md-6');
        selectsObj.removeAttr("style");
        if ($(window).width() > 974) {
            $(selectsObj).each(function (index) {
                if (index % 2 == 0 && index !== selectsObj.length - 1) {
                    var height = $(this).height();
                    var objNext = selectsObj.eq(index + 1);

                    if (height < objNext.height()) {
                        height = objNext.height();
                    }
                    $(this).css("height", height);
                    objNext.css("height", height);
                }
            });
        }
    }
    $(document).ready(function () {
        SetHeight();
    });
    $(window).resize(function () {
        SetHeight();
    });

    // ẩn hiện menu top
    $("#hearderMemu").autoHidingNavbar();
    $('#hearderMemu').scrollToFixed();

    var MemberCustomerID = '';

    $(document).ready(function () {
        var offset = 300,
            offset_opacity = 1200,
            scroll_top_duration = 700,
            $back_to_top = $('.cd-top');
        $(window).scroll(function () {
            ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
            if ($(this).scrollTop() > offset_opacity) {
                $back_to_top.addClass('cd-fade-out');
            }
        });
        $back_to_top.on('click', function (event) {
            event.preventDefault();
            $('body,html').animate({
                scrollTop: 0,
            }, scroll_top_duration
            );
        });
        //active menu right
        ActiveMainMenu();
        var url = window.location.href;
        if (MemberCustomerID != "0" && MemberCustomerID != 0) {
            if (MemberCustomerID.indexOf(".") < 0) {
                if (url.indexOf("phap-luat/tim-van-ban.aspx") <= 0 && url.indexOf("page/searchfast.aspx") <= 0 && url.indexOf("/van-ban/") <= 0 && url.indexOf("/cong-van/") <= 0 && url.indexOf("/TCVN/") <= 0 && url.indexOf("law-viet-nam/search-document.aspx") <= 0 && url.indexOf("en/searchfast.aspx") <= 0) {
                    AddCustomerAction(0, 1000, window.location.href);
                }
            }
        }
    })

    function OpenRM() {
        $(".cd-panel-close").click();
        $("#open-right-menu").click();
    }
    $(".panel-click").click(function () {
        $(".cd-panel-close").click();
    })

    function ActiveMainMenu() {
        var url = window.location.href;
        if (url.substring(url.lastIndexOf('/')) == "/") {
            $("#sidebar-wrapper .sidebar-nav > li:nth-child(2) > a").addClass("active");
        }
        else {
            $("#sidebar-wrapper .sidebar-nav > li > a").each(function () {
                var link = $(this).attr("href");
                if (url.toLowerCase().indexOf(link.toLowerCase()) >= 0 && link != "/") {
                    $(this).addClass("active");
                }
            });
        }
    }

    function nSize() {
        $(".pinch").css("font-size", "16px");
        $(".note").css("font-size", "15px");
        $(".pinch-title").css("font-size", "18px");
    }
    function normalSize() {
        nSize();
        $("#zoomModal").modal("hide");
    }
    $("#noneed").on("change", function () {
        $("#zoomModal").modal("hide");
        $('#zoomModal').on('hidden.bs.modal', function () {
            $("#ZMstatus").empty();
        })
    })
        
