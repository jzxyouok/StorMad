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
        if ('address' === obj.name) {
            if ('' === $('#province').val()) {
                this.setErrorMsg(obj, '请选择省份');
            } else if ('' === $('#city').val()) {
                this.setErrorMsg(obj, '请选择市');
            } else if ('' === $('#district').val()) {
                this.setErrorMsg(obj, '请选择县');
            } else if ('' === $.trim(obj.value)) {
                this.setErrorMsg(obj, '请输入' + $(obj).closest('.form-group').find('.control-label').text().split('：')[0]);
            }
        } else {
            if ('' === $.trim(obj.value)) {
                this.setErrorMsg(obj, '请输入' + $(obj).closest('.form-group').find('.control-label').text().split('：')[0]);
            } else {
                var name = obj.name,
                    val = obj.value;
                if ((name === 'legalIdcard') && !IdCardValidate(val)) {
                    this.setErrorMsg(obj, '身份证格式错误');
                } else if ((name === 'password') && !/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/.test(val)) {
                    this.setErrorMsg(obj, '请输入6-20位数字和字母的组合');
                } else if (name === 'email' && !/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(val)) {
                    this.setErrorMsg(obj, '联系人邮箱格式错误');
                } else if ((name === 'phone') && !/^1[34578]\d{9}$/.test(val)) {
                    this.setErrorMsg(obj, '手机号码格式错误');
                    // } else if ((name === 'orgCode') && !/^\d{9}$/.test(val)) {
                } else if ((name === 'orgCode') && !/^[\s\S]+$/.test(val)) {
                    this.setErrorMsg(obj, '组织机构代码格式错误');
                    // } else if (name === 'busiRegNo' && !/^.{15}$/.test(val)) {
                } else if (name === 'busiRegNo' && !/^[\s\S]+$/.test(val)) {
                    this.setErrorMsg(obj, '工商注册号格式错误');
                } else if (name === 'accountName' && (val !== $('input[name="corporation"]').val())) {
                    this.setErrorMsg(obj, '开户账户与企业名称不一致');
                } else if (name === 'loginName' && !/^[A-Za-z0-9\u4e00-\u9fa5]+$/.test(val)) {
                    this.setErrorMsg(obj, '请输入1-10位数字文字和字母组合');
                } else if (name === 'cardNo' && !(/^\d{16}$/.test(val) || /^\d{19}$/.test(val))) {
                    this.setErrorMsg(obj, '账号格式错误');
                } else if (((name === 'legalPerson') || (name === 'contact')) && !/^[\u4e00-\u9fa5]{2,10}$/.test(val)) {
                    this.setErrorMsg(obj, '姓名格式错误');
                }
            }
        }
    },
    /**
     * 设置错误提示信息
     * @param {object} obj js节点
     * @param {String} msg 错误提示信息
     */
    setErrorMsg: function(obj, msg) {
        $(obj).closest('.form-group').find('.tips').html('<i class="fa fa-exclamation" aria-hidden="true"></i>' + msg);
    },
    /**
     * 获取验证码倒计时
     * @param  {int} second 时间
     */
    countDown: function(second) {
        var self = this;
        second = parseInt(second);
        $('#btnVCode').html(second);
        setTimeout(function() {
            if (second === 1) {
                $('#btnVCode').html('获取验证码').removeAttr('disabled');
            } else {
                $('#btnVCode').html(second);
                second--;
                self.countDown(second);
            }
        }, 1000)
    },
    /**
     * [获取验证码]
     */
    getCode: function() {
        var self = this;
        API.post('/api/ader/getVCode.jsp', {
            phone: $('input[name="phone"]').val(),
            btype: 1
        }, function(data) {
            if (data.errCode === 0) {
                $('#btnVCode').attr('disabled', 'disabled');
                self.countDown(60);
                self.code = data.obj;
            } else {
                layer.msg(data.errMsg);
            }
            $('#vcode').off('blur', function() {
                self.validate();
            });
        });

    },
    /**
     * [注册]
     */
    register: function() {
        var dataPram = this.ARRAY2JSON($(".form-horizontal").serializeArray());
        dataPram.logoUrl = this.urlParam('key', $('#logo').attr('src'));
        dataPram.busiLicenseUrl = this.urlParam('key', $('#busiLicense').attr('src'));
        dataPram.taxCertifyUrl = this.urlParam('key', $('#taxCertify').attr('src'));

        $('input[type="text"],input[type="password"],input[type="email"]').trigger('blur');
        $('#logo').attr('src') || this.setErrorMsg('#logo', '请上传品牌logo');
        $('#busiLicense').attr('src') || this.setErrorMsg('#busiLicense', '请上传营业执照/税务登记证');
        $('#taxCertify').attr('src') || this.setErrorMsg('#taxCertify', '请上传一般纳税人资格认定资料');
        if (!$('.fa-exclamation').length) {
            if ($('.checkbox input').is(':checked')) {
                API.post('/api/ader/register.jsp', dataPram, function(data) {
                    if (data.errCode === 0) {
                        window.location.href = yonghui.contextPath + '/ader/login-new-account-status.html';
                    } else {
                        layer.alert(data.errMsg);
                    }
                });
            } else {
                layer.msg('请阅读并同意《永辉超市广告在线竞拍服务协议条款》', {
                    time: 800
                });
            }
        } else {
            $(window).scrollTop($('.fa-exclamation:first').offset().top - 80);
            /*layer.msg('有选项未填或格式错误', {
                time: 800,
                end: function() {
                    $(window).scrollTop(0);
                }
            });*/
        }
    },
    /**
     * [url参数获取]
     * @param  {[type]} name [参数名]
     * @param  {[type]} url  [url]
     */
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
    /**
     * [输入位数控制]
     * @param  {[type]} obj [选择器]
     * @param  {[type]} num [位数]
     * @param  {[type]} msg [提示信息]
     */
    inputCtrl: function(obj, num, msg) {
        var $obj = $(obj);
        $obj.on('keydown input', function() {
            if ($obj.val().length > num) {
                $obj.val($obj.val().substr(0, num));
                if (msg) {
                    layer.msg(msg, {
                        time: 800
                    });
                } else {
                    layer.msg('最多输入' + num + '位', {
                        time: 800
                    });
                }
            }
        });
    },
    /**
     * [省市区]
     */
    selAddr: function() {
        var region = {};
        $.ajax({
            url: yonghui.contextPath + '/ader/js/region.json',
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function(res) {
                region = res.region;
            }
        });
        var province = '<option value="">请选择省</option>';
        $.each(region, function(n, item) {
            province += '<option value="' + item.name + '">' + item.name + '</option>';
        });
        $('#province').html(province);
        layui.use('form', function() {
            var form = layui.form();
            var provinceVal = '',
                cityVal = '';
            form.render('select');
            form.on('select(province)', function(data) {
                $('#address').closest('.form-group').find('.tips').html('');
                provinceVal = data.value;
                var city = '<option value="">请选择市</option>';
                $.each(region, function(index, val) {
                    if (val.name === $('#province').val()) {
                        $.each(val.city, function(n, item) {
                            city += '<option value="' + item.name + '">' + item.name + '</option>'
                        });
                        return false;
                    }
                });
                $('#city').html(city);
                $('#district').html('<option value="">请选择县/区</option>');
                form.render('select');
            });
            form.on('select(city)', function(data) {
                $('#address').closest('.form-group').find('.tips').html('');
                cityVal = data.value;
                $.each(region, function(index, val) {
                    var district = '<option value="">请选择县/区</option>'
                    if (val.name === provinceVal) {
                        $.each(val.city, function(n, item) {
                            if (item.name === cityVal) {
                                $.each(item.area, function(i, value) {
                                    district += '<option value="' + value + '">' + value + '</option>'
                                });
                                $('#district').html(district);
                                form.render('select');
                                return false;
                            }
                        });
                    }
                });
            });
            $('body').on('click', '.city-wrap', function() {
                if ('' === $('#province').val()) {
                    layer.msg('请先选择省份', {
                        time: 700
                    });
                }
            }).on('click', '.district-wrap', function() {
                if ('' === $('#province').val()) {
                    layer.msg('请先选择省份', {
                        time: 700
                    });
                } else if ('' === $('#city').val()) {
                    layer.msg('请先选择市', {
                        time: 700
                    });
                }
            });
        });


    },
    bindEvent: function() {
        var self = this;

        $('input[type="text"],input[type="password"],input[type="email"]').on('blur', function() {
            self.validate(this);
        }).on('focus', function() {
            $(this).closest('.form-group').find('.tips').html('')
        });

        $('body').on('click', '#btnVCode:not(:disabled)', function() {
            $('#vcode').off('blur');
            if ($.trim($('#phone').val()) === '') {
                $('#phone').closest('.form-group').find('.tips').html('<i class="fa fa-exclamation" aria-hidden="true"></i>请输入联系人手机号');
            } else if (!/^1[34578]\d{9}$/.test($('#phone').val())) {
                $('#phone').closest('.form-group').find('.tips').html('<i class="fa fa-exclamation" aria-hidden="true"></i>手机号码格式错误');
            } else {
                self.getCode();
            }
        }).on('click', '#btnSubmit', function() {
            self.register();
        });

        self.inputCtrl('#legalIdcard', 18);
        self.inputCtrl('#phone', 11);
        self.inputCtrl('#orgCode');
        self.inputCtrl('#busiRegNo');
        self.inputCtrl('#vcode', 6);
        self.inputCtrl('#password', 20);
        self.inputCtrl('#loginName', 10, '请输入1-10位任意组合用户名');
        self.inputCtrl('#cardNo', 19);
        self.inputCtrl('#legalPerson', 10);
        self.inputCtrl('#contact', 10);

        $('#btnBack').on('click', function() {
            window.location.href = yonghui.contextPath + '/ader/login.html';
        });
    },
    init: function() {
        this.bindEvent();
        this.selAddr();
    }
}


$(function() {
    new OpenAccount();
    /**
     * 图片上传
     */
    layui.use('upload', function() {
        layui.upload({
            url: yonghui.contextPath + '/api/ader/upload.jsp', //上传接口
            ext: 'jpg|png|jpeg', //限制图片格式
            success: function(res, input) { //上传成功后的回调
                if (res.errCode === 0) {
                    $(input).closest('.form-group').find('.tips').empty();
                    $(input).parents('.upbar').siblings('img').attr('src', yonghui.contextPath + '/api/showTempImg.jsp?key=' + res.obj);
                } else {
                    layer.alert(res.errMsg);
                }
            }
        });
    });
});
