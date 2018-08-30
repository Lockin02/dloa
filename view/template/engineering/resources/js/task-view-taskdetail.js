$(document).ready(function() {
	//����������ʾ
	var taskType = $("#taskType").val();
	$("#"+taskType+"list").show();

	$("#ZK").yxeditgrid({
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'ZK',
			'areaPrincipalId' : $("#userId").val()
		},
		objName : 'task[ZK]',
		colModel : [{
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '��չ',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
				}else{
				   return "";
				}
			}
		}]
	})

	//���깺/�����豸
	$("#DSG").yxeditgrid({
		objName : 'task[DSG]',
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'DSG',
			'areaPrincipalId' : $("#userId").val()
		},
		colModel : [{
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '��չ',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
				}else{
				   return "";
				}
			}
		}]
	})
//�޷�����
	$("#WFDP").yxeditgrid({
		objName : 'task[WFDP]',
		isAddAndDel : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		type:'view',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'WFDP',
			'areaPrincipalId' : $("#userId").val()
		},
		colModel : [{
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '��չ',
			name : 'makeProgress',
			type : 'statictext',
			width : 150
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
				}else{
				   return "";
				}
			}
		}]
	})

});


