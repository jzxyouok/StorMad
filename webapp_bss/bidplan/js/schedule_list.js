/**
 * 
 */

$(function() {

    //初始化全局变量
    var pageNo = 1;
    var bpName = '';
    /*var year = '';
    var month = '';*/
    var yearMonth = '';
    var repeatType = '';
    var status = '';

    //初始化数据
    list(pageNo, bpName, yearMonth, repeatType, status);

    //表达验证与提交
    layui.use('form', function() {
        var form = layui.form();
        form.render('select');

        //搜索表单提交
        form.on('submit(serach)', function(data) {
            var val = data.field;
            /*var year = 0;
            var month = 0;*/
            var yearMonth = '';

            //格式化时间
            /*if (val.date != '') {
                var date = val.date.split("-");
                year = date[0];
                month = date[1];
            }*/

            list(1, val.bpName, val.yearMonth, val.repeatType, val.status)

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });

});

//关闭窗口
$("#back_list").on('click', function() {
    layer.closeAll('page');
});

//更新档期状态
function set_status(status, id) {
    url = yonghui.contextPath + '/api/bidplan/start.jsp';
    console.log(status);
    $.post(url, { bpId: id, status: status }, function(data) {
        if (data.errCode == 0) {
            layer.msg('操作成功');
            if (status == 10) {
                console.log('11');
                $("#start_" + id).removeAttr('onclick');
                $("#start_" + id).addClass('active-status');
                $('#stop_' + id).attr('onclick', 'set_status(0,' + id + ')');
                $('#stop_' + id).removeClass('active-status');
            } else {
                console.log('22');
                $("#stop_" + id).removeAttr('onclick');
                $("#stop_" + id).addClass('active-status');
                $('#start_' + id).attr('onclick', 'set_status(10,' + id + ')');
                $('#start_' + id).removeClass('active-status');
            }
        } else {
            layer.alert(data.errMsg);
        }
    })
}
/*function set_status(status, id) {
    url = yonghui.contextPath + '/api/bidplan/start.jsp';

    $.post(url, { bpId: id, status: status }, function(data) {
        if (data.errCode == 0) {
            layer.msg('操作成功');
            // 
            if (status == 1) {
                $("#start_" + id).removeAttr('onclick');
                $("#start_" + id).addClass('active-status');
                $('#stop_' + id).attr('onclick', 'set_status(0,' + id + ')');
                $('#stop_' + id).removeAttr('active-status');
            } else {
                $("#stop_" + id).removeAttr('onclick');
                $("#stop_" + id).addClass('active-status');
                $('#start_' + id).attr('onclick', 'set_status(1,' + id + ')');
                $('#start_' + id).removeAttr('active-status');
            }
        } else {
            layer.alert(data.errMsg);
        }
    })
}*/

//获取列表
function list(pageNo, bpName, yearMonth, repeatType, status) {
    //分页模块
    layui.use('laypage', function() {
        var laypage = layui.laypage,
            layer = layui.layer;

        var redata = { pageNo: pageNo, pageSize: yonghui.pageSize, bpName: bpName, yearMonth: yearMonth, repeatType: repeatType, status: status };

        $.post(yonghui.contextPath + "/api/bidplan/query.jsp", redata, function(data) {
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
                        query(obj.curr, bpName, yearMonth, repeatType, status);
                    }
                }
            });
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
                /*时间戳格式转换*/
                var createTime = new Date(Number(val[i].createTime));
                var startDate = new Date(Number(val[i].startDate));
                var endDate = new Date(Number(val[i].endDate));
                var startTime = Number(val[i].startTime) / 1000 / 3600;
                var endTime = Number(val[i].endTime) / 1000 / 3600;

                createTime = createTime.format('YYYY-MM-dd hh:mm:ss');
                startDate = startDate.format('YYYY-MM-dd');
                endDate = endDate.format('YYYY-MM-dd');

                //格式化投放时间
                if (val[i].startTime == 0 && val[i].endTime == 86399999) {
                    var cTime = "全时间段";
                } else {
                    var cTime = startTime + '时&nbsp;至&nbsp;' + endTime + '时';
                }

                htm += '<tr>';

                htm += '<td>' + val[i].bpId + '</td><td>' + val[i].bpName + '</td>';
                htm += '<td>' + createTime + '</td>';
                htm += '<td>' + startDate + '至' + endDate + '&nbsp;&nbsp;&nbsp;&nbsp;' + cTime + '</td><td>' + val[i].repeatTypeCN + '</td>';
                htm += '<td>￥' + ((val[i].cbasePrice) / 100).toFixed(2) + '</td><td id="sc_status_' + val[i].bpId + '">' + val[i].statusCN  + '</td>';

                //  STOP(0 , "停用"),  //启动，编辑
                //  USING(10 , "启用"),   //停用，编辑
                //  NO_START(20, "未开始"),   //启动，停用，编辑
                //  BIDING(30, "竞拍中"),   //---启动
                //  EXPIRE(40, "已失效");   //-----

                //初始化状态
                var startcss = '';
                var startact = '';
                var stopcss = '';
                var stopact = '';
                var editact = '';
                var editcss = '';

                //判断档期状态
                /*if (val[i].status == 0) {
                    startcss = '';
                    startact = 'onclick="set_status(10,' + val[i].bpId + ')"';
                    stopcss = 'active-status';
                    stopact = '';
                    editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                    editcss = '';
                }
                if (val[i].status == 10) {
                    stopcss = '';
                    stopact = 'onclick="set_status(0,' + val[i].bpId + ')"';
                    startcss = 'active-status';
                    startact = '';
                    editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                    editcss = '';
                }
                if (val[i].status == 20) {
                    stopact = 'onclick="set_status(0,' + val[i].bpId + ')"';
                    stopcss = '';
                    startact = '';
                    startcss = 'active-status';
                    editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                    editcss = '';
                }
                if (val[i].status == 30) {
                    stopact = '';
                    stopcss = 'active-status';
                    startact = '';
                    startcss = 'active-status';
                    editact = '';
                    editcss = 'active-status';
                }
                if (val[i].status == 40) {
                    stopact = '';
                    stopcss = 'active-status';
                    startact = '';
                    startcss = 'active-status';
                    editact = '';
                    editcss = 'active-status';
                }*/
                if (val[i].status == 0) {
                    // 停用未开始，可开启编辑
                    if (val[i].status == 20) {
                        console.log('停用未开始');
                        stopcss = 'active-status';
                        stopact = '';
                        startcss = '';
                        startact = 'onclick="set_status(10,' + val[i].bpId + ')"';
                        editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                        editcss = '';
                    }
                    // 停用竞拍中，可开启编辑
                    if(val[i].bidStatus == 30){
                        console.log('停用竞拍中');
                        stopcss = 'active-status';
                        stopact = '';
                        startcss = '';
                        startact = 'onclick="set_status(10,' + val[i].bpId + ')"';
                        editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                        editcss = '';
                    }
                }
                if (val[i].status == 10) {
                    // 开启未开始，可停用编辑
                    console.log(val[i].bidStatus);
                    if (val[i].bidStatus == 20) {
                        console.log('未开始');
                        stopcss = '';
                        stopact = 'onclick="set_status(0,' + val[i].bpId + ')"';
                        startcss = 'active-status';
                        // startact = 'onclick="set_status(10,' + val[i].bpId + ')"';
                        startact = '';
                        editact = 'onclick="edit_schedule(' + val[i].bpId + ')"';
                        editcss = '';
                    }
                    // 开启竞拍中，不可开启停用和编辑
                    if (val[i].bidStatus == 30) {
                        console.log('竞拍中');
                        stopcss = 'active-status';
                        stopact = '';
                        startcss = 'active-status';
                        startact = '';
                        editact = '';
                        editcss = 'active-status';
                    }
                }
                // 已失效，不可开启停用和编辑
                if (val[i].status == 40) {
                    console.log('已失效');
                    stopact = '';
                    stopcss = 'active-status';
                    startact = '';
                    startcss = 'active-status';
                    editact = '';
                    editcss = 'active-status';
                }

                htm += '<td><a id="start_' + val[i].bpId + '" class="' + startcss + '" href="javascript:void(0)" ' + startact + ' >启用</a> | <a id="stop_' + val[i].bpId + '" class="' + stopcss + '"  href="javascript:void(0)" ' + stopact + ' >停用</a> | <a href="javascript:void(0)" id="edit_' + val[i].bpId + '" class="' + editcss + '" ' + editact + ' >编辑</a></td>';

                htm += '</tr>';
            }

            $("#sechedule_list").html(htm);
            $(".no_info").hide();
            $(".show_info").show();
        } else {
            $("#sechedule_list").html('');
            $(".no_info").show();
            $(".show_info").hide();
        }
    });
}

function query(pageNo, bpName, yearMonth, repeatType, status) {
    var curbpName = $('.btn-bpName').val();
    var curyearMonth = $('.btn-date').val();
    /*var curdate = ($('.btn-date').val()).split("-");
    curyear = curdate[0];
    curmonth = curdate[1];*/
    var currepeatType = $('.btn-repeatType .layui-this').attr('lay-value');
    var curstatus = $('.btn-status .layui-this').attr('lay-value');

    var redata = { pageNo: pageNo, pageSize: yonghui.pageSize, bpName: curbpName, yearMonth: curyearMonth, repeatType: currepeatType, status: curstatus };

    $.post(yonghui.contextPath + "/api/bidplan/query.jsp", redata, function(data) {
        if (data.errCode == 0) {
            get_info(data.obj);
        } else {
            layer.alert('查询列表失败!\r\n' + data.errMsg);
        }
    });
};

//编辑传递
function edit_schedule(id) {
    window.location.href = 'creat-schedule.html?schedule_id=' + id;
}

//格式化时间戳
function getLocalTime(nS) {
    return new Date(parseInt(nS)).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ").replace(/下午/g, " ").replace(/上午/g, " ");
}

function formatDate(time) {
    var date = new Date(time);
    return [date.getFullYear(), date.getMonth() + 1, date.getDate()].join('-');
}
