// Windows 8 中的 Internet Explorer 10 和 Windows Phone 8
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
        document.createTextNode(
            '@-ms-viewport{width:auto!important}'
        )
    )
    document.querySelector('head').appendChild(msViewportStyle)
}

$(document).ready(function() {

    //鼠标触发弹出子菜单
    $('li.dropdown').mouseover(function() {
        $(this).addClass('open');
    }).mouseout(function() {
        $(this).removeClass('open');
    });

    //关闭click.bs.dropdown.data-api事件，一级菜单恢复href属性
    $(document).off('click.bs.dropdown.data-api');

    // 选项卡背景切换
    /*$(".auction .nav-tabs li:nth-child(1)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "0");
        $("div#auction-record").addClass("hide");
        $(".record-list").addClass("hide");
        $(".record-show").animate({
            left: 248
        }, 0);
    });
    $(".auction .nav-tabs li:nth-child(2)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "-50px");
        $("div#auction-record").removeClass("hide");
    });
    $(".auction .nav-tabs li:nth-child(3)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "-100px");
        $("div#auction-record").addClass("hide");
        $(".record-list").addClass("hide");
        $(".record-show").animate({
            left: 248
        }, 0);
    });*/


});
