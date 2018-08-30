$(function() {
	$("#esmworklogGrid").yxeditgrid("remove").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		param : {
			projectId : $("#projectId").val(),
			beginDateThan : $("#beginDate").val(),
			endDateThan : $("#endDate").val(),
			createId : $("#createId").val()
		},
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
				display : 'ִ������',
				name : 'executionDate',
				width : 70
			}, {
				display : '���',
				name : 'createName',
				width : 80
			}, {
				display : '��Ŀ����',
				name : 'projectName',
				width : 120,
				align : 'left'
			},{
				display : '��������',
				name : 'activityName',
				width : 120,
				align : 'left'
			},{
				display : '������',
				name : 'workloadDay',
				width : 60
			}, {
				display : '��λ',
				name : 'workloadUnitName',
				width : 40
			}, {
				display : '�����չ',
				name : 'thisActivityProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '��Ŀ��չ',
				name : 'thisProjectProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '�˹�Ͷ��ռ��',
				name : 'inWorkRate',
				width : 70,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '����',
				name : 'costMoney',
				width : 60,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}
				}
			}, {
				display : '��������',
				name : 'description',
				align : 'left'
			}, {
				display : '��˽��',
				name : 'assessResultName',
				width : 60
			}, {
				display : '��˽���',
				name : 'feedBack',
				align : 'left'
			}
		]
	});
});