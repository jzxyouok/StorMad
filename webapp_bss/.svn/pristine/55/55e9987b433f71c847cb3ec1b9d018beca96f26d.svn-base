/**
 * 
 */
$(function() {

	//分页模块
	layui.use('laypage', function() {
		var laypage = layui.laypage,
			layer = layui.layer;
	
	
		
		//初始化分页参数
		$.post(yonghui.contextPath + "/api/ad/adlocation/findAdLocationPage.jsp?pageSize="+yonghui.pageSize,function(data){
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

/*  分页查询数据
* 	curr   当前页
*/
function get_info(curr)
{
	//请求url
	var url=yonghui.contextPath + "/api/ad/adlocation/findAdLocationPage.jsp?pageNo="+curr+"&pageSize="+yonghui.pageSize;
	
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
					
					var specinfo=val[i].adSize;
										
					htm +='<td>'+val[i].alName+'</td><td>永辉</td><td>'+adtypename+'</td>';

					if(adtype==1)
					{
					   htm +='<td>'+specinfo.asName+'('+adtypename+'：宽：'+specinfo.width+'px&nbsp;高：'+specinfo.height+'px)</td>';
					}
					else
					{
					    htm +='<td>'+specinfo.asName+'('+adtypename+'：'+specinfo.textMaxLength+')</td>';	
					}
					
					if(val[i].alStatus==1)
					{
						htm +='<td>停用</td>'
					}
					else
					{
						htm +='<td>启动</td>'		
					}
					
					htm +='</tr>';
				}
				
				$("#area_info").html(htm);
			}
		}
		else
		{
			layer.alert(data.errMsg);
			return false	
		}
	});
}	