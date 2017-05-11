/**
 *
 */
$(function() {
    var queryGroups = function(pageNo) {
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/findSpreadGroupPage.jsp',
            data: { 'pageNo': pageNo, 'pageSize': yonghui.pageSize },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    fillTable(data.obj);
                } else {
                    layer.alert('查询推广组失败!\r\n' + data.errMsg);
                }
            },
            error: function(data) {
                layer.alert('查询推广组失败!\r\n' + data.errMsg);
            }
        });
    };
    //获取链接参数
    var getQueryString = function(name) {     
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");     
        var r = window.location.search.substr(1).match(reg);     
        if (r != null) return  unescape(r[2]);
        return null;
    };
    //填充表格
    var fillTable = function(page) {
        var tbl = '';
        var statusCN = '';
        var list = page.record;

        var spreadPlan = '';
        $('#tblGroup tbody').html('');
        for (var i = 0; i < list.length; i++) {
            if (list[i].spreadPlan == null) {
                spreadPlan = '';
            } else {
                spreadPlan = list[i].spreadPlan.spName;
            }

            tbl += '<tr data-sgId="' + list[i].sgId + '">';
            tbl += '<td><div class="checkbox"><label><span class="checkbox">';
            tbl += '<input name="sgId" id="' + list[i].sgId + '" value="' + list[i].sgId + '" type="checkbox"><i></i>';
            tbl += '</span></label></div></td>';
            tbl += '<td><a href="javascript:toAdList(\'' + list[i].sgId + '\')">' + list[i].sgName + '</a></td>';
            /*tbl += '<td>' + list[i].pv + '</td>';
            tbl += '<td>' + list[i].click + '</td>';
            tbl += '<td>' + list[i].ctr + '</td>';*/
            tbl += '<td>' + spreadPlan + '</td>';
            tbl += '<td>' + list[i].adCount + '</td>';
            statusCN = '推广中';
            if (list[i].sgStatus == 0) {
                statusCN = '未参与';
            }
            tbl += '<td>' + statusCN + '</td>';
            tbl += '<td><a class="edit-Plan" href="javascript:editGroup(\'' + list[i].sgId + '\', \'' + list[i].sgName + '\');">编辑</a>';
            tbl += '<a class="plan-Delete" href="javascript:delGroup(\'' + list[i].sgId + '\');">删除</a></td>';
            tbl += '</tr>';
        }
        $("#tblGroup tbody").html(tbl);
    };

    layui.use(['laypage', 'layer'], function() {
        var laypage = layui.laypage,
            layer = layui.layer;
        var page = null;
        var spId = getQueryString('spId');

        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/findSpreadGroupPage.jsp',
            data: { 'pageNo': 1, 'pageSize': yonghui.pageSize, 'spId': spId },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    page = data.obj;
                    laypage({
                        cont: 'pageNumber',
                        groups: yonghui.groups,
                        pages: page.pageCount,
                        skip: true,
                        jump: function(obj, first) {
                            if (first) {
                                fillTable(page);
                            } else {
                                queryGroups(obj.curr);
                            }
                        }
                    });
                } else {
                    layer.alert('查询推广组失败\r\n' + data.errMsg);
                }
            },
            error: function(data) {
                layer.alert('查询推广组失败!\r\n' + data.errMsg);
            }
        });
    });

    //查询推广计划
    var queryPlans = function(pageNo, pageSize) {
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/findSpreadPlanPage.jsp',
            data: { 'pageNo': pageNo, 'pageSize': pageSize },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    fillPlans(data.obj.record);
                } else {
                    layer.alert('查询推广计划失败!\r\n' + data.errMsg);
                }
            },
            error: function(data) {
                layer.alert('查询推广计划失败!\r\n' + data.errMsg);
            }
        });
    };

    //填充推广计划下拉框
    var fillPlans = function(list) {
        var options = '';
        if (list != null) {
            for (var i = 0; i < list.length; i++) {
                options += '<option value=\'' + list[i].spId + '\'>' + list[i].spName + '</option>';
            }
            $('#plans').append(options);
        }
    }

    //完成元素的渲染和事件的监听
    layui.use('form', function() {
        var form = layui.form();

        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/findSpreadPlanPage.jsp',
            data: { 'pageNo': 1, 'pageSize': 100 },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    var list = data.obj.record;
                    var options = '';
                    for (var i = 0; i < list.length; i++) {
                        options += '<option value=\'' + list[i].spId + '\'>' + list[i].spName + '</option>';
                    }
                    $('#plans').append(options);
                    form.render('select');
                } else {
                    layer.alert('查询推广计划失败!\r\n' + data.errMsg);
                }
            },
            error: function(data) {
                layer.alert('查询推广计划失败!\r\n' + data.errMsg);
            }
        });
    });

    //添加推广组
    $("#btnSave").click(function() {
        var groupId = $('#groupId').val();
        if (groupId == '') {
            addGroup();
        } else {
            updateGroup();
        }
    });

    //新增推广组
    var addGroup = function() {
        var groupName = $('#groupName').val();
        if (groupName == '') {
            layer.alert('请输入推广组名称');
            $('#groupName').focus();
            return;
        }
        var planId = $('#plans').val();
        if (planId == -1) {
            layer.alert('请选择推广计划');
        } else {

            $.ajax({
                type: 'POST',
                url: yonghui.contextPath + '/api/ad/spread/addSpreadGroup.jsp',
                data: { 'sgName': groupName, 'spId': planId },
                dataType: 'json',
                success: function(data) {
                    if (data.errCode == -10000) {
                        layer.alert('你尚未登录系统，不能操作');
                        return;
                    }
                    if (data.errCode != 0) {
                        layer.alert('新增推广组失败，错误原因[' + data.errMsg + ']');
                        return;
                    }
                    layer.msg('新增推广组成功');
                    layer.closeAll('page');
                    queryGroups(1);
                },
                error: function(data) {
                    layer.alert('新增推广组失败!\r\n' + data.errMsg);
                }
            });
        }
    };

    //更新推广组
    var updateGroup = function() {
        var groupName = $('#groupName').val();
        var groupId = $('#groupId').val();

        if (groupName == '') {
            layer.alert('请输入推广组名称');
            $('#groupName').focus();
            return;
        }
        if (groupId == '') {
            layer.alert('未指定修改推广组的ID');
            return;
        }

        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/updateSpreadGroup.jsp',
            data: { 'sgName': groupName, 'sgId': groupId },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == -10000) {
                    layer.alert('你尚未登录系统，不能操作');
                    return;
                }
                if (data.errCode != 0) {
                    layer.alert(data.errMsg);
                    return;
                }
                $('#groupId').val('');
                layer.msg('更新推广组成功');
                layer.closeAll('page');
                queryGroups(1);
            },
            error: function(data) {
                layer.alert('编辑推广组失败!\r\n' + data.errMsg);
            }
        });
    };

    //暂停推广组
    $('#stop_group').click(function() {
        var sgIds = '';
        $('input[name="sgId"]:checked').each(function() {
            // if ($(this).get(0).checked) {
            sgIds += ($(this).attr('value') + ',');
            // }
        });

        if (sgIds == '') {
            layer.msg('请选中一个推广组暂停');
            return;
        }
        updateGroupStatus(sgIds.substr(0, sgIds.length - 1), 0);
    });

    //参与推广组
    $('#join_group').click(function() {
        var sgIds = '';
        $('input[name="sgId"]:checked').each(function(n, item) {
            // if ($(this).get(0).checked) {
            sgIds += ($(this).attr('value') + ',');
            // }
        });

        if (sgIds == '') {
            layer.msg('请选中一个组参与推广');
            return;
        }

        updateGroupStatus(sgIds.substr(0, sgIds.length - 1), 1);
    });

    //执行推广组状态更新
    var updateGroupStatus = function(groupIds, status) {
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/updateSgStatus.jsp',
            data: { 'sgStatus': status, 'sgIds': groupIds },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == -10000) {
                    layer.alert('你尚未登录系统，不能操作');
                    return;
                } else {
                    layer.alert(data.errMsg, function(index) {
                        $('#check_all').removeAttr('checked');
                        queryGroups(1);
                        layer.close(index);
                    });
                    return;
                }
                /*if (data.errCode != 0) {
                    layer.alert(data.errMsg);
                    return;
                }
                layer.alert("更新推广组状态成功");
                layer.closeAll('page');
                queryGroups(1);*/
            },
            error: function(data) {
                layer.alert('编辑推广组失败!\r\n' + data.errMsg);
            }
        });
    };


});

//弹出新增和修改广告组窗口
var editGroup = function(id, name) {
    $('#groupName').val(name);
    $('#groupId').val(id);

    layer.open({
        type: 1,
        area: ['auto', 'auto'],
        title: ['推广组编辑', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#editGroup')
    });

    //完成元素的渲染和事件的监听
    layui.use('form', function() {
        var form = layui.form();

        //如果是编辑，屏蔽推广计划
        if (id != '') {
            $('.layui-form').prev().css('display', 'none');
            $('.layui-form').css('display', 'none');
            form.render('');
        } else {
            $('.layui-form').prev().css('display', 'block');
            $('.layui-form').css('display', 'block');
            form.render('');
        }
    });
};

//删除广告组
var delGroup = function(id) {
    var cancelPop = layer.open({
        type: 1,
        area: ['auto', 'auto'],
        //offset: 'rb', //弹窗右下角
        title: false, //隐藏默认标题
        title: ['删除广告组', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#groupCancel')
    });
    /*取消删除*/
    $('#btn-cancel').click(function() {
        parent.layer.close(cancelPop);
    });
    /*确定删除*/
    $('#btn-submit').click(function() {
        parent.layer.close(cancelPop);
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ad/spread/deleteSpreadGroup.jsp',
            data: { 'sgId': id },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    layer.alert("删除推广组成功");
                    layer.closeAll('page');
                    $('tr[data-sgid="' + id + '"]').remove();
                    // queryGroups(1);
                } else if (data.errCode == -10000) {
                    layer.alert('你尚未登录系统，不能操作');
                    return;
                }
                // else {
                //     layer.alert(data.errMg);
                // }

            },
            error: function(data) {
                layer.alert(data.errMsg);
            }
        });
    });
};

//跳转到广告列表
var toAdList = function(id) {
    location.href = 'ad-list.html?sgId=' + id
}

// 全选
$('#tblGroup').on('click', '#check_all', function() {
    if ($(this).is(':checked')) {
        $('#check_list input[type="checkbox"]').prop('checked', true);
    } else {
        $('#check_list input[type="checkbox"]').removeAttr('checked');
    }
});
