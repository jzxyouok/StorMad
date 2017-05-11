var ssl=1;	
function SelCity(obj,e) {

    var ths = obj;
    var dal = '<div class="_citys"><span title="关闭" id="cColse" >×</span><ul id="_citysheng" class="_citys0"><li class="citySel">省份</li><li>城市</li><li>区县</li></ul><div id="_citys0" class="_citys1"></div><div style="display:none" id="_citys1" class="_citys1"></div><div style="display:none" id="_citys2" class="_citys1"></div></div>';
	var clofan = '<a class="ArS" id="allcho" >不限</a><a class="ArS" id="chd" >确定</a>'; 
	var addall = '<a class="ArS" id="allcity" >不限</a>'; 
	var addregion = '<div class="region area_region'+ssl+'" ></div>';
	var adcol = '<div id="cori'+ssl+'" class="cori" onClick="colqu(this)">x</div>';
	var addinp = '<input id="ciy'+ssl+'" type="hidden"><span class="ciy'+ssl+'"></span>';
	 $("#addef").append(addregion);
	
    Iput.show({ id: ths, event: e, content: dal,width:"470"});
    $("#cColse").click(function () {
        Iput.colse();
		$(".area_region"+ssl).remove();
    });
    var tb_province = [];
    var b = province;
    for (var i = 0, len = b.length; i < len; i++) {
        tb_province.push('<a data-level="0" data-id="' + b[i]['id'] + '" data-name="' + b[i]['name'] + '">' + b[i]['name'] + '</a>');
    }
    $("#_citys0").append(tb_province.join(""));
    $("#_citys0 a").click(function () {
        var g = getCity($(this));
        $("#_citys1 a").remove();
        $("#_citys1").append(g);
        $("._citys1").hide();
        $("._citys1:eq(1)").show();
        $("#_citys0 a,#_citys1 a,#_citys2 a").removeClass("AreaS");
        $(this).addClass("AreaS");
        var lev = $(this).data("name");
        ths.value = $(this).data("name");
        if (document.getElementById("hcity"+ssl) == null) {
            var hcitys = $('<input>', {
                type: 'hidden',
                name: "hcity"+ssl,
                "data-id": $(this).data("id"),
                id: "hcity"+ssl,
                val: lev
            });
            $(".area_region"+ssl).append(hcitys);
        }
        else {
            $("#hcity"+ssl).val(lev);
            $("#hcity"+ssl).attr("data-id", $(this).data("id"));
        }
		$("#_citys1").append(addall);
		/***全选市**/
		$("#allcity").click(function () {
			
			var dq = lev + "-unlimited-unlimited"
				var dq2 = lev + "-不限-不限"
					 $(".area_region"+ssl).append(addinp);
					
					 $("#ciy"+ssl).val(dq);
					 $("#ciy"+ssl).attr("name",'area[]');
					  $(".ciy"+ssl).html(dq2);
					   $(".area_region"+ssl).append(adcol);
					   $('#ciy'+ssl).attr("form",'adinfo_submit'); 
					 ssl++;	
				Iput.colse();
			
		});
		
		
		/***全选市**/
        $("#_citys1 a").click(function () {
            $("#_citys1 a,#_citys2 a").removeClass("AreaS");
            $(this).addClass("AreaS");
            var lev =  $(this).data("name");
            if (document.getElementById("hproper"+ssl) == null) {
                var hcitys = $('<input>', {
                    type: 'hidden',
                    name: "hproper"+ssl,
                    "data-id": $(this).data("id"),
                    id: "hproper"+ssl,
                    val: lev
                });
               $(".area_region"+ssl).append(hcitys);
            }
            else {
                $("#hproper"+ssl).attr("data-id", $(this).data("id"));
                $("#hproper"+ssl).val(lev);
            }
            var bc = $("#hcity"+ssl).val();
            ths.value = bc+ "-" + $(this).data("name");

            var ar = getArea($(this));

            $("#_citys2 a").remove();
            $("#_citys2").append(ar);
            $("._citys1").hide();
            $("._citys1:eq(2)").show();
			
			$("#_citys2").append(clofan);
            $("#_citys2 a").click(function () {

			   $(this).toggleClass("AreaS");

            });
			$("#allcho").click(function () {
				 $("#_citys2 a").addClass("AreaS");
				var nuh;
					//alert($("#_citys2").children(".AreaS").data("name"));
					 $("#_citys2 a").each(function(){
						 if($(this).hasClass("AreaS")){
							 if ($(this).data("name") == null){ return false}	
							 if(nuh != undefined){
								  nuh = nuh + ',' + $(this).data("name");
								 }
							 if( nuh == undefined ){
								 nuh = $(this).data("name");
								 }
							
						 }
					 });
					 
					   if (document.getElementById("harea"+ssl) == null) {
                    var hcitys = $('<input>', {
                        type: 'hidden',
                        name: "harea"+ssl,
                        "data-id": $(this).data("id"),
                        id: "harea"+ssl,
                        val: nuh
                    });
                   $(".area_region"+ssl).append(hcitys);
                }
                else {
                    $("#harea"+ssl).val(lev);
                    $("#harea"+ssl).attr("data-id", $(this).data("id"));
                }
                var bc = $("#hcity"+ssl).val();
                var bp = $("#hproper"+ssl).val();
				
				
				var dq = bc + "-" + $("#hproper"+ssl).val() + "-unlimited"
				var dq2 = bc + "-" + $("#hproper"+ssl).val() + "-不限"
					 $(".area_region"+ssl).append(addinp);
					 
					 $("#ciy"+ssl).val(dq);
					 $("#ciy"+ssl).attr("name",'area[]');
					  $(".ciy"+ssl).html(dq2);
					   $(".area_region"+ssl).append(adcol);
					   $('#ciy'+ssl).attr("form",'adinfo_submit'); 
					 ssl++;	
				Iput.colse();
			});
			
			$("#chd").click(function () {
			
				var nuharr=[];
				var nuh='';
					//alert($("#_citys2").children(".AreaS").data("name"));
					 $("#_citys2 a").each(function(){
						 if($(this).hasClass("AreaS") && $(this).data("name"))
						 {
							 nuharr.push($(this).data("name"));
						 }
						 
					 });
					 
					 if(nuharr.length>0)
					 {
					 	nuh=nuharr.join(',');
					 }
					 else
					 {
						nuh='unlimited'; 
					 }
					   if (document.getElementById("harea"+ssl) == null) {
                    var hcitys = $('<input>', {
                        type: 'hidden',
                        name: "harea"+ssl,
                        "data-id": $(this).data("id"),
                        id: "harea"+ssl,
                        val: nuh
                    });
                   $(".area_region"+ssl).append(hcitys);
                }
                else {
                    $("#harea"+ssl).val(lev);
                    $("#harea"+ssl).attr("data-id", $(this).data("id"));
                }
                var bc = $("#hcity"+ssl).val();
                var bp = $("#hproper"+ssl).val();
				
				
				var dq = bc + "-" + $("#hproper"+ssl).val() + "-" + nuh
				var dq2 = bc + "-" + $("#hproper"+ssl).val() + "-" + nuh
				if(nuh=='unlimited')
				{
					dq=bc + "-" + $("#hproper"+ssl).val() + "-unlimited";
					dq2 = bc + "-" + $("#hproper"+ssl).val() + "-不限"
				}
				
				
					 $(".area_region"+ssl).append(addinp);
					
					 $("#ciy"+ssl).val(dq);
					 $("#ciy"+ssl).attr("name",'area[]');
					 $(".ciy"+ssl).html(dq2);
					  $(".area_region"+ssl).append(adcol);
					  $('#ciy'+ssl).attr("form",'adinfo_submit'); 
					 ssl++;	
				Iput.colse();
			});
				
			
        });
		
    });
    $("#_citysheng li").click(function () {
        $("#_citysheng li").removeClass("citySel");
        $(this).addClass("citySel");
        var s = $("#_citysheng li").index(this);
        $("._citys1").hide();
        $("._citys1:eq(" + s + ")").show();
    });
			
}

function colqu(obj) {
	obj.parentNode.outerHTML='';

	}


function getCity(obj) {
    var c = obj.data('id');
    var e = province;
    var f;
    var g = '';
    for (var i = 0, plen = e.length; i < plen; i++) {
        if (e[i]['id'] == parseInt(c)) {
            f = e[i]['city'];
            break
        }
    }
    for (var j = 0, clen = f.length; j < clen; j++) {
        g += '<a data-level="1" data-id="' + f[j]['id'] + '" data-name="' + f[j]['name'] + '" title="' + f[j]['name'] + '">' + f[j]['name'] + '</a>'
    }
    $("#_citysheng li").removeClass("citySel");
    $("#_citysheng li:eq(1)").addClass("citySel");
    return g;
}
function getArea(obj) {
    var c = obj.data('id');
    var e = area;
    var f = [];
    var g = '';
    for (var i = 0, plen = e.length; i < plen; i++) {
        if (e[i]['pid'] == parseInt(c)) {
            f.push(e[i]);
        }
    }
    for (var j = 0, clen = f.length; j < clen; j++) {
        g += '<a data-level="1" data-id="' + f[j]['id'] + '" data-name="' + f[j]['name'] + '" title="' + f[j]['name'] + '">' + f[j]['name'] + '</a>'
    }

    $("#_citysheng li").removeClass("citySel");
    $("#_citysheng li:eq(2)").addClass("citySel");
    return g;
}