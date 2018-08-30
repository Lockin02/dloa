$(document).ready(function() {
	// ��Ա��Ⱦ
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});
	
	// �ӱ��ʼ��
	$("#importTable").yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=selectdeviceInfo',
		param : {
			rowsId : $("#rowsId").val()
		},
		isAddAndDel : false,
		objName : 'erenew[erenewdetail]',
		tableClass : 'form_in_table',
		colModel : [ {
			display : '�豸����',
			width : 120,
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
			name : 'maxNum',
			display : '�ڽ�����',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60,
			process:function($input,row){
				$input.val(row.number);
			}
		}, {
			name : 'number',
			display : '��������',
			width : 60,
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
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
            display : '���õ���ϸid',
            isSubmit : true,
            type : 'hidden'
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
			display : 'Ԥ�ƿ�ʼ����',
			width : 100,
			isSubmit : true,
			type : 'date',
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