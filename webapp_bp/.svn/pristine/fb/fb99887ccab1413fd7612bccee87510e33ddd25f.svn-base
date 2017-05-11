/**
 *
 */
var yonghui = {
    contextPath: 'https://superip.yonghui.cn', //域名
    pageSize: 10, //每页数据数量
    groups: 5, //显示页码数量
    maxPageSize: 100000000 //默认最大数据量
}

//格式化时间函数
Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}

//关闭click.bs.dropdown.data-api事件，一级菜单恢复href属性
$(document).ready(function() {
    $(document).off('click.bs.dropdown.data-api');
});

//退出系统
var logout = function() {
    layer.alert('确认退出系统？', function() {
        $.ajax({
            type: 'POST',
            url: '/api/ader/logout.jsp',
            data: {},
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
                location.href = yonghui.contextPath + '/ader/login.html';
            },
            error: function(data) {
                layer.alert(data.errMsg);
            }
        });
    });
};

/*上传头像和用户名*/

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        // 解决ie8不兼容trim的问题
        if (!String.prototype.trim) {
            String.prototype.trim = function() {
                return this.replace(/^\s+|\s+$/g, '');
            };
        }
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function getLoginInfo() {
    var allcookies = document.cookie;
    var bp_login_name = getCookie('bp_login_name');
    var bp_logo_url = getCookie('bp_logo_url');
    $('.navbar .navbar-right .dropdown').before('<li class="" style="color:#fff;height:80px;font-size:12px; line-height:80px;display:block;">欢迎&nbsp;&nbsp;' + decodeURIComponent(bp_login_name) + '&nbsp;&nbsp;' + '</li>');
    if (bp_logo_url != '') {
        bp_logo_url = bp_logo_url.substring(1, bp_logo_url.length - 1);
        $('.navbar .navbar-right .dropdown img').attr('src', bp_logo_url);
    } else {
        $('.navbar .navbar-right .dropdown img').attr('src', yonghui.contextPath +'/img/id.jpg');
    }
}
$(document).ready(function() {
    getLoginInfo();
});
/*解决button的foucs事件*/
$('body').on('click','button',function(){
   $(this).blur(); 
});
