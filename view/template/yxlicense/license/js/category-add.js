$(document).ready(function() {
	$("#categoryItem").yxeditgrid({
		objName : 'category[items]',
		isAddOneRow : true,
		title : '从表信息',
		colModel : [{
					name : 'itemName',
					tclass : 'txt',
					display : '明细名称',
					sortable : true,
					validation : {
						required : true
					}
				}, {
					name : 'groupName',
					display : '分组名',
					tclass : 'txt',
					sortable : true
				}, {
					name : 'appendShow',
					display : '扩展显示',
					width : 300,
					sortable : true
				}]
	});
	validate({
		"categoryName" : {
			required : true
		},
		"lineFeed" : {
			required : true
		}
	});
	
	//当选择类型为分组时显示勾选、文本选择框
	$("#showType").change(function(){
		if($("#showType").find("option:selected").val()=='2'){
			$("#typeShow").show();
		}
		else
			$("#typeShow").hide();
	});
	
	//选择分组展现方式-勾选/文本
	$("#type").change(function(){
		if($("#type").find("option:selected").val()=='1'){
			$("#type").val('1');
		}
		else
			$("#type").val('2');
	});
});

function changeShowType(){
	var showType = $("#showType").val();
	var itemTableObj = $("#categoryItem");
	if(showType == 2){
		itemTableObj.yxeditgrid("getCmpByCol","groupName").addClass('validate[required]');
	}else{
		itemTableObj.yxeditgrid("getCmpByCol","groupName").removeClass('validate[required]');
	}
}