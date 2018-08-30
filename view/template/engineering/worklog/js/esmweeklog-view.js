$(function() {
	if($("#exaResults").val() != ""){
		$(".auditInfo").show();
	}

	//������־
	$("#esmweeklogTable").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		title : '������־',
		param : {
			weekId : $("#weekId").val()
		},
		tableClass : 'form_in_table',
		colModel : [ {
				display : 'ִ������',
				name : 'executionDate',
				width : 80
			}, {
				display : '���ڵ�',
				name : 'provinceCity',
				width : 80
			}, {
				display : '����״̬',
				name : 'workStatus',
				width : 70,
				datacode : 'GXRYZT'
			}, {
				display : '��Ŀ����',
				name : 'projectName',
				width : 200
			},{
				display : '��������',
				name : 'activityName',
				width : 150
			},{
				display : '�����',
				name : 'workloadDay',
				width : 70
			}, {
				display : '����',
				name : 'costMoney',
				width : 70,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<a href='javascript:void(0)' onclick='viewCost(\"" + row.id + "\",1)' title='����鿴����'>" + moneyFormat2(v) + "</a>";
					}
				}
			}, {
				display : '��������',
				name : 'description'
			}
		]
	});
});

//����鿴����ҳ��
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "�鿴��־��Ϣ",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}