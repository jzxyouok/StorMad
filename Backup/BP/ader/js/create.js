/**
 * 创建广告主JS
 */
$(function() {

    //点击提交按钮事件
    $("#btnSubmit").click(function() {
        alert($('#aderForm').serialize());

        var corpName = $('#corporation').val(),
            if (corpName == '') {
                $(".form-group:nth-child(1)> .col-md-3> p").removeClass("hide");
                $('#corporation').focus();
                return;
            }

        // var address = $('#address').val();
        // if (address == '') {
        //     $(".form-group:nth-child(2)> .col-md-3> p").removeClass("hide");
        //     $('#address').focus();
        //     return;
        // }

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
