(function($) {
	$.fn.Gfselect = function(options) {
		$.fn.Gfselect.defaults = {
			isSelect: true,
			sPeed: 50,
			showVal: "span",
			Ram: 'input[type="hidden"]',
			sDiv: ".select",
			addAttr: "val",
			effect: "slide",
			faOption: "ol",
			option: "ol li",
			cDocHide: true,
			valFn: null,
			toValFn:false
		};
		var opts = $.extend({}, $.fn.Gfselect.defaults, options);
		return this.each(function() {
			var _this = $(this),
				sPeed = opts.sPeed;
			var sDiv = opts.sDiv;
			var effect = opts.effect;
			var sOl = $(opts.faOption, _this);
			var sLi = $(opts.option, _this);
			var allFaOption = $(opts.faOption, sDiv);
			var Ram = $(opts.Ram, _this);
			var showVal = $(opts.showVal, _this);
			var eFfect = [];
			var arrData = {};
			var sLiLen = sLi.length;
			var addAttr = opts.addAttr;	
			var toValFn = opts.toValFn;
			//效果
			switch (effect) {
			case "slide":
				eFfect[0] = "slideUp";
				eFfect[1] = "slideDown";
				break;
			case "fade":
				eFfect[0] = "fadeOut";
				eFfect[1] = "fadeIn";
				break;
			case "hide":
				eFfect[0] = "hide";
				eFfect[1] = "show";
				break;
			default:
			}
			//动态设置val值
			if(toValFn)	{
			  if($.isFunction(toValFn)){
			     //传入函数自定义设置
				 toValFn(sLiLen,sLi);
			  }						 
			  else{ 
			      if(toValFn=='default'){
					  for (var i = 0; i < sLiLen; i++)                    {
				    sLi.eq(i).attr(addAttr,function(){return i;});
			        }	  
				  }	
				  else{	
					for (var i = 0; i < sLiLen; i++)                 
				    {
				   sLi.eq(i).attr(addAttr,function(){
				    var str= new Array();
				    str=toValFn.split(",");
				    return str[i];	
				  })
				  }//for end
				 }
			  }	
			  
			}
			else{   
				
				
		 	}					
			//效果函数
			var doEffect = function(obj, j, e) {
					obj[eFfect[j]](sPeed)
				};
			_this.off("click");
			_this.on("click", function(e) {
				//效果选择
				if (sDiv) {
					doEffect($(allFaOption, sDiv), 0)
				}
				if (sOl.is(":hidden")) {
					doEffect(sOl, 1)
				} else {
					doEffect(sOl, 0)
				}
				
				//select 控件
				if (opts.isSelect) {
					//li click
					sLi.off("click");
					sLi.on("click", function(e) {
						doEffect(sOl, 0);
						var vTxt = $(this).text();
						showVal.text(vTxt);
						var value = $(this).attr(addAttr);
						Ram.val(value);
						var _1 = $(this).index();
						if ($.isFunction(opts.valFn)) {
						//外部可以调用这个函数来执行一些操作
						opts.valFn(_1, sLiLen, vTxt, value)
					    }
						e.stopPropagation()
					})
				} else {
					sOl.on("click", function(e) {						
						e.stopPropagation()
					})
				}
				//隐藏
				if (opts.cDocHide) {
					$(document).off("click");
					$(document).on("click", function() {
						doEffect(sOl, 0)
					})
				}
				e.stopPropagation()
			})
		})
	}
})(jQuery);
/*插件调用方法
 $(传入select包含元素，一般是.select).Gfselect({传入对象参数})
  参数说明
  addAttr: "val" //li 属性名称
  toValFn:""  // 属性值，传入一个字符串，用逗号隔开，为空的话，默认设置val=0,1,2,3,4,...等
  调用示例：   
  $(".select").Gfselect({toValFn:"sdfoej,dfoe,eife"});
*/
