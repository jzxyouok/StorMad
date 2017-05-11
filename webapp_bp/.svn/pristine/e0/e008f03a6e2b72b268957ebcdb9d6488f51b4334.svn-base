$(document).ready(function() {

	//判断当前是否有收件地址，如果没有，显示提示信息。

	//三级联动



    function saveDate() {

        var province = $('select[name="province"]').val(); //省
        var city = $('select[name="city"]').val(); //城市
        var district = $('select[name="district"]').val(); //区域
        var address = $('#street').val(); //街道小区
        var phone = $('#name').val(); //收件人姓名
        var consignee = $('#phone').val(); //收件人电话

        $.ajax({

            // url: 'http://testbp.stormad.cn/api/invoice/createAddr.jsp',
            url: yonghui.contextPath + '/api/invoice/createAddr.jsp', //请求添加数据的接口服务器地址
            data: 'province=' + province + '&city=' + city + '&district=' + district + '&address=' + address + '&phone=' + phone + '&consignee=' + consignee,
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function(data) {
                //输出返回的json数据
                alert(data);
                alert(data.obj);
                alert(data.errCode);
                alert(data.errMsg);
            }

        });
    }



});
