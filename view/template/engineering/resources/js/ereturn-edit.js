$(document).ready(function() {
	// ��Ա��Ⱦ
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	// ʵ�����ʼĹ�˾
	$("#expressName").yxcombogrid_logistics({
		hiddenId : 'expressId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [ {
			display : '�豸����',
			name : 'resourceTypeName',
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '�豸����',
			isSubmit : true
		}, {
			name : 'coding',
			display : '������',
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '���ű���',
			isSubmit : true
		}, {
			name : 'number',
			display : '����',
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
			isSubmit : true
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
		}]
	});
	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"areaId" : {
			required : true
		}
	});
});

// �ύȷ�ϸı�����ֵ
function setStatus(thisType) {
	$("#status").val(thisType);
}