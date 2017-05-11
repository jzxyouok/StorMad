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
    }
};
var api = new API();

function Ader(options) {
    this.corpName = '';
    this.status = '';
    this.contact = '';
    this.phone = '';
    this.pageNo = 1;
    this.pageSize = yonghui.pageSize;
    this.num = 1;
    this.pageNumFlag = true;
    this.init();
}
Ader.prototype = {
    /**
     * [广告主列表]
     */
    getData: function() {
        var self = this;
        api.post('/api/ader/query.jsp', {
            op: 1,
            corpName: this.corpName,
            status: this.status,
            contact: this.contact,
            phone: this.phone,
            pageNo: this.pageNo,
            pageSize: this.pageSize
        }, function(res) {
            if (res.errCode === 0) {
                if (res.obj.record && res.obj.record.length) {
                    self.num = self.pageSize * (self.pageNo - 1) + 1;
                    if (res.obj.pageCount > 0 && self.pageNumFlag) {
                        self.pageNumFlag = false;
                        self.setPageNumber(res.obj.pageCount);
                    }
                    var aderList = '';
                    var aderItem = '<tr><td>$num$</td><td><a href="$aderInfoUrl$">$corpName$</a></td><td>$state$</td><td>￥<span>$balance$</span></td><td>￥<span>$cashBalance$</span></td><td>￥<span>$goodsBalance$</span></td><td data-aduin="$adUin$" data-accountName="$accountname$"><a class="add-Cash$addCashClass$" href="javascript:void(0);">添加金额</a>|<a href="javascript:void(0);" id="disable"$disableClass$>停用</a>|<a  href="javascript:void(0);" id="enable" $enableClass$>启用</a></td></tr>';
                    $.each(res.obj.record, function(n, item) {
                        aderList += api.simple(aderItem, {
                            num: self.num++,
                            aderInfoUrl: yonghui.contextPath + '/ad/ad-management-rewrite.html?aduin=' + item.adUin,
                            corpName: item.corporation,
                            state: item.statusCN,
                            balance: (item.balance / 100).toFixed(2),
                            cashBalance: (item.cashBalance / 100).toFixed(2),
                            goodsBalance: (item.goodsBalance / 100).toFixed(2),
                            adUin: item.adUin,
                            accountname: item.accountName,
                            addCashClass: item.status !== 2 ? ' active-status' : '',
                            disableClass: item.status !== 2 ? ' class="active-status"' : '',
                            enableClass: item.status !== 3 ? ' class="active-status"' : ''
                        })
                    });
                    $('#aderList').html(aderList);
                    $(".no_info").hide();
                    $(".show_info").show();
                } else {
                    $('#aderList').html('');
                    $(".no_info").show();
                    $(".show_info").hide();
                    self.setPageNumber(0);
                }
            }
        })
    },
    /**
     * [分页]
     * @param {[type]} pageCount [总页数]
     */
    setPageNumber: function(pageCount) {
        var self = this;
        layui.use('laypage', function() {
            var laypage = layui.laypage,
                layer = layui.layer;
            laypage({
                cont: 'pageNumber',
                pages: pageCount,
                skin: '#2089ff',
                skip: true,
                groups: yonghui.groups,
                jump: function(obj, first) {
                    //得到了当前页，用于向服务端请求对应数据
                    if (!first) {
                        self.pageNo = obj.curr;
                        self.getData();
                    }
                }
            });
        });
    },
    /**
     * [账户状态中文和数字对应]
     * @param {[type]} status [中文状态]
     */
    setStatus: function(status) {
        switch (status) {
            case '停用':
                return 3;
                break;
            case '正常':
                return 2;
                break;
            case '账户审核中':
                return 0;
                break;
            case '审核未通过':
                return 1;
                break;
            default:
                return '';
        }
    },
    /**
     * [停用账号]
     * @param  {[type]} adUin [广告主id]
     */
    setAccountStatus: function(adUin, status) {
        var self = this;
        api.post('/api/ader/freeze.jsp', {
            tuin: adUin,
            status: status
        }, function(res) {
            if (res.errCode === 0) {
                layer.msg(res.errMsg, {
                    time: 800
                });
                setTimeout(function() {
                    self.getData();
                })
            } else {
                layer.msg(res.errMsg, {
                    time: 800
                });
            }
        });
    },
    /**
     * [添加金额弹窗]
     */
    addCash: function() {
        layer.closeAll();
        $('input#cash').val('');
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            title: ['添加金额', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true,
            content: $('#addCash')
        });
    },
    /**
     * [确认添加金额]
     * @param  {[type]} cash        [金额]
     * @param  {[type]} accountName [账户名]
     */
    confirmAddCash: function(cash, accountName) {
        layer.closeAll();
        $('#addCash-confirm .cash').html(cash);
        $('#addCash-confirm .account').html(accountName);
        layer.open({
            type: 1,
            area: ['auto', 'auto'],
            title: ['确认添加', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
            shadeClose: true,
            content: $('#addCash-confirm')
        });
    },
    /**
     * [添加金额]
     * @param {[type]} cash [金额]
     * @param {[type]} tuin [广告主id]
     * @param {[type]} type [添加金额类型(现金、货款)]
     */
    setCash: function(cash, tuin, type) {
        api.post('/api/money/recharge.jsp', {
            money: cash,
            tuin: tuin,
            type: type
        }, function(res) {
            if (0 === res.errCode) {
                layer.msg('充值成功', {
                    time: 800
                });
            } else {
                layer.msg(res.errMsg, {
                    time: 800
                });
            }
            setTimeout(function() {
                location.reload();
            }, 1000);
        });
    },
    bindEvent: function() {
        var self = this;
        var accountName = '',
            cash = 0,
            addCashType = 0,
            aduin = null;
        $('#btnSearch').on('click', function() {
            self.corpName = document.getElementById('corpName').value;
            self.status = self.setStatus(document.getElementById('status').value);
            self.pageNo = 1;
            self.pageNumFlag = true;
            self.getData();
        });
        $('#aderList').on('click', '.add-Cash:not(.active-status)', function() {
            aduin = $(this).closest('td').data('aduin');
            accountName = $(this).closest('td').data('accountname');
            self.addCash();
        }).on('click', '#disable:not(.active-status)', function() {
            aduin = $(this).closest('td').data('aduin');
            layer.open({
                title: '停用',
                shadeClose: true,
                content: '是否停用该广告主账号',
                yes: function() {
                    self.setAccountStatus(aduin, 3);
                }
            });
            // self.setAccountStatus(aduin, 3);
        }).on('click', '#enable:not(.active-status)', function() {
            aduin = $(this).closest('td').data('aduin');
            layer.open({
                title: '启用',
                shadeClose: true,
                content: '是否启用该广告主账号',
                yes: function() {
                    self.setAccountStatus(aduin, 2);
                }
            });
        });
        $('body').on('click', '#btnConfirm', function() {
            $(this).removeAttr('disabled').css({'background-color':'#2089ff'});
            cash = $('#cash').val();
            var cashTypeVal = $('#addCash option:selected').val();
            if ('' === cashTypeVal) {
                layer.msg('请选择添加金额类型', {
                    time: 800
                });
            } else if ('' === $.trim(cash)) {
                layer.msg('请输入金额', {
                    time: 800
                });
            } else if (!/^(([1-9][0-9]*)|(([0]\.\d{1,2}|[1-9][0-9]*\.\d{1,2})))$/.test(cash)) {
                layer.msg('请输入正确的金额', {
                    time: 800
                });
            } else {
                self.confirmAddCash(cash, accountName);
                if ('现金' === cashTypeVal) {
                    addCashType = 1;
                } else if ('货款' === cashTypeVal) {
                    addCashType = 2;
                }
            }

        }).on('click', '#addCashReturn', function() {
            layer.closeAll();
        }).on('click', '#btnConfirmAgain', function() {
            $(this).attr('disabled','disabled').css({'background-color':'#ccc'});
            self.setCash(cash, aduin, addCashType);
        });
    },
    init: function() {
        this.getData();
        this.bindEvent();
    }
}
$(function() {
    new Ader();
})
