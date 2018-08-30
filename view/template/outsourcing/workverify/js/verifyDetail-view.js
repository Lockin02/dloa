
$(document).ready(function() {

	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcing :''
		},
		dir : 'ASC',
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50
				},  {
					display : '����ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : 'ʡ��',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : 'ʡ��ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '����',
					name : 'outsourcing',
					type : 'select',
					datacode:'HTWBFS',
					width : 100
				},{
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '�����˾ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '�ܽ���',
					name : 'scheduleTotal',
					type : 'txt',
					width : 70
				},{
					display : '���ڽ���',
					name : 'presentSchedule',
					type : 'txt',
					width : 70
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val()
		},
		dir : 'ASC',
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50
				},  {
					display : '����ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : 'ʡ��',
					name : 'provinceId',
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : 'ʡ��ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '�����˾ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '����',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true
				}, {
					display : '����ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '���ڿ�ʼ',
					name : 'beginDate',
					type : 'date',
					width : 70
				},{
					display : '���ڽ���',
					name : 'endDate',
					type : 'date',
					width : 70
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					type : 'txt',
					width : 60
				}]
	});

 });

