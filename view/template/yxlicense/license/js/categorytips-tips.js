var show_page = function(page) {
	$("#categoryformGrid").yxgrid("reload");
};
$(function() {
	var id = $("#formId").val();
	var titlesArr = getTitles();
	var optionsArr = getOptions();
	var categoryformGrid = $("#categoryformGrid");
	
	categoryformGrid.yxeditgrid({
		url : '?model=yxlicense_license_categorytips&action=listJson',
		param : {formId:$("#formId").val(),
			'dir' : 'ASC'},
		async : true,
		objName : 'categorytips',
		// 列信息
		colModel : [{
			name : 'id',
			display : 'id',
			sortable : true,
			type : 'hidden'
		},{
			name : 'titleId',
			display : '业务细分列表',
			sortable : true,
			width : 150,
			type : 'select',
			options : titlesArr,
			emptyOption : true,
			event : {
				change : function(){
					rowNum = $(this).data("rowNum");//行号				
					var titleName = $(this).find("option:selected").text();
					categoryformGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"titleName").val(titleName);
				}
			}
		}, {
			name : 'titleName',
			display : '业务细分',
			type : 'hidden',
			value : $("#titleName").val()
		}, {
			name : 'optionId',
			display : '选项列表',
			width : 150,
			type : 'select',
			options : optionsArr,
			emptyOption : true,
			event : {
				change : function(){
					rowNum = $(this).data("rowNum");//行号					
					var optionName = $(this).find("option:selected").text();
					categoryformGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"optionName").val(optionName);
				}
			}
		}, {
			name : 'optionName',
			display : '选项',
			type : 'hidden',
			value : $("#optionName").val()
		}, {
			name : 'tips',
			display : '备注',
			tclass : 'txt',
			width : 160
		}, {
			name : 'isDisable',
			display : '禁用',
			checked : false,
			type : 'checkbox',
			checkVal : '1'
		}]
	});
});

function getTitles() {
	var responseText = $.ajax({
		url : 'index1.php?model=yxlicense_license_categorytitle&action=listJson',
		data : {formId:$("#formId").val(),
			'dir' : 'ASC'},
		type : "POST",
		async : false
		}).responseText;
	var dataArr = eval("(" + responseText + ")");
	var newArr = [];
	if (dataArr.length > 0) {
		for ( var i = 0; i < dataArr.length; ++i) {
			newArr.push({
				"name" : dataArr[i].titleName,"value" : dataArr[i].id
			});
		}
	}
	return newArr;
}

function getOptions() {
	var responseText = $.ajax({
		url : 'index1.php?model=yxlicense_license_categoryoptions&action=listJson',
		data : {formId:$("#formId").val(),
			'dir' : 'ASC'},
		type : "POST",
		async : false
		}).responseText;
	var dataArr = eval("(" + responseText + ")");
	var newArr = [];
	if (dataArr.length > 0) {
		for ( var i = 0; i < dataArr.length; ++i) {
			newArr.push({
				"name" : dataArr[i].optionName,"value" : dataArr[i].id
			});
		}
	}
	return newArr;
}