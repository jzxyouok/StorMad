//引入layui方法
layui.use(['form', 'upload', 'layer', 'laypage', 'layedit', 'laydate'], function() {
    var form = layui.form(),
        layer = layui.layer,
        laypage = layui.laypage,
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

    // laypage({
    //     cont: 'pageNumber',
    //     pages: 5,
    //     skin: '#e6614f',
    // });

    // laypage({
    //     cont: 'result-pageNumber',
    //     pages: 5,
    //     skin: '#e6614f'
    // });

});



//审核不通过
$('button.b01').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['审核不通过', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#notPass')
    });
});

//审核中
$('button.b02').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['审核中', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#reviewing')
    });
});


//广告位预览
$('span.ad-Preview').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['广告位预览', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#adPreview')
    });
});

//档期投放门店列表
$('span.store-List').on('click', function() {
    layer.open({
        type: 1,
        area: ['340px', 'auto'],
        title: ['该档期投放门店', 'color:#e6614f;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#storeList')
    });
});

//竞拍结果
$('button.pay-Tips').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['竞拍结果', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#payTips')
    });
});

//本期竞拍结果
$('span.result-Tips').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        offset: 'rb', //弹窗右下角
        shade: 0,
        move: false,
        title: ['本期竞拍结果', 'font-size:12px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#resultTips')
    });

});


//推广计划管理
// $('button.edit-Plan, a.edit-Plan').on('click', function() {
//     layer.open({
//         type: 1,
//         area: ['auto', 'auto'],
//         title: ['推广计划管理', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
//         shadeClose: true, //点击遮罩关闭
//         content: $('#editPlan')
//     });
// });

//推广组编辑
// $('button.edit-Group, a.edit-Group').on('click', function() {
//     layer.open({
//         type: 1,
//         area: ['auto', 'auto'],
//         title: ['推广组编辑', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
//         shadeClose: true, //点击遮罩关闭
//         content: $('#editGroup')
//     });
// });

//广告信息已提交审核
$('button.submit-Review').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['提示', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#submitReview')
    });
});

//广告预览
$('a.ad-Text-Pic').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: false, //隐藏默认标题
        shadeClose: true, //点击遮罩关闭
        closeBtn: 0,
        content: $('#adTextPic')
    });
});

//新增地址
$('button.add-Address').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#shippingAddressEdit')
    });
});

//删除操作
$('a.shipping-Address-Delete').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['删除操作', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#shippingAddressDelete')
    });
});

//编辑地址
$('a.shipping-Address-Edit').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#shippingAddressEdit')
    });
});

//发票申请审核状态 - 审核通过
$('a.invoice-Check').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#invoiceCheck')
    });
});

//发票申请审核状态 - 审核不通过
$('a.invoice-Check-no').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#invoiceCheck-no')
    });
});


//发票申请审核状态 - 审核通过
$('a.invoice-Check-ing').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['发票申请审核状态', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#invoiceCheck-ing')
    });
});

//发票申请撤销操作
$('a.invoice-Cancel').on('click', function() {
    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['撤销操作', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#invoiceCancel')
    });
});

//广告投放明细
$('button.data-Detail').on('click', function() {
    layer.open({
        type: 1,
        area: ['900px', 'auto'],
        title: ['广告投放明细', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#dataDetail')
    });
});
