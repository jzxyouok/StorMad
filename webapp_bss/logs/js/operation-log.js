$(function() {

    //初始化
    var pageNo = 1;
    var content = "";
    var opTime = "";

    list(pageNo, content, opTime);

    //表达验证与提交
    layui.use('form', function() {
        var form = layui.form();

        //搜索表单提交
        form.on('submit(search)', function(data) {
            var val = data.field;

            var opTime = "";

            list(1, val.content, val.opTime)

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
});

function list(pageNo, content, opTime) {
    //分页模块
    layui.use('laypage', function() {
        var laypage = layui.laypage

        var req = { pageNo: pageNo, pageSize: yonghui.pageSize, content: content, opTime: opTime };
;
console.log($('.layui-input').val())
console.log(req);
        //初始化分页参数
        $.post(yonghui.contextPath + "/api/log/findBssOpLogPage.jsp", req, function(data) {

            if (data.errCode == 0) {

                val = data.obj;
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
            } else if (data.errCode == -10000) {
                layer.open({
                    skin: 'loginTip',
                    content: '您尚未登录系统，不能进行操作！',
                    btn: '我知道了',
                    yes: function(index, layero) {
                        window.location.href = '../../ader/login.html';
                    },
                    cancel: function() {
                        window.location.href = '../../ader/login.html';
                    }
                })
            } else {
                layer.alert(data.errMsg)
            }
        });
    });
}


function get_info(data) {
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        var val = data.record;

        var htm = "";

        if (val.length > 0) {

            for (var i = 0; i < val.length; i++) {
                /*时间戳格式转换*/
                var myOpTime = new Date(Number(val[i].opTime));

                myOpTime = myOpTime.format('yyyy年MM月dd日 hh:mm:ss');

                htm += '<tr>';

                htm += '<td class="optime">' + myOpTime + '</td><td>' + val[i].content + '</td><td>' + val[i].adminName + '</td>';

                htm += '</tr>';
            }

            $("#log_info").html(htm);

            $(".no_info").hide();
            $(".show_info").show();
        } else {
            $(".no_info").show();
            $(".show_info").hide();
        }
    });
}


function query(pageNo, opTime, content) {
    var curopTime=$('.btn-opTime').val();
    var curContent=$('.btn-content').val();
    var req = { pageNo: pageNo, pageSize: yonghui.pageSize, opTime: curopTime, content: curContent };

    $.post(yonghui.contextPath + "/api/log/findBssOpLogPage.jsp", req, function(data) {
        if (data.errCode == 0) {
            get_info(data.obj);
        } else {
            layer.alert('查询列表失败!\r\n' + data.errMsg);
        }
    });
};
