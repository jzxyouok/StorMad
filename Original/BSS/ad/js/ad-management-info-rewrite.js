function API() {}
API.prototype = {
    /**
     * [API接口调用]
     * @param {[type]}   type [请求类型]
     * @param {[type]}   _url [url]
     * @param {[type]}   data [参数]
     * @param {Function} cb   [回调函数]
     */
    APImethod: function(type, url, data, cb) {
        $.ajax({
            type: type,
            url: yonghui.contextPath + url,
            dataType: 'json',
            data: data,
            success: cb
        });
    },
    post: function(url, data, cb) {
        return this.APImethod('POST', url, data, cb)
    },
    get: function(url, data, cb) {
        return this.APImethod('GET', url, data, cb)
    },
    simple: function(str, obj) {
        return str.replace(/\$\w+\$/gi, function(matchs) {
            var returns = obj[matchs.replace(/\$/g, "")];
            return typeof returns === "undefined" ? "" : returns;
        });
    },
    /**
     * [获取url参数值]
     * @param  {[type]} name [参数名称]
     * @return {[type]}      [参数值]
     */
    getUrlParam: function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"),
            r = window.location.search.substr(1).match(reg);
        return null != r ? unescape(r[2]) : null;
    }
};
var api = new API();

function User() {
    this.aduin = api.getUrlParam('aduin') || '';
    this.init();
}
User.prototype = {
    /**
     * [广告主开户资料查询]
     */
    getData: function() {
        var self = this;
        api.post('/api/ader/findById.jsp', {
            adUin: api.getUrlParam('aduin')
        }, function(res) {
            if (0 === res.errCode) {
                var aderListLeft = '',
                    aderListRight = '',
                    obj = res.obj;
                var aderListLeftItem = '<tr><td>企业全称：</td><td>$corporation$</td></tr><tr><td>企业地址：</td><td>$addr$</td></tr><tr><td>法人姓名：</td><td>$legalPerson$</td></tr><tr><td>法人身份证：</td><td>$legalIdcard$</td></tr><tr><td>开户银行：</td><td>$bank$</td></tr><tr><td>银行开户账号：</td><td>$accountName$</td></tr><tr><td>银行卡帐号：</td><td>$cardNo$</td></tr><tr><td>登陆用户名：</td><td>$loginName$</td></tr><tr></tr>';
                var aderListRightItem = '<tr><td>联系人姓名：</td><td>$contact$</td></tr><tr><td>联系人邮箱：</td><td>$email$</td></tr><tr><td>联系人手机号：</td><td>$phone$</td></tr><tr><td>组织机构代码证：</td><td>$orgCode$</td></tr><tr><td>工商注册号：</td><td>$busiRegNo$</td></tr><tr><td>品牌logo：</td><td><a class="brand-Logo" href="javascript:void(0);">查看</a></td></tr><tr><td>营业执照/税务登记证：</td><td><a class="business-License" href="javascript:void(0);">查看</a></td></tr><tr><td>一般纳税人资格认定资料：</td><td><a class="certification-Information" href="#">查看</a></td></tr>';
                aderListLeft += api.simple(aderListLeftItem, {
                    corporation: obj.corporation,
                    addr: obj.province + obj.city + obj.district,
                    legalPerson: obj.legalPerson,
                    legalIdcard: obj.legalIdcard,
                    bank: obj.bank,
                    accountName: obj.accountName,
                    cardNo: obj.cardNo,
                    loginName: obj.loginName,
                    password: obj.password
                });
                aderListRight += api.simple(aderListRightItem, {
                    contact: obj.contact,
                    email: obj.email,
                    phone: obj.phone,
                    orgCode: obj.orgCode,
                    busiRegNo: obj.busiRegNo
                });
                document.getElementById('aderListLeft').innerHTML = aderListLeft;
                document.getElementById('aderListRight').innerHTML = aderListRight;
                self.seeImg(res.obj);
            }
        });
    },
    /**
     * [审核通过操作]
     */
    approve: function() {
        api.post('/api/ader/approve.jsp', {
            tuin: this.aduin,
            status: 2
        }, function(res) {
            if (0 === res.errCode) {
                layer.msg('审核通过', {
                    time: 800
                });
                setTimeout(function() {
                    window.location.href = yonghui.contextPath + '/ad/ad-management-review.html';
                }, 800);
            } else {
                layer.msg(res.errMsg, {
                    time: 2000
                });
            }
        })
    },
    /**
     * [查看图片弹窗]
     * @param  {[type]} obj [广告主信息]
     */
    seeImg: function(obj) {
        $('body').on('click', 'a.brand-Logo', function() {
            layer.open({
                type: 1,
                area: ['580px', 'auto'],
                title: ['品牌LOGO', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
                shadeClose: true, //点击遮罩关闭
                content: '<div id="brandLogo" class="frame-content"><div class="frame-body ad-management-pic"><img src="http://testbp.stormad.cn/api/showTempImg.jsp?key=' + obj.logoUrl + '" /></div></div>'
            });
        }).on('click', 'a.business-License', function() {
            layer.open({
                type: 1,
                area: ['580px', 'auto'],
                title: ['营业执照/税务登记证', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
                shadeClose: true, //点击遮罩关闭'
                content: '<div id="businessLicense" class="frame-content"><div class="frame-body ad-management-pic"><img src="http://testbp.stormad.cn/api/showTempImg.jsp?key=' + obj.busiLicenseUrl + '" /></div> </div>'
            });
        }).on('click', 'a.certification-Information', function() {
            layer.open({
                type: 1,
                area: ['580px', 'auto'],
                title: ['一般纳税人资格认定资料', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
                shadeClose: true, //点击遮罩关闭
                content: '<div id="certificationInformation" class="frame-content"><div class="frame-body ad-management-pic"><img src="http://testbp.stormad.cn/api/showTempImg.jsp?key=' + obj.taxCertifyUrl + '" /></div> </div>'
            });
        });
    },
    getNotPassReason: function() {
        var options = '';
        api.post('/api/ader/approve.jsp', {
            op: 1
        }, function(res) {
            if (0 === res.errCode) {
                res.obj && res.obj.length > 0 && $.each(res.obj, function(n, item) {
                    options += '<option value=' + item.id + '>' + item.name + '</option>';
                });
                layer.open({
                    type: 1,
                    area: ['420px', 'auto'],
                    title: ['审核结果', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
                    shadeClose: true, //点击遮罩关闭
                    content: '<div id="adminReviewStop" class="frame-content"><div class="frame-body add-cash ad-management-pic"><form class="layui-form" action=""><div class="layui-form-item"><label class="layui-form-label">审核不通过原因：</label><div class="layui-input-inline"><select name="reason" lay-filter="reason" id="notPassReason"><option value="" selected="">请选择不通过原因</option>' + options + '</select></div></div><div class="layui-form-item textarea-wrap hide"><textarea placeholder="请输入内容" class="layui-textarea" maxlength="20"></textarea></div></form><div class="layui-input-inline button-box"><button class="layui-btn btn-confirm disabled" lay-submit="" lay-filter="">确认</button><button type="reset" class="layui-btn layui-btn-primary btn-return">返回</button></div></div></div>',
                    success: function() {
                        layui.use('form', function() {
                            var form = layui.form();
                            form.render('select');
                            form.on('select(reason)', function(data) {
                                if ('' === data.value) {
                                    $('.btn-confirm').addClass('disabled');
                                } else {
                                    $('.btn-confirm').removeClass('disabled');
                                }
                                if ('其它' === data.value) {
                                    $('.textarea-wrap').removeClass('hide');
                                } else {
                                    $('.textarea-wrap').addClass('hide');
                                }
                            });
                        });
                    }
                });
            }
        });
    },
    setNotPass: function(result, reason) {
        var self = this;
        api.post('/api/ader/approve.jsp', {
            op: 0,
            tuin: self.aduin,
            status: 1,
            result: result,
            reason: reason
        }, function(res) {
            if (0 === res.errCode) {
                layer.msg('操作成功', {
                    time: 800,
                    success: function() {
                        window.location.href = yonghui.contextPath + '/ad/ad-management-review.html';
                    }
                });
            } else {
                layer.msg(res.errMsg);
            }
        })
    },
    bindEvent: function() {
        var self = this;
        $('.content').on('click', '#btnApprove', function() {
            self.approve();
        });
        $('body').on('click', '.btn-return', function() {
            layer.closeAll();
        }).on('click', '.btn-confirm:not(.disabled)', function() {
            if ('' === $('#notPassReason option:selected').val()) {
                layer.msg('请选择不通过原因', {
                    time: 800
                });
            } else if ('其它' === $('#notPassReason option:selected').val() && '' === $('.textarea-wrap textarea').val()) {
                layer.msg('请输入不通过原因', {
                    time: 800
                });
            } else {
                if ('其它' === $('#notPassReason option:selected').val()) {
                    self.setNotPass($('#notPassReason option:selected').val(), $('.textarea-wrap textarea').val());
                } else {
                    self.setNotPass($('#notPassReason option:selected').val());
                }
            }
        });

        $('button.admin-Review-Stop').on('click', function() {
            self.getNotPassReason();
        });
    },
    init: function() {
        this.getData();
        this.bindEvent();
    }
};
$(function() {
    new User();
});
