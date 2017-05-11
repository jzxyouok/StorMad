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
            op: 2,
            corpName: self.corpName,
            status: self.status,
            contact: self.contact,
            phone: self.phone,
            pageNo: self.pageNo,
            pageSize: self.pageSize
        }, function(res) {
            if (res.errCode === 0) {
                if (res.obj.record && res.obj.record.length) {
                    self.num = self.pageSize * (self.pageNo - 1) + 1;
                    if (res.obj.pageCount > 0 && self.pageNumFlag) {
                        self.pageNumFlag = false;
                        self.setPageNumber(res.obj.pageCount);
                    }
                    var aderList = '';
                    var aderItem = '<tr><td>$num$</td><td><a href="$aderInfoUrl$">$corpName$</a></td><td>$contact$</td><td class="phone">$phone$</td><td class="status">$state$</td><td data-aduin="$adUin$">$op$</td></tr>';
                    $.each(res.obj.record, function(n, item) {
                        aderList += api.simple(aderItem, {
                            num: self.num++,
                            aderInfoUrl: yonghui.contextPath + '/ad/ad-management-rewrite.html?aduin=' + item.adUin,
                            corpName: item.corporation,
                            contact: item.contact,
                            phone: item.phone,
                            state: item.statusCN,
                            adUin: item.adUin,
                            op: '账户申请中' === item.statusCN ? '<a href="' + yonghui.contextPath + '/ad/ad-management-info-rewrite.html?aduin=' + item.adUin + '">资料审核</a>' : '<a class="review-result" href="javascript:void(0);">通知</a>'
                        })
                    });
                    $('#aderList').html(aderList);
                    $(".no_info").hide();
                    $(".show_info").show();
                } else {
                    $("#sechedule_list").html('');
                    $(".no_info").show();
                    $(".show_info").hide();
                    self.setPageNumber(0);
                }
            } else {
                layer.msg(res.errMsg);
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
            case '审核通过':
                return 2;
                break;
            case '未审核':
                return 0;
                break;
            case '审核不通过':
                return 1;
                break;
            case '审核未通过':
                return 1;
                break;
            default:
                return '';
        }
    },
    informAder: function(uin, type) {
        api.post('/api/ader/notify.jsp', {
            tuin: uin,
            mtype: type
        }, function(res) {
            if (0 === res.errCode) {
                layer.msg('该通知已发送成功', { time: 800 });
            } else {
                layer.msg(res.errMsg, { time: 800 });
            }
        });
    },
    bindEvent: function() {
        var self = this;
        var accountName = '',
            cash = 0,
            addCashType = 0,
            aduin = null;
        $('#btnSearch').on('click', function() {
            self.contact = document.getElementById('contactPerson').value;
            self.phone = document.getElementById('contactTel').value;
            self.corpName = document.getElementById('corpName').value;
            self.status = self.setStatus(document.getElementById('reviewStatus').value);
            self.pageNo = 1;
            self.pageNumFlag = true;
            self.getData();
        });
        $('#aderList').on('click', 'a.review-result', function() {
            layer.open({
                type: 1,
                area: ['auto', 'auto'],
                title: ['发送信息', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
                shadeClose: true, //点击遮罩关闭
                content: '<div id="reviewPass" class="frame-content"><div class="frame-body info-review"><p><span></span></p><p>是否发送<span id="reviewResult" data-uin="' + $(this).closest('td').data('aduin') + '">' + $(this).closest('tr').find('.status').html() + '</span>通知至联系人电话：<span>' + $(this).closest('td').siblings('.phone').text() + '</span>？</p><p>发送信息后无法撤回，请确认后操作。</p><div class="layui-input-inline button-box"><button type="button" class="layui-btn" lay-submit="" lay-filter=" " id="btnConfirm">确认</button><button type="button" class="layui-btn layui-btn-primary" id="btnCancel">取消</button></div></div></div>'
            });
        });
        $('body').on('click', '#btnConfirm', function() {
            self.informAder($('#reviewResult').data('uin'), self.setStatus($('#reviewResult').html()));
            layer.closeAll();
        }).on('click', '#btnCancel', function() {
            layer.closeAll();
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
