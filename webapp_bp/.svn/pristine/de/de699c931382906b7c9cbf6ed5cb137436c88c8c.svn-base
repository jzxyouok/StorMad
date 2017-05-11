/*支付状态相应展示*/
function payState() {
    var paystate = window.location.search;
    if (paystate == '?result=success') {
        $('.account .status-success').css({ 'display': 'block' }).siblings().css({ 'display': 'none' });
    } else if (paystate == '?result=fail') {
        $('.account .status-fail').css({ 'display': 'block' }).siblings().css({ 'display': 'none' });
    } else {
        $('.account .status-box').css({ 'display': 'none' });
    }
}
/*时间戳转换函数*/
Date.prototype.format = function(format) {
        var date = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3),
            "S+": this.getMilliseconds()
        };
        if (/(y+)/i.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
        }
        for (var k in date) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
            }
        }
        return format;
    }
    /*取出session值，填充充值成功的信息*/
var dsno, finishTime, status;

function getStore() {
    /*页面跳转标识*/
    sessionStorage.setItem("fromState", "fromPayState");
    var money = sessionStorage.getItem("money");
    var wxUrl = sessionStorage.getItem("wxUrl");
    var acctName = sessionStorage.getItem("acctName", acctName);
    var balance = sessionStorage.getItem("balance", balance);
    var tradeno = sessionStorage.getItem("tradeno");
    status = sessionStorage.getItem("status");
    dsno = tradeno;
    /*充值成功时间戳转换*/
    finishTime = sessionStorage.getItem("finishTime", finishTime);
    var successTime = new Date(Number(finishTime));
    finishTime = successTime.format('yyyy年MM月dd日 hh:mm');
    $('.account .recharge-ok .name').html(acctName);
    $('.account .recharge-ok .time').html(finishTime);
    $('.account .recharge-ok .money').html('￥' + (money * 1).toFixed(2));
    if (money || wxUrl || acctName || balance || finishTime || status) {
        sessionStorage.getItem("money", "");
        sessionStorage.getItem("wxUrl", "");
        sessionStorage.getItem("acctName", "");
        sessionStorage.getItem("balance", "");
        sessionStorage.getItem("finishTime", "");
        sessionStorage.getItem("status", "");
    }
}
/*判断是否从微信支付页面跳转*/
function fromWechat() {
    var from = sessionStorage.getItem('from');
    if (from == "account-wechat-pay") {
        $('.account .status-success').css({ 'display': 'block' }).siblings().css({ 'display': 'none' });
    }
}
/*判断充值成功时间*/
function judgeFinishTime() {
    if (status == 1) {
        clearInterval();
    } else {
        setInterval("judgeState()", 50);
        // 充值成功的账户余额
        getBalance();
    }
}
$(document).ready(function() {
    getStore();
    judgeFinishTime();
    payState();
    fromWechat();
});
