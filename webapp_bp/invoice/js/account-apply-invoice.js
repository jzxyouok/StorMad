/*获取发票相关信息*/
var baId;

function getStoreInfo() {
    baId = sessionStorage.getItem("baId");
    var crtTime = sessionStorage.getItem("crtTime");
    var cash = sessionStorage.getItem("cash");
    $('.cash').html(cash);
    $('.crt-time').html(crtTime);
    if (crtTime || cash || name || baId) {
        sessionStorage.getItem("baId", "");
        sessionStorage.getItem("crtTime", "");
        sessionStorage.getItem("cash", "");
    }
}
/*获取发票抬头和开户银行*/
function getInvoiceTitle() {
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/invoice/invoiceTitle.jsp',
        data: { 'addrId': addrId, 'baId': baId },
        dataType: 'json',
        success: function(data) {
            if (data.errCode == 0) {
                invoiceTitle(data);
            } else {
                console.log("失败");
            }
        },
        error: function(data) {
            console.log("失败");
        }
    });
}

function invoiceTitle(data) {
    var data = data.obj;
    $('.apply-addr').html(data.address + '（' + data.onsignee + '&nbsp;&nbsp;收）');
    $('.apply-tel').html(data.phone);
    $('.apply-title').html(data.corporation);
    $('.apply-bank').html(data.bank);
    if (data.invoiceStatus == '1') {
        $(".btn-apply").attr("disabled", "disabled").css({ 'background-color': '#ccc', 'border': '1px solid #ccc' });
    }
}
/*点击申请发票按钮*/
function btnApply() {
    $('.btn-apply').click(function() {
        addInvoice();
    });
    /*关闭弹窗*/
    $('.btn-know').click(function() {
        parent.layer.close(applytips);
        window.location.href = "../../invoice/account-invoice-status.html";
    });
}


/*获取url参数*/
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}
var addrId, title;
addrId = getUrlParam("addrid");
// baId=baId;
title = '1';
var applytips;
/*申请发票*/
function addInvoice() {
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/invoice/apply.jsp',
        data: { 'addrId': addrId, 'baId': baId, 'title': title },
        dataType: 'json',
        success: function(data) {
            if (data.errCode == 0) {
                openTips();
            } else {
                parent.layer.close(applytips);
                layer.alert(data.errMsg);
            }
        },
        error: function(data) {
            console.log("失败");
        }
    });
}

/*支付成功提示弹框*/
function openTips() {
    applytips = layer.open({
        type: 1,
        area: ['auto', 'auto'],
        //offset: 'rb', //弹窗右下角
        title: false, //隐藏默认标题
        title: ['提示', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
        shadeClose: true, //点击遮罩关闭
        //content: '\<\div style="padding:20px;">自定义内容\<\/div>'
        content: $('#applyTips'),
        cancel:function(){
            window.location.href='../../invoice/account-invoice-status.html'
        }
    });
    /*申请发票按钮不可点击*/
    $(".btn-apply").attr("disabled", "disabled").css({ 'background-color': '#ccc', 'border': '1px solid #ccc' });
}
$(document).ready(function() {
    getStoreInfo();
    getInvoiceTitle();
    btnApply();
});
