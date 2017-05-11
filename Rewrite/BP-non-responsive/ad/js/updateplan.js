/**
 * 新建推广计划
 */
$(function(){
	
	//点击提交按钮事件
	$("#btnUpdate").click(function() {
		var planName = $('#planName').val();
		if(planName == '') {
			alert('请输入推广计划名称');
			$('#planName').focus();
			return;
		}
		
		$.ajax({
			type : 'POST',
			url  : yonghui.contextPath + '/api/ad/spread/updateSpreadPlan.jsp',
			data : {'spName':planName},
			dataType : 'json',
			success : function(data) {
				alert(data.obj);
			},
			error :function(data) {
				alert('新增推广计划失败!\r\n' + data.errMsg);
			}
		});
	});
});