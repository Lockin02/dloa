var show_page = function(page) {
	$("#categoryItem").yxgrid("reload");
};
$(document).ready(function() {
	var showType = $("#showTypeHidden").val();
	if(showType == 1){
		$("#showType").find("option[text='列表显示']").attr("selected",true);
	}else if(showType == 2){
		$("#showType").find("option[text='分组显示']").attr("selected",true);
		$("#typeShow").show();
	}else if(showType == 3){
		$("#showType").find("option[text='表单显示']").attr("selected",true);
	}else if(showType == 4){
		$("#showType").find("option[text='直接输入']").attr("selected",true);
	}else if(showType == 5){
		$("#showType").find("option[text='填写表格']").attr("selected",true);
	}

	var type = $("#typeHidden").val();
	if(type == 1){
		$("#type").find("option[text='勾选']").attr("selected",true);
	}else if(type == 2){
		$("#type").find("option[text='文本']").attr("selected",true);
	}
	var categoryId = $("#id").val();
	$("#categoryItem").yxeditgrid( {
		objName : 'category[items]',
		url : '?model=yxlicense_license_categoryitem&action=listJson',
		title : '从表信息',
		param : {
			categoryId : categoryId,
			'dir' : 'ASC'
		},
		colModel : [{
			name : 'id',
			tclass : 'txt',
			display : 'id',
			sortable : true,
			type : "hidden"
		},{
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
	
	if($("#isHideTitleValue").val() == '1') {
		$("#isHideTitle").attr("checked","checked");
	};
	
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

//必填
function changeShowType(){
	var showType = $("#showType").val();
	var itemTableObj = $("#categoryItem");
	if(showType == 2){
		itemTableObj.yxeditgrid("getCmpByCol","groupName").addClass('validate[required]');
	}else{
		itemTableObj.yxeditgrid("getCmpByCol","groupName").removeClass('validate[required]');
	}
}