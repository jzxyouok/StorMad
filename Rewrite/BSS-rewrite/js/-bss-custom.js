//引入layui方法
layui.use(['layer', 'laypage', 'laydate', 'upload', 'form'], function() {
    var layer = layui.layer,
        laypage = layui.laypage,
        laydate = layui.laydate;
    form = layui.form();

    layui.upload({
        url: '' //上传接口
            ,
        success: function(res) { //上传成功后的回调
            console.log(res)
        }
    });

    var start = {
        min: laydate.now(),
        max: '2099-06-16 23:59:59',
        istoday: false,
        choose: function(datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };

    var end = {
        min: laydate.now(),
        max: '2099-06-16 23:59:59',
        istoday: false,
        choose: function(datas) {
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };

    document.getElementById('yearMonth').onclick = function() {
        start.elem = this;
        laydate(start);
    }
    document.getElementById('yearMonth').onclick = function() {
        end.elem = this
        laydate(end);
    }

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

layui.use(['laypage', 'layer'], function() {
    var laypage = layui.laypage,
        layer = layui.layer;

    laypage({
        cont: 'pageNumber',
        pages: 5,
        skin: '#2089ff'
    });

});


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


$(document).reday(function() {

})
