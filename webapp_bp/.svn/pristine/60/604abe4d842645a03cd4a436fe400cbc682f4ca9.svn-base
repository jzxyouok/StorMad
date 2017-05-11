$(function() {

    //初始化
    var pageNo = 1;
    var yearMonth = "";
    var type = "";

    list(pageNo, yearMonth, type);

    //表达验证与提交
    layui.use('form', function() {
        var form = layui.form();
        form.render('select');

        //搜索表单提交
        form.on('submit(serach)', function(data) {
            var val = data.field;

            list(1, val.yearMonth, val.type)

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
    exportData();
});

function list(pageNo, yearMonth, type) {
    //分页模块
    layui.use('laypage', function() {
        var laypage = layui.laypage

        var req = { pageNo: pageNo, pageSize: yonghui.pageSize, yearMonth: yearMonth, type: type };

        //初始化分页参数
        $.post(yonghui.contextPath + "/api/invoice/queryDeposit.jsp", req, function(data) {

            if (data.errCode == 0) {
                val = data.obj.page;
                $("#balance").text('￥' + ((data.obj.balance) / 100).toFixed(2));

                laypage({
                    cont: 'pageNumber',
                    groups: yonghui.groups,
                    pages: val.pageCount,
                    skip: true,
                    jump: function(obj, first) {
                        if (first) {
                            get_info(val);
                        } else {
                            query(obj.curr);
                        }
                    }
                });
            } else {
                layer.alert(data.errMsg)
            }
        });
    });
}

/*  分页查询数据*/
function get_info(data) {
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        var val = data.record;

        var htm = "";

        if (val.length > 0) {
            for (var i = 0; i < val.length; i++) {
                htm += '<tr>';

                htm += '<td>' + laydate.now(val[i].crtTime, "YYYY-MM-DD hh:mm:ss") + '</td><td>' + val[i].typeCN + '</td><td>￥' + ((val[i].money) / 100).toFixed(2) + '</td>';


                htm += '</tr>';
            }

            $("#account_info").html(htm);

            $(".no_info").hide();
            $(".show_info").show();
        } else {
            $(".no_info").show();
            $(".show_info").hide();
        }
    });
}

function query(pageNo, yearMonth, type) {
    var curyearMonth = $('.btn-yearMonth').val();
    var curtype = $('.btn-type .layui-this').attr('lay-value');

    var req = { pageNo: pageNo, pageSize: yonghui.pageSize, yearMonth: curyearMonth, type: curtype };

    $.post(yonghui.contextPath + "/api/invoice/queryDeposit.jsp", req, function(data) {
        if (data.errCode == 0) {
            get_info(data.obj.page);
        } else {
            layer.alert('查询列表失败!\r\n' + data.errMsg);
        }
    });
};

/*导出充值记录*/
function exportData() {
    $('.btn-export').click(function() {
        var expyearMonth = $('.btn-yearMonth').val();
        var exptype = $('.btn-type .layui-this').attr('lay-value');
        window.location.href = yonghui.contextPath + '/api/money/exportDeposit.jsp?yearMonth=' + expyearMonth + '&type=' + exptype;
   });
}
