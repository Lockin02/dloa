$(document).ready(function() {
	// 人员渲染
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});
	
	// 从表初始化
	$("#importTable").yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=selectdeviceInfo',
		param : {
			rowsId : $("#rowsId").val()
		},
		isAddAndDel : false,
		objName : 'erenew[erenewdetail]',
		tableClass : 'form_in_table',
		colModel : [ {
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
			name : 'maxNum',
			display : '在借数量',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60,
			process:function($input,row){
				$input.val(row.number);
			}
		}, {
			name : 'number',
			display : '续借数量',
			width : 60,
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
            name : 'resourceListId',
            display : 'resourceListId',
            isSubmit : true,
            type : 'hidden'
        }, {
            name : 'borrowItemId',
            display : '借用单明细id',
            isSubmit : true,
            type : 'hidden'
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
			width : 90,
			isSubmit : true,
			tclass : 'readOnlyTxt',
			readonly : true,
			process:function($input,row){
				$input.val(row.notse);
			}
		}]
	});
});