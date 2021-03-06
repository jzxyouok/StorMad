/**
 * 
 */				

$(function() {

	//初始化
	$("#spec_img").hide();
	$("#spec_font").hide();
	$("#spec_qr_code").hide();

	//获取广告规格
	get_spec_type();
	
	//表达验证与提交
	layui.use('form', function(){
		var form = layui.form();
		
		form.on('select(spec_type)', function(data){
			if(data.value==1)
			{
				$("#spec_img").show();
				$("#spec_font").hide();
				$("#spec_qr_code").hide();	
				
				$("#qr_code").val("");
				$("#max_font").val("");
			}
			if(data.value==2)
			{
				$("#spec_font").show();
				$("#spec_img").hide();
				$("#spec_qr_code").hide();
				
				$("#img_weight").val("");
				$("#img_height").val("");
				$("#qr_code").val("");
			}
			if(data.value==3)
			{
				$("#spec_qr_code").show();
				$("#spec_img").hide();
				$("#spec_font").hide();
				
				$("#img_weight").val("");
				$("#img_height").val("");
				$("#max_font").val("");
			}
		});
		
		form.verify({
			spec_name: function(value){
				if(value==""){
				return '请输入规格名称';
				}
			},
			spec_type: function(value){
				if(value==""){
				return '请选择规格类型';
				}
			},
			img_weight: function(value){
				if(value=="" && $("#spec_img").is(":visible")){
				return '请输入图片宽度';
				}
				if(isNaN(value) && $("#spec_img").is(":visible")){
				return '图片宽度必须为纯数字';
				}
			},
			img_height: function(value){
				if(value=="" && $("#spec_img").is(":visible")){
				return '请输入图片高度';
				}
				if(isNaN(value) && $("#spec_img").is(":visible")){
				return '图片高度必须为纯数字';
				}
			},
			max_font: function(value){
				if(value=="" && $("#spec_font").is(":visible")){
				return '请输入文字最大字符数';
				}
				if(isNaN(value) && $("#spec_font").is(":visible")){
				return '文字字符数必须为纯数字';
				}
			},
			qr_code: function(value){
				if(value=="" && $("#spec_qr_code").is(":visible")){
				return '请输入二维码最大字符数';
				}
				if(isNaN(value) && $("#spec_qr_code").is(":visible")){
				return '二维码字符数必须为纯数字';
				}
			}		
		});
		
		//提交表单
		form.on('submit(sub_spec)', function(data){
			var val=data.field;
			var info={};
			if(val.spec_type==1)
			{
				info={'asName':val.spec_name, 'adType':val.spec_type, 'width':val.img_weight, 'height':val.img_height}
			}
			if(val.spec_type==2)
			{
				info={'asName':val.spec_name, 'adType':val.spec_type, 'textMaxLength':val.max_font}
			}
			if(val.spec_type==3)
			{
				info={'asName':val.spec_name, 'adType':val.spec_type, 'textMaxLength':val.qr_code}
			}
			
			$.ajax({
				type : 'POST',
				url  : yonghui.contextPath + '/api/ad/adsize/addAdSize.jsp',
				data : info,
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
			
			return false;
		});
		

		//编辑广告规格
		form.on('submit(sub_spec_edit)', function(data){
			console.log(data);
			var val=data.field;
			var id=$("#asId").val();
			
			var info={};
			if(val.spec_type==1)
			{
				info={'asId':id,'asName':val.spec_name, 'adType':val.spec_type, 'width':val.img_weight, 'height':val.img_height}
			}
			if(val.spec_type==2)
			{
				info={'asId':id,'asName':val.spec_name, 'adType':val.spec_type, 'textMaxLength':val.max_font}
			}
			if(val.spec_type==3)
			{
				info={'asId':id,'asName':val.spec_name, 'adType':val.spec_type, 'textMaxLength':val.qr_code}
			}
			
			$.ajax({
				type : 'POST',
				url  : yonghui.contextPath + '/api/ad/adsize/updateAdSize.jsp',
				data : info,
				dataType : 'json',
				success : function(data) {
					if(data.errCode == 0) {
						layer.msg("修改成功");
						 //location.reload();
					} else {
						layer.alert(data.errMsg);
					}
				},
				error : function(data) {
					layer.alert('修改异常');
				}
			});
			
			return false;
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
		$.post(yonghui.contextPath + "/api/ad/adsize/findAdSizePage.jsp?pageSize="+yonghui.pageSize,function(data){
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

/*获取广告规格类型
* sel 当前选中
*/
function get_spec_type(sel)
{
	  $.ajax({
	  type : 'POST',
	  url  : yonghui.contextPath + "/api/ad/getAllAdType.jsp",
	  dataType : 'json',
	  success : function(data) {
			  if(data.errCode==0)
			  {
				  var val=data.obj;
		  
				  if(val.length>0)
				  {
					  for(var i=0;i<val.length;i++)
					  {
							$("#spec_type").append("<option value="+val[i].first+">"+val[i].second+"</option>");
					  }
				  }			
			  }
			  else
			  {
				  layer.alert(data.errMsg);
				  return false	
			  }
	  },
	  error : function(data) {
		  layer.alert('添加异常');
	  }
  });
}


//删除广告规格				
function del_spec(id)
{
	var url=yonghui.contextPath + '/api/ad/adsize/deleteAdSize.jsp';
	
	$.post(url,{asId:id},function(data){
		  if(data.errCode==0)
		  {
			if(data.obj==true)
			{
				layer.msg("删除成功");
				location.reload();
			} 
			else
			{
				layer.alert(data.errMsg);	
			}
		  }
		  else
		  {
			layer.alert(data.errMsg);  
		  }	
	});
}

//显示编辑窗口
function show_edit(id)
{
	layer.open({
		type: 1,
		area: ['680px', 'auto'],
		title: ['编辑规格', 'color:#666;font-size:14px;font-weight:bold;'], //自定义标题
		shadeClose: true, //点击遮罩关闭
		content: $('#addSpecification')
	});
	
	//获取广告规格信息
	var url=yonghui.contextPath + '/api/ad/adsize/getOneAdSize.jsp';
	
	$.post(url,{asId:id},function(data){
		  if(data.errCode==0)
		  {
				var info=data.obj;
				var adTypeobj=info.adType.split(","); 
				var adtype=adTypeobj[0];
				var adtypename=adTypeobj[1];		
						
				$("#spec_name").val(info.asName);
				$("#asId").val(info.asId);
				
				$("#spec_type").val(2);
				$("#sub_spec").attr("lay-filter","sub_spec_edit");
				
				//get_spec_type(2);
						
				if(adtype==1)
				{
					$("#spec_img").show();
					$("#spec_font").hide();
					$("#spec_qr_code").hide();
					
					$("#img_weight").val(info.width);
					$("#img_height").val(info.height);
					$("#max_font").val("");
					$("#qr_code").val("");			
				}
				if(adtype==2)
				{
					$("#spec_font").show();
					$("#spec_img").hide();
					$("#spec_qr_code").hide();
					
					$("#max_font").val(info.textMaxLength);
					$("#img_weight").val("");
					$("#img_height").val("");
					$("#qr_code").val("");		
				}
				if(adtype==3)
				{
					$("#spec_qr_code").show();
					$("#spec_img").hide();
					$("#spec_font").hide();
					
					$("#qr_code").val(info.textMaxLength);	
					$("#img_weight").val("");
					$("#img_height").val("");
					$("#max_font").val("");				
				}
		  }
		  else
		  {
				layer.alert(data.errMsg);  
		  }	
	})
}

/*  分页查询数据
* 	curr   当前页
*/
function get_info(curr)
{
	//请求url
	var url=yonghui.contextPath + "/api/ad/adsize/findAdSizePage.jsp?pageNo="+curr+"&pageSize="+yonghui.pageSize;
	
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
					
					var adTypeobj=val[i].adType.split(","); 
					var adtype=adTypeobj[0];
					var adtypename=adTypeobj[1];
					
					htm +='<td>'+val[i].asName+'</td><td>'+adtypename+'</td>';
						  
					if(adtype==1)
					{
					   htm +='<td>宽：'+val[i].width+'px&nbsp;高'+val[i].height+'px</td>';
					}
					if(adtype==2)
					{
						 htm +='<td>'+adtypename+':'+val[i].textMaxLength+'</td>';	
					}
					if(adtype==3)
					{
						 htm +='<td>'+adtypename+':'+val[i].textMaxLength+'</td>';	
					}

					htm +='<td>'+getLocalTime(val[i].createTime)+'</td><td>'+getLocalTime(val[i].optime)+'</td><td>'+val[i].operator+'</td><td><a class="specification-Edit" href="javascript:void(0)" onclick="show_edit('+val[i].asId+')" >编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0)" onclick="del_spec('+val[i].asId+')" >删除</a></td>'
					htm +='</tr>';
				}
				
				$("#spec_info").html(htm);
			}
		}
		else
		{
			layer.alert(data.errMsg);
			return false	
		}
	});
}	

//格式化时间戳
function getLocalTime(nS) { 
	return new Date(parseInt(nS)).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ").replace(/下午/g, " "); 
} 