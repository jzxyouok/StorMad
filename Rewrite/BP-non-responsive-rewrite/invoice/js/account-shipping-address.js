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
    },
    simple: function(str, obj) {
        return str.replace(/\$\w+\$/gi, function(matchs) {
            var returns = obj[matchs.replace(/\$/g, "")];
            return typeof returns === "undefined" ? "" : returns;
        });
    }
};
var api = new API();
/**
 * [地址]
 */
function Address() {
    this.pageNo = 1;
    this.region = {};
    this.pageSize = yonghui.pageSize;
    this.init();
}

Address.prototype = {
    getData: function() {
        var self = this;
        api.post('/api/invoice/queryAddr.jsp', {
            pageNo: self.pageNo,
            pageSize: self.pageSize
        }, function(res) {
            if (0 === res.errCode) {
                if (res.obj.record && res.obj.record.length) {
                    $('.empty-tips').hide();
                    $('.tab-addr').show();
                    var addrList = '',
                        addrItem = '<tr data-addr="$addrid$"><td><span class="checked"><i></i></span></td><td>$consignee$</td><td>$addr$</td><td>$phone$</td><td><a class="shipping-Address-Edit" href="javascript:void(0)">编辑</a><a class="shipping-Address-Delete" href="javascript:void(0)">删除</a></td></tr>';
                    $.each(res.obj.record, function(n, item) {
                        addrList += api.simple(addrItem, {
                            addrid: item.addrId,
                            consignee: item.consignee,
                            addr: self.joinAddr(item),
                            phone: item.phone
                        });
                    });
                    $('#addrList').html(addrList);
                    $('#addrList tr:first').trigger('click');
                } else {
                    $('.empty-tips').show();
                    $('.tab-addr').hide();
                }
            }
        });
    },
    joinAddr: function(item) {
        if (item.province === item.city) {
            return item.city + '市' + item.district + item.address;
        } else {
            return item.province + '省' + item.city + '市' + item.district + item.address;
        }
    },
    getAddr: function() {
        var self = this;
        $.ajax({
            url: yonghui.contextPath + '/ader/js/region.json',
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function(res) {
                self.region = res.region;
            }
        });
    },
    selAddr: function() {
        var self = this;
        var province = '<option value="">请选择省</option>';
        $.each(self.region, function(n, item) {
            province += '<option value="' + item.name + '">' + item.name + '</option>';
        });
        $('#province').html(province);
        $('#city').html('<option value="">请选择市</option>');
        $('#district').html('<option value="">请选择县/区</option>');
        layui.use('form', function() {
            var form = layui.form();
            var provinceVal = '',
                cityVal = '';
            form.render('select');
            form.on('select(province)', function(data) {
                provinceVal = data.value;
                var city = '<option value="">请选择市</option>';
                $.each(self.region, function(index, val) {
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
                cityVal = data.value;
                $.each(self.region, function(index, val) {
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
        });
    },
    saveAddr: function(param) {
        var self = this;
        $.ajax({
            url: yonghui.contextPath + '/api/invoice/createAddr.jsp', //请求添加数据的接口服务器地址
            data: param,
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function(res) {
                if (0 === res.errCode) {
                    layer.closeAll();
                    self.getData();
                } else {
                    layer.msg(res.errMsg);
                }
            }
        });
    },
    bindEvent: function() {
        var self = this;
        //新增地址
        $('button.add-Address').on('click', function() {
            layer.open({
                type: 1,
                area: ['auto', 'auto'],
                title: false,
                title: ['新增收件地址', 'color:#666;font-size:14px;font-weight:bold;'],
                shadeClose: true,
                content: $('#shippingAddressEdit'),
                success: function() {
                    self.selAddr();
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
        }).on('click', 'button[type="reset"]', function() {
            self.selAddr();
        });
        $('#addrList').on('click', 'tr', function() {
            $(this).addClass('active').siblings().removeClass('active');
        }).on('click', '.shipping-Address-Edit', function() {
            layer.open({
                type: 1,
                area: ['auto', 'auto'],
                title: false,
                title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'],
                shadeClose: true,
                content: $('#shippingAddressEdit'),
                success: function() {
                    self.selAddr();
                }
            });
            return false;
        }).on('click', '.shipping-Address-Delete', function() {
            return false;
        });
        layui.use('form', function() {
            var form = layui.form();
            form.on('submit(btnAddrSave)', function(data) {
                self.saveAddr(data.field);
                return false;
            });
        });
    },
    init: function() {
        this.getData();
        this.getAddr();
        this.bindEvent();
    }
}

$(function() {
    new Address();
});
