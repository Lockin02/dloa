/*
 * selectbox
 *
 * @author    yebiao
 * @version   1.0
 * @since     2011-8-5
 * @filename  jquery-selectbox.js
 * @email     yebiao@myhexin.com
 *
 */
function SelectBoxClass(){
    this.selectedElement=null;
    this.state = false;
    this.name = '';
    this.name_hdn = '';
    this.cssid = '';
    //生成div层
    this.changeListState = function(event,element,divid){
     this.name = element.name;
     this.name_hdn = "#"+this.name+"_hdn";
     var e = event ? event : window.event;
     this.selectedElement = e.srcElement || e.target ;
     this.cssid = "#"+divid;
     $(this.cssid).css('left',this.selectedElement.offsetLeft + "px");
     $(this.cssid).css('top',(this.selectedElement.offsetTop+22)+"px");
     var display = $(this.cssid).css('display');
     $(this.cssid).css('display',(display=="block"?"none":"block"));
     if($(this.cssid).css('display')=="block"){
        changeCheckboxShow($(this.name_hdn).val(),this.name);
     }else{
        changeCheckboxHide();
     }

    };
    //改变读取的值
    this.changeSelected = function(option,event){
    	$(this.name_hdn).val(getallchecked(this.name));
    	this.selectedElement.value = getallcheckedname(this.name);
    };
    //隐藏
    this.hiddenList = function(){
     if(!this.state){
    	$(this.cssid).hide("fast");
        changeCheckboxHide(this.name);
     }
    };
    //显示下拉框
    function changeCheckboxShow(value,name){
     changeCheckboxHide();
     var splitarr = value.split(",");
     var num1 = splitarr.length;
     for(var i=0;i<num1;i++){
    	$("input[type='checkbox'][name='"+name+"'][value='"+splitarr[i]+"']").attr("checked",true);
     }
    }

    function changeCheckboxHide(name){
     $("input[type='checkbox'][name='"+name+"']").each(function(){
        this.checked=false;
     });
    }
    //获取勾选中的值，用逗号相隔
   function getallchecked(name){
     var cs = "";
     $("input[type='checkbox'][name='"+name+"']").each(function(){
        if(this.checked){
         cs = cs+","+this.value;
        }
     });
     //去掉最后的逗号
     if(cs.length>1){
        cs = cs.substring(1);
     }
     return cs;
   }
   //获取勾选中的名字,显示在页面中
   function getallcheckedname(name){
     var cs = "";
     var chkval = '';
     $("input[type='checkbox'][name='"+name+"']").each(function(){
        if(this.checked){
   	     chkval = $(this).next();
         cs = cs+","+chkval.text();
        }
     });
     if(cs.length>1){
        cs = cs.substring(1);
     }
     return cs;
   }
}
var s = new SelectBoxClass();

(function($){
	$.fn.selectboxs = function selectboxs(){
		var select_name = $(this).attr("id");
		var select_name_hdn = select_name+"_hdn";
		var select_div_id = select_name+"_list";
		var str = "<input type='hidden' name='"+select_name_hdn+"' id='"+select_name_hdn+"'>";
		str += "<input type='text' name='"+select_name+"' class='t_selected' onclick=s.changeListState(event,this,'"+select_div_id+"'); id='s1' onmouseover='s.state=true;' onmouseout='s.state=false;' onblur='s.hiddenList()' readonly='readonly'/>";
		str += "<div class='t_select_list' id='"+select_div_id+"' onmouseover='s.state=true;' onmouseout='s.state=false;'>";
		str += "<div class='t_select_list_table'>";
		var selectHtml = $(this).html();
		str += selectHtml+"</div>";
		str += "<div style='height:25px;width:180px;margin-top:2px;margin-bottom:1px;' align='center'>"+
			"<input name='"+select_name+"' style='border: #777EEE 1px solid;background:#CEFFFF;width:66px;height:24px;word-spacing:4;' type='button' onclick=s.changeListState(event,this,'"+select_div_id+"'); value='关闭'/>"+
			"</div></div>";
		$(this).html('');
		$(this).append(str);
	};
})(jQuery);