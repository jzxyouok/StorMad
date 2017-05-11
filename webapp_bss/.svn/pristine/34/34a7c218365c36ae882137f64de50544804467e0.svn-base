/**
 * 
 */
$(function() {
	var getVCode=function(vid) {
		$.ajax({
			type : 'GET',
			url  : yonghui.contextPath + '/api/createCode.jsp',
			data : {},
			dataType : 'json',
			success : function(data) {
				var imgUrl = yonghui.contextPath + '/api/showCode.jsp?id=' + data.obj; 
				$('#code-pic').attr('src', imgUrl);
				$('#vid').val(data.obj);
			},
			error :function(data) {
				alert('获取验证码失敗!\r\n');
			}
		});
	}
	
	$('#code-pic').click(function() {
		getVCode();
	});
	
	$('#btnSubmit').click(function(){
		var loginName = $('#user').val();
		if(loginName == '') {
			alert('请输入用户名');
			$('#user').focus();
			return false;
		}
		
		var password = $('#password').val();
		if(password == '') {
			alert('请输入密码');
			$('#password').focus();
			return false;
		}
		
		var vcode = $('#code').val();
		if(vcode == '') {
			alert('请输入验证码');
			$('#code').focus();
			return false;
		}
		var id = $('#vid').val();

		$.ajax({
			type : 'POST',
			url  : yonghui.contextPath + '/api/admin/login.jsp',
			data : {'userName':loginName, 'password':password, 'id':id, 'vcode':vcode},
			dataType : 'json',
			success : function(data) {
				if(data.errCode == 0) {
					location.href = yonghui.contextPath + '/index.html';
				} else {
					alert(data.errMsg);
				}
			},
			error : function(data) {
				alert('登录失败11');
			}
		});
	});
	
	//打开页面时获取验证码
	getVCode();
});