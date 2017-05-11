//引入layui方法
layui.use(['form', 'upload', 'layedit', 'laydate', 'laypage', 'layer'], function() {
    var form = layui.form(),
        layer = layui.layer,
        layedit = layui.layedit,
        laydate = layui.laydate;

    layui.upload({
        url: '' //上传接口
            ,
        success: function(res) { //上传成功后的回调
            console.log(res)
        }
    });

    layui.upload({
        url: '/test/upload.json',
        elem: '#test' //指定原始元素，默认直接查找class="layui-upload-file"
            ,
        method: 'get' //上传接口的http类型
            ,
        success: function(res) {
            LAY_demo_upload.src = res.url;
        }
    });

});


//判断footer的位置是否固定
$(document).ready(function() {
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

    // $('li.dropdown').mouseover(function() {
    //     $(this).addClass('open');
    // }).mouseout(function() {
    //     $(this).removeClass('open');
    // });



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


    //弹出一个页面层
    //支付提醒

    //本期竞拍结果
    $('span.result-Tips').on('click', function() {
        $("div#resultTips").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            offset: 'rb', //弹窗右下角
            offset: ['0px', '100px'],
            shade: 0,
            move: false,
            //title: false,//隐藏默认标题
            title: ['本期竞拍结果', 'font-size:12px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#resultTips')
        });
    });


    //广告预览
    $('span.ad-Preview').on('click', function() {
        $("div#adPreview").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            //title: ['该档期投放门店', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            closeBtn: 0,
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#adPreview')
        });
    });


    //档期投放门店列表
    $('span.store-List').on('click', function() {
        $("div#storeList").removeClass("hide");
        layer.open({
            type: 1,
            area: ['340px', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            //title: ['该档期投放门店', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            closeBtn: 0,
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#storeList')
        });
    });


    //推广计划管理
    $('a.edit-Plan').on('click', function() {
        $("div#editPlan").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['推广计划管理', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            content: $('#editPlan')
        });
    });


    //推广组编辑
    $('a.edit-Group').on('click', function() {
        $("div#editGroup").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['推广组编辑', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            content: $('#editGroup')
        });
    });


    //广告绑定
    $('button.ad-Binding').on('click', function() {
        $("div#adBinding").removeClass("hide");
        layer.open({
            type: 1,
            area: ['900px', '420px'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['广告绑定', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            content: $('#adBinding')
        });
    });


    //广告信息已提交审核
    $('button.submit-Review').on('click', function() {
        $("div#submitReview").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['提示', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            content: $('#submitReview')
        });
    });


    //广告预览
    $('a.ad-Text-Pic').on('click', function() {
        $("div#adTextPic").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            // title: ['广告预览', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            closeBtn: 0,
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#adTextPic')
        });
    });


    //扫码支付
    $('button.we-Chat-Pay').on('click', function() {
        $("div#weChatPay").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['请扫码支付', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#weChatPay')
        });
    });


    //支付结果
    $('button.pay-Failed').on('click', function() {
        $("div#payFailed").removeClass("hide");
        layer.open({
            type: 1,
            area: ['580px', '300px'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['支付结果', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#payFailed')
        });
    });


    //新增地址
    $('button.add-Address').on('click', function() {
        $("div#shippingAddressEdit").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#shippingAddressEdit')
        });
    });


    //编辑地址
    $('a.shipping-Address-Edit').on('click', function() {
        $("div#shippingAddressEdit").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#shippingAddressEdit')
        });
    });


    //删除操作
    $('a.shipping-Address-Delete').on('click', function() {
        $("div#shippingAddressDelete").removeClass("hide");
        layer.open({
            type: 1,
            area: ['580px', '300px'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['删除操作', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#shippingAddressDelete')
        });
    });


    //支付结果
    $('button.apply-Tips').on('click', function() {
        $("div#applyTips").removeClass("hide");
        layer.open({
            type: 1,
            area: ['580px', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['提示', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#applyTips')
        });
    });


    //发票申请审核状态 - 审核通过
    $('a.invoice-Check').on('click', function() {
        $("div#invoiceCheck").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#invoiceCheck')
        });
    });


    //发票申请审核状态 - 审核通过
    $('a.invoice-Check-no').on('click', function() {
        $("div#invoiceCheck-no").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#invoiceCheck-no')
        });
    });


    //发票申请审核状态 - 审核通过
    $('a.invoice-Check-ing').on('click', function() {
        $("div#invoiceCheck-ing").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#invoiceCheck-ing')
        });
    });


    //发票申请撤销操作
    $('a.invoice-Cancel').on('click', function() {
        $("div#invoiceCancel").removeClass("hide");
        layer.open({
            type: 1,
            area: ['580px', '300px'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['撤销操作', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#invoiceCancel')
        });
    });


    //广告投放明细
    $('button.data-Detail').on('click', function() {
        $("div#dataDetail").removeClass("hide");
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            //offset: 'rb', //弹窗右下角
            title: false, //隐藏默认标题
            title: ['广告投放明细', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true, //点击遮罩关闭
            //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
            content: $('#dataDetail')
        });
    });










})
