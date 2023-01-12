function backdrop()
{
    if (!$("#modal-backdrop").hasClass("modal-backdrop")) {
        $("#modal-backdrop").addClass("modal-backdrop fade in");
    }
    else {
        $("#modal-backdrop").removeClass("modal-backdrop fade in");
    }
    
}
//FormatNumber using for format curency
function FormatNumber(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2 + " đ";
}

$(".menu-toggle").click(function (e) {
    e.preventDefault();
    //  $("#wrapper").removeClass();
    backdrop();
    $("#wrapper").toggleClass($(this).data("toggled"));
});

function fullLayout()
{
    var mobile = $("#mobileck").val();
    console.log("mobileck: " + mobile);
    if (mobile == "true")
    {
        var href = "https://thuvienphapluat.vn/redirect.aspx?mobile=true&url=" + window.location.href;
        window.location = href;
    }
    else {
        window.location = "https://thuvienphapluat.vn/redirect.aspx?mobile=true";
    }
}
//2017/06/02
//2017/06/02
$("#modal-backdrop").on("click", function () {

    $("#wrapper").toggleClass($(this).data("toggled"));
    backdrop();
});


$(document).ready(function () {
    ActiveLinkMenuRight();
});
/*
athor: rilk
date:2017.08.07
function :(menu right) active link when focus this page login
*/
function ActiveLinkMenuRight() {
    var url = window.location.href;
    $("#sidebar-wrapper-right li > .form-group > a").each(function () {
        var link = $(this).attr("href");
        if (url.toLowerCase().indexOf(link.toLowerCase()) >= 0) {
            $(this).addClass("active");
        }
    });
}
$("#btnSearchTNPL").on("click", function () {
    var text = $("#searchtnpl").val();
    window.location.href = "/tnpl/" + text;
})

$('#searchtnpl').keypress(function (e) {
    if (e.which == 13) {
        var text = $("#searchtnpl").val();
        window.location.href = "/tnpl/" + text;
    }
});