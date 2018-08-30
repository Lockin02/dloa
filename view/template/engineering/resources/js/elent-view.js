$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_elentdetail&action=listJson',
//		title : '设备需求明细',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '设备类型',
			width : 120,
			name : 'resourceTypeName',
			isSubmit : true
		}, {
			name : 'resourceName',
			width : 120,
			display : '设备名称',
			isSubmit : true
		}, {
			name : 'coding',
			display : '机身码',
			width : 120,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '部门编码',
			width : 100,
			isSubmit : true
		}, {
			name : 'number',
			display : '数量',
			width : 60,
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			width : 60,
			isSubmit : true	
		}, {
			name : 'beginDate',
			display : '预计领用日期',
			width : 80,
			isSubmit : true
		}, {
			name : 'endDate',
			display : '预计归还日期',
			width : 80,
			isSubmit : true
		}, {
			name : 'realDate',
			display : '实际转借日期',
			width : 80,
			isSubmit : true
		}, {
			name : 'remark',
			display : '备注',
			width : 120,
			isSubmit : true
		}]
	})
});