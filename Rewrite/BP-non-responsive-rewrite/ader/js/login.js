/**
 *
 */
$(function() {
    var getVCode = function(vid) {
        $.ajax({
            type: 'GET',
            url: yonghui.contextPath + '/api/createCode.jsp',
            data: {},
            dataType: 'json',
            success: function(data) {
                var imgUrl = yonghui.contextPath + '/api/showCode.jsp?id=' + data.obj;
                $('#code-pic').attr('src', imgUrl);
                $('#vid').val(data.obj);
            },
            error: function(data) {
                alert('获取验证码失敗!\r\n');
            }
        });
    }

    $('#code-pic').click(function() {
        getVCode();
    });

    $('#btnSubmit').click(function() {
        var loginName = $.trim($('#loginName').val());
        if (loginName == '') {
            $('.tips').html('<i class="exclamation-icon"></i>请输入用户名').addClass('shake');
            setTimeout(function() {
                $('.tips').removeClass('shake')
            }, 500);
            // alert('请输入用户名');
            $('#loginName').focus();
            getVCode();
            return;
        }

        var password = $.trim($('#password').val());
        if (password == '') {
            $('.tips').html('<i class="exclamation-icon"></i>请输入密码').addClass('shake');
            setTimeout(function() {
                $('.tips').removeClass('shake')
            }, 500);
            // alert('请输入密码');
            $('#password').focus();
            getVCode();
            return;
        }

        var vcode = $('#vcode').val();
        if (vcode == '') {
            $('.tips').html('<i class="exclamation-icon"></i>请输入验证码').addClass('shake');
            setTimeout(function() {
                $('.tips').removeClass('shake')
            }, 500);
            // alert('请输入验证码');
            $('#vcode').focus();
            getVCode();
            return;
        }
        var id = $('#vid').val();

        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/ader/login.jsp',
            data: { 'loginName': loginName, 'password': password, 'vid': id, 'vcode': vcode },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    location.href = yonghui.contextPath + '/index.html';
                } else if (2017 === data.errCode) {
                    $('#notPass .reason').html(data.errMsg);
                    layui.use('layer', function() {
                        var layer = layui.layer;
                        layer.open({
                            type: 1,
                            area: ['auto', 'auto'],
                            shade: 0,
                            move: false,
                            title: false,
                            shadeClose: true, //点击遮罩关闭
                            content: $('#notPass')
                        });
                    });
                } else if (2016 === data.errCode) {
                    layui.use('layer', function() {
                        var layer = layui.layer;
                        layer.open({
                            type: 1,
                            area: ['auto', 'auto'],
                            shade: 0,
                            move: false,
                            title: false,
                            shadeClose: true, //点击遮罩关闭
                            content: $('#reviewing')
                        });
                    });
                } else {
                    $('.tips').html('<i class="exclamation-icon"></i>' + data.errMsg + '').addClass('shake');
                    setTimeout(function() {
                        $('.tips').removeClass('shake')
                    }, 500);
                    getVCode();
                    // alert(data.errMsg);
                }
            },
            error: function(data) {
                alert('登录失败');
                getVCode();
            }
        });
    });
    $('body').on('keydown',function(){
         if (event.keyCode === 13) {
            $('#btnSubmit').trigger('click');
         }
    });
    $('body').on('click','#btnAlter',function(){
        window.location.href = 'ader-info-rewrite.html';
    });
    //打开页面时获取验证码
    getVCode();
});
