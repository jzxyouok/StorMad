/**
 * 新建推广计划
 */
$(function(){
	
	//点击提交按钮事件
	$("#btnAdd").click(function() {
		var planName = $('#planName').val();
		if(planName == '') {
			alert('请输入推广计划名称');
			$('#planName').focus();
			return;
		}
		
		$.ajax({
			type : 'POST',
			url  : yonghui.contextPath + '/api/ad/spread/addSpreadPlan.jsp',
			data : {'spName':planName},
			dataType : 'json',
			success : function(data) {
				if(data.errCode == -10000) {
					alert('你尚未登录系统，不能操作');
					return;
				}
				alert('新增推广计划成功');
				layer.closeAll('page');	
			},
			error :function(data) {
				alert('新增推广计划失败!\r\n' + data.errMsg);
			}
		});
	});
});