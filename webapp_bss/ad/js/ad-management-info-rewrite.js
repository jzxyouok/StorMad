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
                document.getElementById('corporation').innerHTML = res.obj.corporation;
                document.getElementById('addr').innerHTML = document.getElementById('addr').title = ((res.obj.province === res.obj.city) ? '' : (res.obj.province + '省')) + res.obj.city + '市' + res.obj.district;
                document.getElementById('legalPerson').innerHTML = res.obj.legalPerson;
                document.getElementById('legalIdcard').innerHTML = res.obj.legalIdcard;
                document.getElementById('bank').innerHTML = res.obj.bank;
                document.getElementById('accountName').innerHTML = res.obj.accountName;
                document.getElementById('cardNo').innerHTML = res.obj.cardNo;
                document.getElementById('loginName').innerHTML = res.obj.loginName;
                document.getElementById('contact').innerHTML = res.obj.contact;
                document.getElementById('email').innerHTML = res.obj.email;
                document.getElementById('phone').innerHTML = res.obj.phone;
                document.getElementById('orgCode').innerHTML = res.obj.orgCode;
                document.getElementById('busiRegNo').innerHTML = res.obj.busiRegNo;
                $('#brandLogo img').attr('src', res.obj.logoUrl);
                $('#businessLicense img').attr('src', res.obj.busiLicenseUrl);
                $('#certificationInformation img').attr('src', res.obj.taxCertifyUrl);
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
    getNotPassReason: function() {
        var options = '<option value="" selected="">请选择不通过原因</option>';
        api.post('/api/ader/approve.jsp', {
            op: 1
        }, function(res) {
            if (0 === res.errCode) {
                res.obj && res.obj.length > 0 && $.each(res.obj, function(n, item) {
                    options += '<option value=' + item.id + '>' + item.name + '</option>';
                });
                $('#adminReviewStop select').html(options);
                $('.textarea-wrap').addClass('hide');
                layui.use('form', function() {
                    var form = layui.form();
                    form.render('select');
                    form.on('select(reason)', function(data) {
                        if ('' === data.value) {
                            $('.btn-confirm').addClass('disabled');
                        } else {
                            $('.btn-confirm').removeClass('disabled');
                        }
                        if ('其它' === $('#adminReviewStop select option[value=' + data.value + ']').text()) {
                            $('.textarea-wrap').removeClass('hide');
                        } else {
                            $('.textarea-wrap').addClass('hide');
                        }
                    });
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
            layer.open({
                type: 1,
                title: '审核通过确认',
                area: ['auto', 'auto'], //宽高
                shadeClose: true,
                btn: '确定',
                content: '是否确认审核通过该广告主',
                yes: function(){
                    self.approve();
                }
            });
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
                if ('其它' === $('#notPassReason option:selected').text()) {
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
