$(document).ready(function() {
	//从表初始化
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_elentdetail&action=listJson",
		param : {"mainId" : $("#id").val()},
		isAddAndDel : false,
		objName : "elent[item]",
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'borrowItemId',
			name : 'borrowItemId',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'resourceListId',
			name : 'resourceListId',
			isSubmit : true,
			type: 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			display : '设备类型',
			width : 100,
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceName',
			width : 120,
			display : '设备名称',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'coding',
			display : '机身码',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'dpcoding',
			display : '部门编码',
			width : 100,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'number',
			display : '数量',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '单位',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'beginDate',
			display : '预计领用日期',
			width : 80,
			isSubmit : true,
			type: 'statictext'
		}, {
			name : 'endDate',
			display : '预计归还日期',
			width : 80,
			isSubmit : true,
			type: 'statictext'
		}, {
			name : 'realDate',
			display : '实际转借日期',
			width : 100,
			isSubmit : true,
			type: 'date',
			validation : {
				required : true
			},
			tclass : 'Wdate'
		}, {
			name : 'remark',
			display : '备注',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}]
	});

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({
		"applyUser" : {
			required : true
		}
	});
});

//确认时进行相关验证
function checkSubmit() {
	var objGrid = $("#importTable");
	//获取设备的行数
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//日期验证
	var today = $("#today").val();
	for(var i = 0; i < curRowNum ; i++){
		var realDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"realDate");

		if(DateDiff(realDateObj.val(),today) < 0){
			alert("实际转借日期不能晚于今天");
			realDateObj.focus();
			return false;
		}
	}
}