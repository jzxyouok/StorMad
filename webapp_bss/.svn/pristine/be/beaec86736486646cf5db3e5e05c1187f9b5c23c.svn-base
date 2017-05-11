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

});

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


