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


