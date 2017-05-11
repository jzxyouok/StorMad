/**
 * 
 */
$(function() {

	//表达验证与提交
	layui.use('form', function(){
		var form = layui.form();
		
		//提交表单
		form.on('submit(sub)', function(data){
			var val=data.field;
			var url=yonghui.contextPath + '/api/admin/create.jsp';
			
			$.ajax({
				type : 'POST',
				url  : url,
				data : {'userName':val.username, 'password':val.password, 'trueName':val.truename},
				dataType : 'json',
				success : function(data) {
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
	});

	//关闭窗口
	$("#back_list").on('click',function(){
		layer.closeAll('page');	
	});
	
	//分页模块
	layui.use('laypage', function() {
		var laypage = layui.laypage,
			layer = layui.layer;

		//初始化分页参数
		$.post(yonghui.contextPath + "/api/admin/query.jsp?pageSize="+yonghui.pageSize,function(data){
				pages=data.obj.pageCount;
					  
				laypage({
					cont: 'pageNumber',
					pages: pages,
					skin: '#2089ff',
					groups: yonghui.groups,
					jump: function(obj, first){
					  if(!first){
						 get_info(obj.curr);
					  }
					  else
					  {
						 get_info(1); 
					  }
					}
				});
		});			
	});
});

//重置密码
function respsd(id)
{
	var url=yonghui.contextPath + "/api/admin/resetPwd.jsp"

	$.post(url,{tuin:id,status:status},function(data){
			if(data.errCode==0)
			{
				layer.msg("操作成功");
				
				if(status==1)
				{
					$("#start_"+id).css("style","layui-btn-disabled");	
				}
				else
				{
					$("#stop_"+id).css("style","layui-btn-disabled");		
				}
			}
			else
			{
				layer.alert(data.errMsg);
				return false	
			}
		});	
}

//停用,启动
function set_status(id,status)
{
	var url=yonghui.contextPath + "/api/admin/freeze.jsp";

	$.post(url,{tuin:id,status:status},function(data){
			if(data.errCode==0)
			{
				layer.msg("操作成功");
				
				if(status==1)
				{
					$("#start_"+id).css("style","layui-btn-disabled");	
				}
				else
				{
					$("#stop_"+id).css("style","layui-btn-disabled");		
				}
			}
			else
			{
				layer.alert(data.errMsg);
				return false	
			}
		});
}

/*  分页查询数据
* 	curr   当前页
*/
function get_info(curr)
{
	//请求url
	var url=yonghui.contextPath + "/api/admin/query.jsp?pageNo="+curr+"&pageSize="+yonghui.pageSize;
	
	//获取页码
	$.post(url,function(data){

		if(data.errCode==0)
		{
			var val=data.obj.record;
			
			if(val.length>0)
			{
				var htm="";
				for(var i=0;i<val.length;i++)
				{
					htm +='<tr>';
					
					htm +='<td>'+val[i].admUin+'</td><td>'+val[i].trueName+'('+val[i].userName+')</td>';

					if(val[i].status==1)
					{
						htm +='<td>正常</td>';
						var dis="disable"	
					}
					else
					{
						htm +='<td>停用</td>';	
					}
					
					htm +='<td><a class="reset-Password" href="javascript:void(0)" onclick="respsd('+val[i].admUin+')" >重置密码</a> | <a href="javascript:void(0)" onclick="set_status('+val[i].admUin+',0)" id="stop_'+val[i].admUin+'" >停用</a> | <a href="javascript:void(0)" onclick="set_status('+val[i].admUin+',1)" id="start_'+val[i].admUin+'">启用</a></td>';
					
					htm +='</tr>';
				}
				
				$("#admin_info").html(htm);
			}
		}
		else
		{
			layer.alert(data.errMsg);
			return false	
		}
	});
}	