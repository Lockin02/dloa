/**
 * @author lzyy <healdream@gmail.com>
 * @copyright 2007 lzyy
 * @license GPL
 * @version 1.0.0
 */
(function($){
	$.fn.multiSelect = function(options){
		
		var opts = $.extend({},$.fn.multiSelect.defaults,options);	
		
		return this.each(function(){
		
			$this = $(this);
			$.fn.multiSelect.createSelectDiv($this.attr("id"),opts);
		
	});
	}
	
	//创建div
	$.fn.multiSelect.createSelectDiv = function(select_id,opts){
		
		//固定select的宽，以免被撑大
		$("#"+select_id).width($("#"+select_id).width());
		
		//取得select的jquery对象
		var $obj=$("#"+select_id);
		//取得该select的坐标对象
		var offset = $obj.offset();
		//获取select的title值
		var title = $obj.attr("title")==undefined?"请选择":$obj.attr("title");
		//如果是IE的话将该div往上移一个像素
		if($.browser.msie){
			offset.top -=1;
		}
		//开始构建div
		var div_str = "<div id="+select_id+"_div class='select_div' style='width:"+opts.width+"px;display:none;position:absolute;top:"+offset.top+"px;left:"+offset.left+"px'>";
		//构建iframe，用来在IE里挡住select，如果iframe参数为true的话
		var iframeStr='<iframe src="javascript:false" style="display:none;position:absolute; visibility:inherit;border:0px; top:0px; left:-1px; width:'+(opts.width+20)+'; height:100%; z-index:-1;-moz-opacity:0; filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\';"></iframe>';
		if(opts.iframe){
			if($.browser.msie){
				div_str += iframeStr;
			}
		}
		
		div_str += "<div class='select_div_title'>"+title+"</div>";
		div_str += "<table class='select_div_table' id="+select_id+"_table width=100% cellpadding=0 cellspacing=0>";
		//创建table内容tr和td
		div_table_trtd = $.fn.multiSelect.createTrTd(select_id);
		div_str += div_table_trtd+"</table><div class='select_div_bottom'><input id="+select_id+"_ok type=button value='确定'></div></div>";
		//添加到页面上
		$(div_str).appendTo('body');
		
		//如果div过高，则显示滚动条
		if($("#"+select_id+"_div").height()>opts.height){
			$("#"+select_id+"_div").height(opts.height);
			//IE这个老家伙总是要来点hack才舒服
			if($.browser.msie){
				$("#"+select_id+"_div .select_div_title").width($("#"+select_id+"_div").width()-10);
				$("#"+select_id+"_div .select_div_bottom").width($("#"+select_id+"_div").width()-16);
			}
		}
		
		//定义打开函数
		$.fn.multiSelect.opener(select_id,opts);
		//定义div里的checkbox点击事件
		$.fn.multiSelect.checkboxClick(select_id);
		//定义确定按钮的点击事件
		$.fn.multiSelect.okClick(select_id,opts);
	}
	
	$.fn.multiSelect.checkboxClick=function(select_id){
		//获取div的jquery对象
		$obj = $("#"+select_id+"_div :checkbox");
		
		//定义点击事件
		$obj.click(function(){
			
			//改变背景颜色
			if($(this).attr("checked")){
				$(this).parent().parent().addClass("selected");
			}
			else{
				$(this).parent().parent().removeClass("selected");
			}
			
			//这个算法较之前的有所改进，判断起来更加方便，效率也更高
			$checked_obj = $("#"+select_id+"_div :checkbox:checked");
			//如果当前只有一个checkbox被选中，使select里对应的option被选中，同时去掉新添加的option（如果有的话）
			if($checked_obj.length==1){
				$("#"+select_id+" option[@value="+$checked_obj.val()+"]").attr("selected","selected");
				$("#"+select_id+" option[@added]").remove();
			}
			//如果当前没有被选中项，去掉新添加的option（如果有的话）
			else if($checked_obj.length==0){
				$("#"+select_id+" option[@added]").remove();
			}
			//如果多个checkbox被选中，依次取得text和value，然后添加或者编辑option
			else{
				var val='';
				var text='';
				for(var i=0;i<$checked_obj.length;i++){
					val += $checked_obj.eq(i).val()+",";
					text += $checked_obj.eq(i).attr("text")+",";
					
				}
				val = val.substr(0,val.length-1);
				text = text.substr(0,text.length-1);
				
				if($("#"+select_id+" option[@added]").length!=0){
					
					$("#"+select_id+" option[@added]").val(val);
					$("#"+select_id+" option[@added]").text(text);
				}
				else{
					var option = "<option added='true' value='"+val+"'>"+text+"</option>";
					$(option).appendTo('#'+select_id);
				}
			}
		});
	}
	
	//定义确定按钮的点击事件，点击后div消失
	$.fn.multiSelect.okClick=function(select_id,opts){
		$("#"+select_id+"_ok").click(function(){
			$("#"+select_id+"_div").slideUp("fast",function(){
				if(!opts.iframe){
				$("#"+select_id).css("visibility","visible");
			}
			});
			
		});
	}
		
	//定义打开div函数
	$.fn.multiSelect.opener = function(select_id,opts){
		$obj = $("#"+select_id+"_open");
		
		$obj.click(function(){
			//关掉所有打开的div
			$(".select_div").slideUp("fast");
			$("select").css("visibility","visible");
			if(opts.iframe){
				//定义iframe的css
				if($.browser.msie){
					$("#"+select_id+"_div iframe").css("display","block").css("height",$("#"+select_id+"_div").height());
				}
			}
			else{
				$("#"+select_id).css("visibility","hidden");
			}
			$("#"+select_id+"_div").slideDown("fast");
		});
	}
	
	//创建tr和td
	$.fn.multiSelect.createTrTd = function(select_id){
		var $child = $("#"+select_id);
		var childLength = $child.children().length;
		var trtd = "";
		for(var i=0;i<childLength;i++){
			text = $child.children().eq(i).text();
			val = $child.children().eq(i).val();
			if(i%2==0){
				var bg_class="odd";
			}else{
				var bg_class="even";
			}
			trtd += "<tr class='"+bg_class+"'><td>"+text+"</td><td class='right_td'><input text="+text+" type=checkbox value="+val+"></td></tr>";
		}
		return trtd;
	}
	

	$.fn.multiSelect.defaults = {
		width:300,
		height:200,
		iframe:true
	}

})(jQuery);

