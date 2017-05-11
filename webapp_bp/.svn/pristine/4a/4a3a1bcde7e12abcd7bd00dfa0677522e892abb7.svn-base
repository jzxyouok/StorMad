/**
 *
 */
$(function() {
    getAdList();
    $('.nav').on('click', 'li[role="presentation"]:nth-child(1)', function() {
        getAdList();
    }).on('click', 'li[role="presentation"]:nth-child(2)', function() {
        getAdList('next');
    }).on('click', 'li[role="presentation"]:nth-child(3)', function() {
        getAdList('previous');
    });
});
var queryBinds = function(pageNo, yearMonth) {
    var param = { pageNo: pageNo, pageSize: yonghui.pageSize };
    yearMonth && (param.yearMonth = yearMonth);
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/bidplan/queryBindList.jsp',
        data: param,
        dataType: 'json',
        success: function(data) {
            if (data.errCode == 0) {
                yearMonth ? fillTable(data.obj, yearMonth) : fillTable(data.obj);
            }
        },
        error: function(data) {
            layer.alert(data.errMsg);
        }
    });
};

var fillTable = function(page, yearMonth) {
    var tbl = '';
    var statusCN = '';
    var list = page.record;
    var startDate = '';
    var endDate = '';
    var startTime = '';
    var endTime = '';
    var period = '';

    $('#tblBinding tbody').html('');

    if (list.length > 0) {
        for (var i = 0; i < list.length; i++) {
            startDate = new Date(list[i].startDate);
            endDate = new Date(list[i].endDate);
            if (list[i].startTime == 0 && list[i].endTime == 86399999) {
                var period = "";
            } else {
                var period = list[i].startTime / 1000 / 3600 + '时&nbsp;至&nbsp;' + list[i].endTime / 1000 / 3600 + '时';
            }
            /*startTime = new Date(parseInt(list[i].startDate) + parseInt(list[i].startTime));
            endTime = new Date(parseInt(list[i].endDate) + parseInt(list[i].endTime));
            period = startTime.format('hh:mm') + " 至 " + endTime.format('hh:mm');*/

            tbl += '<tr>';
            tbl += '<td>' + list[i].bpName + '</td>';
            tbl += '<td>' + startDate.format('yyyy-MM-dd') + '至' + endDate.format('yyyy-MM-dd') + '<br>';
            tbl += '' + period + '</td>';
            tbl += '<td><span class="status-tips ad-Preview"><i class="tips-icon"></i><a href="javascript:seeAdPos(\'' + list[i].bpId + '\')">' + list[i].location + '<span></td>';
            tbl += '<td><span class="status-tips store-List"><i class="tips-icon"></i><a href="javascript:showShops(\'' + list[i].bpId + '\')">查看门店<a><span></td>';
            tbl += '<td>' + list[i].iName + '</td>';
            tbl += '<td>' + (list[i].bidPrice / 100).toFixed(2) + '</td>';
            tbl += '<td>' + list[i].adTitle + '</td>';
            tbl += '<td>' + list[i].statusCN + '</td>';
            if (list[i].status == 0) {
                tbl += '<td><a type="button" class="btn btn-default" href="javascript:bindAd(\'' + list[i].bpaId + '\', \'' + list[i].bpId + '\', \'' + list[i].location + '\', \'' + list[i].alId + '\')">立即绑定</a></td>';
            } else {
                tbl += '<td><a type="button" class="btn btn-default" href="javascript:unBindAd(\'' + list[i].bpaId + '\', \'' + list[i].adId + '\')">解除绑定</a></td>';
            }
            tbl += '</tr>';
        }
        if (yearMonth) {
            if ('next' === yearMonth) {
                $('.show_info_next').show();
                $('.no_info').hide();
                $('#next tbody').html(tbl);

            } else {
                $('.show_info_previous').show();
                $('.no_info').hide();
                $('#previous tbody').html(tbl);

            }
        } else {
            $('.show_info_now').show();
            $('.no_info').hide();
            $('#tblBinding tbody').html(tbl);

        }
    } else {
        $('.no_info').show();
    }

};
var getAdList = function(yearMonth) {
        layui.use(['laypage', 'layer'], function() {
            var laypage = layui.laypage,
                layer = layui.layer;
            var page = null;
            var param = { pageNo: 1, pageSize: yonghui.pageSize };
            yearMonth && (param.yearMonth = yearMonth);
            $.ajax({
                type: 'POST',
                url: yonghui.contextPath + '/api/bidplan/queryBindList.jsp',
                data: param,
                dataType: 'json',
                success: function(data) {
                    if (data.errCode == 0) {
                        page = data.obj;
                        laypage({
                            cont: yearMonth ? ('next' === yearMonth ? 'next-pageNumber' : 'pre-pageNumber') : 'now-pageNumber',
                            groups: yonghui.groups,
                            pages: page.pageCount,
                            skip: true,
                            skin: '#e6614f',
                            jump: function(obj, first) {
                                if (first) {
                                    fillTable(page, yearMonth);
                                } else {
                                    queryBinds(obj.curr);
                                }
                            }
                        });
                    }
                },
                error: function(data) {
                    layer.alert(data.errMsg);
                }
            });
        });
    }
    //绑定广告
var bindAd = function(bpaId, bpId, local, alId) {
    location.href = 'ad-binding.html?bpaId=' + bpaId + '&bpId=' + bpId + '&local=' + encodeURI(local) + '&alId=' + alId;

}

//解除广告和档期的绑定
var unBindAd = function(bpaId, adId) {
    var cancelPop = layer.open({
        type: 1,
        area: ['auto', 'auto'],
        //offset: 'rb', //弹窗右下角
        title: false, //隐藏默认标题
        title: ['提示', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        content: $('#unBindCancel')
    });
    /*取消撤销*/
    $('#btn-cancel').click(function() {
        parent.layer.close(cancelPop);
    });
    /*确定撤销*/
    $('#btn-submit').click(function() {
        $.ajax({
            type: 'POST',
            url: yonghui.contextPath + '/api/bidplan/bindAd.jsp?op=0',
            data: { 'bpaId': bpaId, 'adId': adId },
            dataType: 'json',
            success: function(data) {
                if (data.errCode == 0) {
                    layer.msg('解除绑定成功', {
                        time: 800
                    }, function() {
                        location.href = yonghui.contextPath + "/ad/ad-management.html";
                    });
                } else {
                    layer.alert(data.errMsg);
                }
            },
            error: function(data) {
                layer.alert(data.errMsg);
            }
        })
    })
};

//列出门店
var showShops = function(bpId) {
    $('#shops').empty();
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/bidplan/query.jsp',
        data: { 'pageNo': 1, 'pageSize': yonghui.maxPageSize, 'op': 1, 'bpId': bpId },
        dataType: 'json',
        success: function(data) {
            if (data.errCode == 0) {
                var lis = '';
                var shops = data.obj.shops;
                if (shops != null) {
                    for (var i = 0; i < shops.length; i++) {
                        lis += '<li>' + shops[i].shopName + '</li>';
                    }

                    layer.open({
                        type: 1,
                        area: ['340px', 'auto'],
                        title: false,
                        shadeClose: true,
                        closeBtn: 1,
                        content: '<div class="frame-content"><div class="frame-body store-list"><h3>该档期投放门店</h3><ul>' + lis + '</ul></div></div>'
                    });
                }
            }
        },
        error: function(data) {
            layer.alert(data.errMsg);
        }
    });
};
/**
 * [查看广告位置]
 * @param  {[type]} bpId [档期id]
 */
var seeAdPos = function(bpId) {
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/bidplan/query.jsp',
        dataType: 'json',
        data: {
            op: 1,
            bpId: bpId
        },
        success: function(res) {
            if (0 === res.errCode) {
                var adPosList = '';
                res.obj.locations && res.obj.locations.length && $.each(res.obj.locations, function(n, item) {
                    adPosList += '<a class="adGroup" href="' + item.sketchMap + '" title="' + item.alName + '"></a>';
                });

                $('#adPopup').html(adPosList);
                $(".adGroup").colorbox({
                    rel: 'adGroup',
                    transition: 'elastic',
                    current: '',
                    opacity: 0.6,
                    maxHeight: "90%"
                });
                $('#adPopup a:first').click();
            }
        }
    });
};
