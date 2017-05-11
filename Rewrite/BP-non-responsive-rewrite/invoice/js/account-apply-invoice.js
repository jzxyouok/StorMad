
/*点击申请发票按钮*/
function apply(){
    $('.btn-apply').click(function(){
        console.log("点击");
        addInvoice();
    });
}

function addInvoice() {
    $.ajax({
        type: 'POST',
        url: yonghui.contextPath + '/api/invoice/apply.jsp',
        data: { 'addrId': '3', 'baId': '20161203154340331' ,'title':'1'},
        dataType: 'json',
        success: function(data) {
            if (data.errCode == 0) {
                console.log("成功");
                $('.applyTips').css({'display':'block'});
                // $('.btn-apply').addClass('apply-Tips');
            }else{
                $('.applyTips').css({'display':'block'});
                // $('.btn-apply').removeClass('apply-Tips');
                console.log("失败");
            }
        },
        error: function(data) {
            console.log("失败");
        }
    });
}
/**/
$(document).ready(function(){
    addInvoice();
    apply();
});