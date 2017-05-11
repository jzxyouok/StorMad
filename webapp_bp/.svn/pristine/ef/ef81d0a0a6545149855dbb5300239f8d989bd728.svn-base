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
    this.pageNumFlag = true;
    this.region = {};
    this.updateAddrId = '';
    this.delAddrId = '';
    this.pageSize = yonghui.pageSize;

    this.addr = '';
    this.name = '';
    this.phone = '';
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
                    if (self.pageNumFlag) {
                        self.setPageNumber(res.obj.pageCount);
                        self.pageNumFlag = false;
                    }
                    var addrList = '',
                        addrItem = '<tr data-addr="$addrid$"><td><span class="checked"><i></i></span></td><td class="name">$consignee$</td><td class="addr">$addr$</td><td class="phone">$phone$</td><td><a class="shipping-Address-Edit" href="javascript:void(0)">编辑</a><a class="shipping-Address-Delete" href="javascript:void(0)">删除</a></td></tr>';
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
    /**
     * [分页]
     * @param {[type]} pageCount [总页数]
     */
    setPageNumber: function(pageCount) {
        var self = this;
        layui.use('laypage', function() {
            var laypage = layui.laypage;
            laypage({
                cont: 'pageNumber',
                pages: pageCount,
                skin: '#e6614f',
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
     * [地址：省市区详细地址拼接]
     * @param  {[type]} item [地址记录]
     */
    joinAddr: function(item) {
        if (item.province === item.city) {
            return item.city + '市' + item.district + item.address;
        } else {
            return item.province + '省' + item.city + '市' + item.district + item.address;
        }
    },
    /**
     * [全国省市区]
     */
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
    /**
     * [地址三级联动]
     * @param  {[type]} provinceVal [修改地址——省]
     * @param  {[type]} cityVal     [修改地址——市]
     * @param  {[type]} districtVal [修改地址——区]
     */
    selAddr: function(provinceVal, cityVal, districtVal) {
        var self = this;
        var province = '<option value="">请选择省</option>';

        $('#city').html('<option value="">请选择市</option>');
        $('#district').html('<option value="">请选择县/区</option>');

        $.each(self.region, function(n, item) {
            province += '<option value="' + item.name + '">' + item.name + '</option>';
        });
        $('#province').html(province);
        provinceVal && $('#province').find('option[value="' + provinceVal + '"]').attr('selected', true);

        if (cityVal) {
            var city = '<option value="">请选择市</option>';
            var cityObj = self.region[$('#province').get(0).selectedIndex - 1].city;
            $.each(cityObj, function(n, item) {
                city += '<option value="' + item.name + '">' + item.name + '</option>';
            });
            $('#city').html(city);
            $('#city').find('option[value="' + cityVal + '"]').attr('selected', true);
        }
        if (districtVal) {
            var district = '<option value="">请选择县/区</option>';
            $.each(cityObj[$('#city').get(0).selectedIndex - 1].area, function(i, value) {
                district += '<option value="' + value + '">' + value + '</option>'
            });
            $('#district').html(district);
            $('#district').find('option[value="' + districtVal + '"]').attr('selected', true);
        }

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
    /**
     * [保存地址]
     * @param  {[type]} param  [请求参数]
     * @param  {[type]} isUpdate [值为'update'表示编辑地址]
     */
    saveAddr: function(param, isUpdate) {
        var self = this;
        var url = yonghui.contextPath + '/api/invoice/createAddr.jsp';
        isUpdate && (param.addrId = self.updateAddrId, url = yonghui.contextPath + '/api/invoice/updateAddr.jsp');
        $.ajax({
            url: url, //请求添加数据的接口服务器地址
            data: param,
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function(res) {
                if (0 === res.errCode) {
                    layer.closeAll();
                    self.pageNumFlag = true;
                    self.getData();
                } else {
                    layer.msg(res.errMsg);
                }
            }
        });
    },
    /**
     * [修改地址——填充地址信息]
     * @param  {[type]} addrid [地址id]
     */
    getAddrDetail: function(addrid) {
        var self = this;
        api.post('/api/invoice/findAddrById.jsp', {
            addrId: addrid
        }, function(res) {
            if (0 === res.errCode) {
                layer.open({
                    type: 1,
                    area: ['auto', 'auto'],
                    title: false,
                    title: ['编辑收件地址', 'color:#666;font-size:14px;font-weight:bold;'],
                    shadeClose: true,
                    content: $('#shippingAddressEdit'),
                    success: function() {
                        console.log(res);
                        self.selAddr(res.obj.province, res.obj.city, res.obj.district);
                        $('#address').val(res.obj.address);
                        $('#consignee').val(res.obj.consignee);
                        $('#phone').val(res.obj.phone);
                        $('#btnAddrSave').attr('lay-filter', 'btnAddrUpdate');
                    }
                });
            }
        });
    },
    /**
     * [删除地址]
     */
    delAddr: function() {
        var self = this;
        api.post('/api/invoice/delAddr.jsp', {
            addrId: self.delAddrId
        }, function(res) {
            if (0 === res.errCode) {
                layer.closeAll();
                layer.msg('删除成功', function() {
                	self.pageNumFlag = true;
                    self.getData();
                });
            } else {
                layer.closeAll();
                layer.msg(res.errMsg);
            }
        });
    },
    bindEvent: function() {
        var self = this;
        //新增地址
        $('button.add-Address').on('click', function() {
            $('#address').val('');
            $('#consignee').val('');
            $('#phone').val('');
            $('#btnAddrSave').attr('lay-filter', 'btnAddrUpdate');
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
        }).on('click', '#btnAddrUpdate', function() {
            self.updateAddr();
        }).on('click', '#btnDelConfirm', function() {
            self.delAddr();
        }).on('click', '#btnDelCancel', function() {
            layer.closeAll();
        }).on('click', '.apply-Invoice', function() {
            window.location.href = 'account-apply-invoice.html?addrid=' + $('#addrList tr.active').data('addr');
        });

        $('#addrList').on('click', 'tr', function() {
            $(this).addClass('active').siblings().removeClass('active');
            self.addr = $(this).find('.addr').text();
            self.name = $(this).find('.name').text();
            self.phone = $(this).find('.phone').text();
        }).on('click', '.shipping-Address-Edit', function() {
            self.updateAddrId = $(this).closest('tr').data('addr');
            self.getAddrDetail($(this).closest('tr').data('addr'));
            return false;
        }).on('click', '.shipping-Address-Delete', function() {
            self.delAddrId = $(this).closest('tr').data('addr');
            layer.open({
                type: 1,
                area: ['auto', 'auto'],
                title: false,
                title: ['删除收件地址', 'color:#666;font-size:14px;font-weight:bold;'],
                shadeClose: true,
                content: $('#shippingAddressDelete')
            });
            return false;
        });
        layui.use('form', function() {
            var form = layui.form();
            form.verify({
                phone: function(value) {
                    if (!/^1[34578]\d{9}$/.test(value)) {
                        return '手机号码格式错误';
                    }
                }
            });
            form.on('submit(btnAddrSave)', function(data) {
                self.saveAddr(data.field);
                return false;
            });
            form.on('submit(btnAddrUpdate)', function(data) {
                self.saveAddr(data.field, self.updateAddrId);
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
