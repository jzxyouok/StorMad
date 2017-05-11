/**
 * 
 */

 
$(function() {

	//初始化全局变量
	var pageNo=1;
	var userName='';
	var status='';

	//初始化数据
	list(pageNo,userName,status);
	
	//表达验证与提交
	layui.use('form', function(){
		var form = layui.form();
		
		//提交新增表单
		form.on('submit(sub)', function(data){
			var val=data.field;
			var url=yonghui.contextPath + '/api/admin/create.jsp';
			
			if(val.password.length<6 || val.password.length>10)
			{
				layer.alert('密码字符大于6位且小于10位');
				return false;
			}
			if(val.username.length<1 || val.username.length>10)
			{
				layer.alert('用户名大于1位且小于10位');
				return false;
			}
			
			$.ajax({
				type : 'POST',
				url  : url,
				data : {'userName':val.username, 'password':val.password, 'trueName':val.truename},
				dataType : 'json',
				success : function(data) {
					if(data.errCode == 0) {
						layer.msg("添加成功");
						location.reload();
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
		
		
		//提交重置密码表单
		form.on('submit(sub_respws)', function(data){
			var val=data.field;
			var url=yonghui.contextPath + "/api/admin/resetPwd.jsp";

			$.ajax({
				type : 'POST',
				url  : url,
				data : {tuin:val.uid,password:val.new_pws},
				dataType : 'json',
				success : function(data) {
					if(data.errCode == 0) {
						layer.msg("重置密码成功");
						layer.closeAll('page');	
					} else {
						layer.alert(data.errMsg);
					}
				},
				error : function(data) {
					layer.alert('操作异常');
				}
			});
			
		  	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
		});		

		//查询表单提交
		form.on('submit(search)', function(data){
			var val=data.field;
			
			list(1,val.ser_name,val.ser_status)
			
		  	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
		});
		
	});

});

function list(pageNo,userName,status)
{
	//分页模块
	layui.use('laypage', function() {
		var laypage = layui.laypage,
			layer = layui.layer;
		
		var redata={pageNo:pageNo,pageSize:yonghui.pageSize,userName:userName,status:status};
		
		//初始化分页参数
		$.post(yonghui.contextPath + "/api/admin/query.jsp",redata,function(data){
				val=data.obj;
					  
				laypage({
					cont: 'pageNumber',
					groups: yonghui.groups,
					pages: val.pageCount,
					jump: function(obj, first){
						if(first) {
							get_info(val);
						} else {
							query(obj.curr,userName,status);
						}
					}
				});
		});			
	});
}

//关闭窗口
function back_list(obj)
{
	layer.closeAll('page');	
}

//打开重置密码窗口
function open_respws(uid)
{
	layer.open({
		type: 1,
		area: ['auto', 'auto'],
		title: ['重置密码', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
		shadeClose: true, //点击遮罩关闭
		content: $('#resetPassword')
	});	
	
	$("#uid").val(uid);
}

//停用,启动
function set_status(id,status)
{
	var url=yonghui.contextPath + "/api/admin/freeze.jsp";

	$.post(url,{tuin:id,status:status},function(data){
			if(data.errCode==0){
				layer.msg("操作成功");
				
				if(status==1)
				{
					$("#start_"+id).addClass("active-status");
					$("#start_"+id).removeAttr("onclick");
					$("#stop_"+id).attr("onclick","set_status("+id+",0)");
					$("#stop_"+id).removeClass("active-status");
					$("#sta_"+id).text("正常");
				}
				else
				{
					$("#stop_"+id).addClass("active-status");
					$("#stop_"+id).removeAttr("onclick");
					$("#start_"+id).attr("onclick","set_status("+id+",1)");
					$("#start_"+id).removeClass("active-status");
					$("#sta_"+id).text("停用");	
				}
			}else{
				layer.alert(data.errMsg);
				return false	
			}
		});
}

/*  分页查询数据*/
function get_info(data)
{
	var val=data.record;

	var htm="";
	
	for(var i=0;i<val.length;i++)
	{
		htm +='<tr>';
		var stopact='';
		var startact='';
		
		htm +='<td>'+val[i].admUin+'</td><td>'+val[i].trueName+'('+val[i].userName+')</td>';

		if(val[i].status==1)
		{
			htm +='<td id="sta_'+val[i].admUin+'">正常</td>';
			startact='class="active-status"';
			stopact='onclick="set_status('+val[i].admUin+',0)"';
		}
		else
		{
			htm +='<td id="sta_'+val[i].admUin+'">停用</td>';
			stopact='class="active-status"';
			startact='onclick="set_status('+val[i].admUin+',1)"'
		}
		
		htm +='<td><a class="reset-Password" href="javascript:void(0)" onclick="open_respws('+val[i].admUin+')" >重置密码</a> | <a href="javascript:void(0)" id="stop_'+val[i].admUin+'" '+stopact+' >停用</a> | <a href="javascript:void(0)"  id="start_'+val[i].admUin+'" '+startact+' >启用</a></td>';
		
		htm +='</tr>';
	}
	
	$("#admin_info").html(htm);
}	

function query(pageNo,userName,status) {

	var redata={pageNo:pageNo,pageSize:yonghui.pageSize,userName:userName,status:status};

	$.post(yonghui.contextPath + "/api/admin/query.jsp",redata,function(data){  
		if(data.errCode == 0) {
			get_info(data.obj);
		} else {
			layer.alert('查询列表失败!\r\n' + data.errMsg);
		}
	});
};