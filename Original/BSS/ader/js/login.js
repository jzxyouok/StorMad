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
				layer.alert('获取验证码失敗!\r\n');
			}
		});
	}
	
	$('#code-pic').on('click',function() {
		getVCode();
	});
	
	//表达验证与提交
	layui.use('form', function(){
		var form = layui.form();

		form.verify({
			user: function(value){
				if(value==""){
				return '请输入用户名';
				}
			},
			password: function(value){
				if(value==""){
				return '请输入密码';
				}
			},
			code: function(value){
				if(value==""){
				return '请输入验证码';
				}
			}		
		});	
		
		//搜索表单提交
		form.on('submit(sub)', function(data){
			var val=data.field;

			
			$.ajax({
				type : 'POST',
				url  : yonghui.contextPath + '/api/admin/login.jsp',
				data : {'userName':val.user, 'password':val.password, 'id':val.vid, 'vcode':val.code},
				dataType : 'json',
				success : function(data) {
					if(data.errCode == 0) {
						location.href = yonghui.contextPath + '/index.html';
					} else {
						layer.alert(data.errMsg);
						getVCode();
					}
				},
				error : function(data) {
					layer.alert('登录失败');
					getVCode();
				}
			});	
			
		 	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
		});		
	});
	
	//打开页面时获取验证码
	getVCode();
});