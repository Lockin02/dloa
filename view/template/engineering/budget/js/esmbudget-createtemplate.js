$(function() {
	alert('您好，首次使用请先配置一个费用模板');
	//显示费用类型
	initExpense();
});

//自定义费用选择功能 - 弹出选择
function initExpense(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=getCostType",
	    async: false,
	    success: function(data){
	   		if(data != ""){
				$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
				//延时调用排序方法
				setTimeout(function(){
					initMasonry();
				},200);
	   	    }else{
				alert('没有找到自定义的费用类型');
	   	    }
		}
	});
}

//瀑布流排序
function initMasonry(){
	$('#costTypeInner').masonry({
		itemSelector: '.box'
	});
}

//选择费用类型
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
	}else{
		$("#view" + thisCostType).attr('class','');
	}
	//判断类型是否存在，存在则干掉，不存在新增
	var trObj = $("input[type='checkbox']:checked");
	var contentArr = [];
	var contentIdArr = [];
	var content = "";
	var contentId = ""
	trObj.each(function(i,n){
		contentArr.push($(this).attr("name"));
		contentIdArr.push(this.value);
	});
	if(contentArr.length > 0){
		content = contentArr.toString();
		contentId = contentIdArr.toString();
	}
	$("#contentView").text(content);
	$("#content").val(content);
	$("#contentId").val(contentId);
}

//上下级渲染
function CostTypeShowAndHide(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//三级费用项目查看
function CostType2View(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType);
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//打开保存界面
function openSavePage(){
	var content = $("#content").val();
	if(content == ""){
		alert('没有任何选中值，请至少选择一项费用类型');
	}else{
		$('#templateInfo').dialog({
		    title: '保存模板',
		    width: 400,
		    height: 200,
   			modal: true,
		    closed: true
		}).dialog('open');
	}
}

//保存模板
function saveTemplate(){
	var content = $("#content").val();
	var templateName= $("#templateName").val();
    if(templateName){
    	//ajax保存模板信息
    	var contentId = $("#contentId").val();

		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_customtemplate&action=ajaxSave",
		    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
		    async: false,
		    success: function(data){
		   		if(data != ""){
		   			alert('保存成功');
		   			location.reload();
		   	    }else{
					alert('保存失败');
		   	    }
			}
		});
    }else{
    	if(strTrim(templateName) == ""){
			alert('请输入报销模板名称');
			$("#templateName").focus();
    	}
    }
}
