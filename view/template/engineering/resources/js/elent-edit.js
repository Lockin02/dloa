$(document).ready(function() {
	//��Ա��Ⱦ
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'elent'
	});
	
	//��������Ⱦ
	$("#receiverName").yxselect_user({
		hiddenId : 'receiverId',
		isGetDept : [true, "receiverDeptId", "receiverDept"],
		formCode : 'elent'
	});

	//������Ŀ��Ⱦ
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

	//�ӱ��ʼ��
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
			display : '�豸����',
			width : 100,
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'resourceName',
			width : 120,
			display : '�豸����',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'coding',
			display : '������',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'dpcoding',
			display : '���ű���',
			width : 100,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'number',
			display : '����',
			width : 80,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '��λ',
			width : 80,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'resourceId',
			display : '�豸ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '�豸����ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'beginDate',
			display : 'Ԥ������ʱ��',
			width : 100,
			isSubmit : true,
			type: 'date',
			validation : {
				required : true
			},
			tclass : 'Wdate'
		}, {
			name : 'endDate',
			display : 'Ԥ�ƹ黹����',
			width : 100,
			isSubmit : true,
			type: 'date',
			validation : {
				required : true
			},
			tclass : 'Wdate'
		}, {
			name : 'remark',
			display : '��ע',
			width : 120,
			isSubmit : true,
			tclass : 'readOnlyTxt',
			readonly : true
		}]
	});

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
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
	//������Ŀ��Ⱦ
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