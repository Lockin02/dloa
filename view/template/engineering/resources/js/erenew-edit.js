$(document).ready(function() {
	// 人员渲染
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	//从表信息
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_erenewdetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		objName : 'erenew[erenewdetail]',
		isAddAndDel : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : '设备类型',
			width : 120,
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext'
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
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}, {
            name : 'beginDate',
            display : '预计开始日期',
            width : 100,
            isSubmit : true,
            type : 'date',
            validation : {
                required : true
            },
            tclass : 'Wdate'
        }, {
            name : 'endDate',
            display : '预计归还日期',
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
			tclass : 'readOnlyTxt',
			readonly : true
		}]
	});
});