/**
 * 创建广告主JS
 */
$(function() {

    //点击提交按钮事件
    $("#btnSubmit").click(function() {
        alert($('#aderForm').serialize());

        var corpName = $('#corporation').val();
        if (corpName == '') {
            $(".form-group:nth-child(1)> .col-md-3> p").removeClass("hide");
            $('#corporation').focus();
            return;
        }

        var address = $('#address').val();
        if (address == '') {
            $(".form-group:nth-child(2)> .col-md-3> p").removeClass("hide");
            $('#address').focus();
            return;
        }

        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ader/register.jsp',
            data: $('#aderForm').serialize(),
            dataType: 'text',
            success: function(data) {
                var json = JSON.parse(data);
                alert(json);
            },
            error: function(data) {
                //alert('创建广告失败!\r\n' + data.responseText);
                layui.use('layer', function() {
                    var layer = layui.layer;
                    layer.msg('创建广告失败!\r\n' + data.responseText);
                });
            }
        });
    });

    //获取验证码
    $("#btnVCode").click(function() {
        var phone = $("#phone").val();
        $.ajax({
            type: 'GET',
            url: yonghui.contextPath + '/api/ader/getVCode.jsp',
            data: {
                'phone': phone
            },
            dataType: 'text',
            success: function(data) {
                var json = JSON.parse(data);
                if (json.errCode == 0) {
                    alert('请输入验证码');
                    $('#phone').focus();
                } else {
                    //alert('验证码发送失败，请重试！');
                    layui.use('layer', function() {
                        var layer = layui.layer;
                        layer.msg('获取验证码失败!\r\ n ');
                    });
                }
            },
            error: function(data) {
                //alert('获取验证码失败!\r\n');
                layui.use('layer', function() {
                    var layer = layui.layer;
                    layer.msg('获取验证码失败!\r\ n ');
                });
            }

        });
    });
});


form.verify({
    username: function(value) {
        if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
            return '用户名不能有特殊字符';
        }
        if (/(^\_)|(\__)|(\_+$)/.test(value)) {
            return '用户名首尾不能出现下划线\'_\'';
        }
        if (/^\d+\d+\d$/.test(value)) {
            return '用户名不能全为数字';
        }
    }

    //我们既支持上述函数式的方式，也支持下述数组的形式
    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
    ,
    pass: [
        /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
    ]
});
