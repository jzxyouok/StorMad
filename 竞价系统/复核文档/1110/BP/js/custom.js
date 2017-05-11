$(document).ready(function() {

    //鼠标触发弹出子菜单
    $('li.dropdown').mouseover(function() {
        $(this).addClass('open');
    }).mouseout(function() {
        $(this).removeClass('open');
    });


    //判断footer的位置是否固定
    var windowH = $(window).height();
    var bodyH = $(document.body).height();
    if (windowH > bodyH) {
        $("footer#footFixed").addClass("footer-fixed");
    } else {
        $("footer#footFixed").removeClass("footer-fixed");
    };

    //              alert($(window).height()); //浏览器时下窗口可视区域高度   
    //              alert($(document).height()); //浏览器时下窗口文档的高度   
    //              alert($(document.body).height()); //浏览器时下窗口文档body的高度   
    //              alert($(document.body).outerHeight(true)); //浏览器时下窗口文档body的总高度 包括border padding margin   
    //              alert($(window).width()); //浏览器时下窗口可视区域宽度   
    //              alert($(document).width()); //浏览器时下窗口文档对于象宽度   
    //              alert($(document.body).width()); //浏览器时下窗口文档body的高度   
    //              alert($(document.body).outerWidth(true)); //浏览器时下窗口文档body的总宽度 包括border padding margin   
    //          })
    //
    //          $(document).ready(function() {


    //选项卡背景切换
    $(".auction .nav-tabs li:nth-child(1)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "0");
        $("div#auction-record").addClass("hide");
    });
    $(".auction .nav-tabs li:nth-child(2)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "-50px");
        $("div#auction-record").removeClass("hide");
    });
    $(".auction .nav-tabs li:nth-child(3)").click(function() {
        $(".auction .nav-tabs").css("background-position-y", "-100px");
        $("div#auction-record").addClass("hide");
    });

    // 竞拍行业选择
    $('.industry > button').each(function(index, item) {
        $(this).click(function() {

            if (index == 0) {
                $("#industry-Type").html('洗护');
            } else if (index == 1) {
                $("#industry-Type").html('化妆品');
            } else if (index == 2) {
                $("#industry-Type").html('11');
            } else if (index == 3) {
                $("#industry-Type").html('22');
            } else if (index == 4) {
                $("#industry-Type").html('33');
            } else if (index == 5) {
                $("#industry-Type").html('44');
            } else if (index == 6) {
                $("#industry-Type").html('55');
            } else if (index == 7) {
                $("#industry-Type").html('66');
            } else if (index == 8) {
                $("#industry-Type").html('77');
            } else if (index == 9) {
                $("#industry-Type").html('88');
            } else if (index == 10) {
                $("#industry-Type").html('99');
            } else if (index == 11) {
                $("#industry-Type").html('00');
            } else if (index == 12) {
                $("#industry-Type").html('011');
            } else if (index == 13) {
                $("#industry-Type").html('022');
            } else if (index == 14) {
                $("#industry-Type").html('033');
            } else if (index == 15) {
                $("#industry-Type").html('044');
            }
        })
    })

    //列表全选
    var checkAll = $("#check_all");
    var checkList = $("#check_list").find("input[type=checkbox]");
    checkAll.click(function() {
        if ($(this).is(":checked")) {
            checkList.each(function() {
                $(this).prop("checked", true);
            })
        } else {
            checkList.each(function() {
                $(this).prop("checked", false);
            })
        }
    })

    




})
