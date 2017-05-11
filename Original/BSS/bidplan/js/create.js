$(function(){
	
	
	//提交表单
		form.on('submit(sub_spec)', function(data){
			var val=data.field;
			
			$.ajax({
				type : 'POST',
				url  : yonghui.contextPath + '/api/bidplan/create.jsp',
				data : {'asName':val.spec_name, 'adType':val.spec_type, 'width':val.img_weight, 'height':val.img_height},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					if(data.errCode == 0) {
						layer.msg("添加成功");
					} else {
						layer.alert(data.errMsg);
					}
				},
				error : function(data) {
					layer.alert('添加异常');
				}
			});
			
		  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
		});
		
		
})
