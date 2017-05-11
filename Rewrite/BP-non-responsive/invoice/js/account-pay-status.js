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
/*取出session值，填充充值成功的信息*/
var dsno, finishTime, status;

function getStore() {
    /*页面跳转标识*/
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

/*判断充值成功时间*/
function judgeFinishTime() {
    if (status == 1) {
        clearInterval();
        sessionStorage.setItem('status', 0);
    } else {
        stopInterval();
        // setInterval("judgeState()", 50);
        // 充值成功的账户余额
        // getBalance();
    }
}

/*120秒停止循环*/
function stopInterval() {
    var startTime = new Date().getTime();
    var interval = setInterval(function() {
        if (new Date().getTime() - startTime > 120000) {
            clearInterval(interval);
            return;
        } else {
            judgeState();
            // 充值成功的账户余额
            getBalance();
        }
    }, 100);
}
$(document).ready(function() {
    getStore();
    judgeFinishTime();
    payState();

});
