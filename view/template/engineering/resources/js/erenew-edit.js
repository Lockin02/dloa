$(document).ready(function() {
	// ��Ա��Ⱦ
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	//�ӱ���Ϣ
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
			name : 'number',
			display : '����',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '��λ',
			width : 60,
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
			width : 120,
			isSubmit : true,
			tclass : 'readOnlyTxt',
			readonly : true
		}]
	});
});