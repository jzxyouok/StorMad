function API() {}
API.prototype = {
    post: function(url, data, cb) {
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + url,
            dataType: 'json',
            data: data,
            success: cb
        });
    }
};
var API = new API();

function OpenAccount() {
    this.code = '';
    this.init();
}
OpenAccount.prototype = {
    /**
     * 数据格式验证
     * @param  {object} obj js节点
     */
    validate: function(obj) {
        if ('' === $.trim(obj.value)) {
            this.setErrorMsg(obj, '请输入' + $(obj).closest('.form-group').find('.control-label').text().split('：')[0]);
        } else {
            var name = obj.name,
                val = obj.value;
            if ((name === 'legalIdcard') && !/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(val)) {
                this.setErrorMsg(obj, '身份证格式错误');
            } else if ((name === 'password') && !/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/.test(val)) {
                this.setErrorMsg(obj, '请输入6-20位数字和字母的组合');
            } else if (name === 'email' && !/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(val)) {
                this.setErrorMsg(obj, '联系人邮箱格式错误');
            } else if ((name === 'phone') && !/^1[34578]\d{9}$/.test(val)) {
                this.setErrorMsg(obj, '手机号码格式错误');
            } else if ((name === 'orgCode') && !/^\d{9}$/.test(val)) {
                this.setErrorMsg(obj, '组织机构代码格式错误');
            } else if (name === 'busiRegNo' && !/^.{18}$/.test(val)) {
                this.setErrorMsg(obj, '工商注册号格式错误');
            }
        }
    },
    /**
     * 设置错误提示信息
     * @param {object} obj js节点
     * @param {String} msg 错误提示信息
     */
    setErrorMsg: function(obj, msg) {
        // $(obj).closest('.form-group').find('.tips').html('<i class="fa fa-exclamation" aria-hidden="true"></i>' + msg);
        $(obj).closest('.form-group').find('.tips').html('<i class="exclamation-icon"></i>' + msg);
    },
    /**
     * 获取验证码倒计时
     * @param  {int} second 时间
     */
    countDown: function(second) {
        var self = this;
        second = parseInt(second);
        $('.getCaptcha').html(second);
        setTimeout(function() {
            if (second === 1) {
                $('.getCaptcha').html('获取验证码').removeAttr('disabled');
            } else {
                $('.getCaptcha').html(second);
                second--;
                self.countDown(second);
            }
        }, 1000)
    },
    getCode: function() {
        var self = this;
        API.post('/api/common/getVCode.jsp', {
            phone: $('input[name="phone"]').val(),
            btype: 1
        }, function(data) {
            if (data.errCode === 0) {
                $('#btnVCode').attr('disabled', 'disabled');
                self.countDown(60);
                self.code = data.obj;
            } else {
                alert(data.errMsg);
            }
            $('#vcode').off('blur', function() {
                self.validate();
            });
        });

    },
    register: function() {
        var dataPram = this.ARRAY2JSON($(".form-horizontal").serializeArray());
        dataPram.logoUrl = this.urlParam('key', $('#logo').attr('src'));
        dataPram.busiLicenseUrl = this.urlParam('key', $('#busiLicense').attr('src'));
        dataPram.taxCertifyUrl = this.urlParam('key', $('#taxCertify').attr('src'));

        $('input[type="text"],input[type="password"],input[type="email"]').trigger('blur');
        if (!$('.fa-exclamation').length) {
            if ($('.checkbox input').is(':checked')) {
                API.post('http://testbp.stormad.cn/api/ader/register.jsp', dataPram, function(data) {
                    if (data.errCode === 0) {
                        console.log(data);
                    } else {
                        alert(data.errMsg);
                    }
                });
            } else {
                layer.msg('请阅读并同意《永辉超市广告在线竞拍服务协议条款》', {
                    area: ['auto', 'auto'],
                    time: 20000, //20s后自动关闭
                    btn: ['知道了'],
                    btnAlign: 'c'
                });
            }
        }
    },
    urlParam: function(name, url) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = null;
        if (url) {
            var r = url.split('.jsp')[1].substr(1).match(reg);
        }
        return null != r ? unescape(r[2]) : null;
    },
    ARRAY2JSON: function(arr) {
        if (!arr || arr.length === 0) return {}
        var json = {}

        for (var i = 0; i < arr.length; i++) {
            json[arr[i].name] = arr[i].value;
        };

        return json;
    },
    bindEvent: function() {
        var self = this;
        $('#btnVCode:not(:disabled)').on('click', function() {
            $('#vcode').off('blur');
            self.getCode();
        });

        $('input[type="text"],input[type="password"],input[type="email"]').on('blur', function() {
            self.validate(this);
        }).on('focus', function() {
            $(this).closest('.form-group').find('.tips').html('')
        });

        $('#btnSubmit').on('click', function() {
            self.register();
        });
    },
    init: function() {
        this.bindEvent();
    }
}


$(function() {
    new OpenAccount();
    /**
     * 图片上传
     */
    layui.use('upload', function() {
        layui.upload({
            url: 'http://testbp.stormad.cn/api/ader/upload.jsp' //上传接口
                ,
            success: function(res, input) { //上传成功后的回调
                if (res.errCode === 0) {
                    $(input).parents('.upbar').siblings('img').attr('src', 'http://testbp.stormad.cn/api/showTempImg.jsp?key=' + res.obj);
                } else {
                    alert(res.errMsg);
                }
            }
        });
    });
});
