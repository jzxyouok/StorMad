var yonghui = {
    contextPath: 'http://testbss.stormad.cn'
};

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
    this.pageSize = 1;
    this.num = 1;
    this.pageNumFlag = true;
    this.init();
}
Ader.prototype = {
    getData: function() {
        var self = this;
        api.post('/api/ader/query.jsp', {
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
                    var aderItem = '<tr><td>$num$</td><td><a href="#">$corpName$</a></td><td>$state$</td><td>￥<span>$balance$</span>元</td><td>￥<span>$cashBalance$</span>元</td><td>￥<span>$goodsBalance$</span>元</td><td data-adUin="$adUin$"><a class="add-Cash" href="javascript:void(0);">添加金额</a>|<a href="javascript:void(0);" id="disable"$disableClass$>停用</a>|<a  href="javascript:void(0);" id="enable" $enableClass$>启用</a></td></tr>';
                    $.each(res.obj.record, function(n, item) {
                        aderList += api.simple(aderItem, {
                            num: self.num++,
                            corpName: item.corporation,
                            state: item.statusCN,
                            balance: item.balance,
                            cashBalance: item.cashBalance,
                            goodsBalance: item.goodsBalance,
                            adUin: item.adUin,
                            disableClass: item.status !== 2 ? ' class="active-status"' : '',
                            enableClass: item.status !== 3 ? ' class="active-status"' : ''
                        })
                    });
                    $('#aderList').html(aderList);
                } else {
                    $('#aderList').html('');
                    self.setPageNumber(0);
                }
            }
        })
    },
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
    disableAccount: function(adUin) {
        var self = this;
        api.post('/api/ader/stop.jsp', {
            aduin: adUin
        }, function(res) {
            if (res.errCode === 0) {
                layer.msg(res.obj.statusCN);
                self.getData();
            } else {
                layer.msg(res.obj.statusCN);
            }
        });
    },
    bindEvent: function() {
        var self = this;
        $('#btnSearch').on('click', function() {
            self.corpName = document.getElementById('corpName').value;
            self.status = self.setStatus(document.getElementById('status').value);
            console.log(self.corpName + '\n');
            console.log(self.status);
            self.pageNo = 1;
            self.pageNumFlag = true;
            self.getData();
        });
        $('#aderList').on('click', '.add-Cash', function() {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                skin: 'yourclass',
                content: '<div id="addCash" class="frame-content"><div class="frame-body add-cash"><form class="layui-form" action=""><div class="layui-form-item"><label class="layui-form-label">请选择添加金额类型：</label><div class="layui-input-inline"><select name="quiz1"><option value="" selected="">类型</option><option value="现金">现金</option><option value="货款">货款</option></select></div></div><div class="layui-form-item"><label class="layui-form-label">输入金额：</label><div class="layui-input-inline"><input name=" " lay-verify=" " autocomplete="on" placeholder="最多输入￥100.000" class="layui-input" type="text"><span>元</span></div></div></form><div class="layui-input-inline button-box"><button class="layui-btn" lay-submit="" lay-filter=" ">确认添加</button><button type="reset" class="layui-btn layui-btn-primary">返回</button></div></div></div>'
            });
        }).on('click', '#disable:not(.active-status)', function() {
            self.disableAccount($(this).closest('td').data('adUin'));
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
