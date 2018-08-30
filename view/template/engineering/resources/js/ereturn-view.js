$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '设备类型',
			name : 'resourceTypeName',
			width : 150,
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '设备名称',
			width : 150,
			isSubmit : true
		}, {
			name : 'coding',
			display : '机身码',
			width : 120,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '部门编码',
			width : 120,
			isSubmit : true
		}, {
			name : 'number',
			display : '申请数量',
			width : 80,
			isSubmit : true
		}, {
			name : 'confirmNum',
			display : '已归还数量',
			width : 80,
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			width : 80,
			isSubmit : true	
		}, {
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}]
	})
});