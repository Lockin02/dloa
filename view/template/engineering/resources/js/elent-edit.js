$(document).ready(function() {
	//人员渲染
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'elent'
	});
	
	//接收人渲染
	$("#receiverName").yxselect_user({
		hiddenId : 'receiverId',
		isGetDept : [true, "receiverDeptId", "receiverDept"],
		formCode : 'elent'
	});

	//工程项目渲染
//	$("#projectCode").yxcombogrid_esmproject({
//		hiddenId : 'projectId',
//		nameCol : 'projectCode',
//		isShowButton : false,
//		height : 250,
//		gridOptions : {
//			isTitle : true,
//			showcheckbox : false,
//			param : {'statusArr' : 'GCXMZT02,GCXMZT01'},
//			event : {
//				'row_dblclick' : function(e,row,data) {
//					$("#projectName").val(data.projectName);
//					$("#place").val(data.place);
//					$("#managerName").val(data.managerName);
//					$("#managerId").val(data.managerId);
//				}
//			}
//		},
//		event : {
//			'clear' : function() {
//				$("#projectName").val('');
//				$("#place").val('');
//				$("#managerName").val('');
//				$("#managerId").val('');
//			}
//		}
//	});

	//从表初始化
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_elentdetail&action=listJson",
		param : {"mainId" : $("#id").val()},
		isAddAndDel : false,
		objName : "elent[elentdetail]",
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : '设备类型',
			width : 100,
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
			width : 80,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '单位',
			width : 80,
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
			display : '预计领用时间',
			width : 100,
			isSubmit : true,
			type: 'date',
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

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyType" : {
			required : true
		},
		"reason" : {
			required : true
		},
		"receiverName" : {
			required : true
		}
	});
	//接收项目渲染
	$("#rcProjectCode").yxcombogrid_esmproject({
		hiddenId : 'rcProjectId',
		isShowButton : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr' : 'GCXMZT02,GCXMZT01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#rcProjectName").val(data.projectName);
					$("#rcManagerName").val(data.managerName);
					$("#rcManagerId").val(data.managerId);
					$("#rcContractType").val(data.contractType);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#rcProjectName").val('');
				$("#rcManagerName").val('');
				$("#rcManagerId").val('');
				$("#rcContractType").val('');
			}
		}
	});
});