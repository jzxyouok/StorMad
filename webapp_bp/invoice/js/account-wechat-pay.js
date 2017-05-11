/*展示充值金额和二维码*/
var dsno;

function getStore() {
    var money = sessionStorage.getItem("money");
    var wxUrl = sessionStorage.getItem("wxUrl");
    var tradeno = sessionStorage.getItem("tradeno");
    var finishTime = sessionStorage.getItem("finishTime");
    dsno = tradeno;
    $('.cash').html('￥' + money);
    $('.img-wxpay img').attr('src', wxUrl);
    if (money || wxUrl || tradeno || finishTime) {
        sessionStorage.getItem("money", "");
        sessionStorage.getItem("wxUrl", "");
        sessionStorage.getItem("tradeno", "");
        sessionStorage.getItem("finishTime", "");
    }
}
/*120秒停止循环*/
function stopInterval() {
    var startTime = new Date().getTime();
    var interval = setInterval(function() {
        if (new Date().getTime() - startTime > 120000) {
            clearInterval(interval);
            window.location.href="../../ad/account.html"
            return;
        } else {
            judgeState();
        }
    }, 1000);
}
$(document).ready(function() {
    getStore();
    stopInterval();
});
