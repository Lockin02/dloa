
$(function() {
	$("#weeklogGrid").yxgrid({
		model : 'rdproject_worklog_rdworklog',
		title : '��־��ѯ���',
		param : {
			"taskId" : $("#taskId").val(),
			"executionDate" : $("#executionDate").val(),
			"createId" : $("#createId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectId',
				display : '��Ŀid',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 130,
				hide : true
			}, {
				name : 'taskName',
				display : '��������',
				sortable : true,
				width : 130
			}, {
				name : 'createName',
				display : '�ձ���д��',
				sortable : true,
				width : 80
			}, {
				name : 'effortRate',
				display : '���������',
				sortable : true,
				process : function (v){
					return v + " %";
				},
				width : 80
			}, {
				name : 'workloadDay',
				display : '����Ͷ�빤����',
				sortable : true,
				process : function (v){
					return v + " Сʱ";
				},
				width : 80
			}, {
				name : 'workloadSurplus',
				display : 'Ԥ��ʣ��',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " Сʱ";
				},
				hide : true
			}, {
				name : 'planEndDate',
				display : 'Ԥ�����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'executionDate',
				display : 'ִ������ ',
				sortable : true,
				width : 80
			}, {
				name : 'description',
				display : '�������� ',
				width : 170,
				sortable : true
			}, {
				name : 'problem',
				display : '�������� ',
				width : 170,
				sortable : true
			}, {
				name : 'updateTime',
				display : '��дʱ��',
				sortable : true,
				width : 130,
				hide : true
			}
		],
		// ��չ�Ҽ��˵�
		menusEx : [
			{
				text : '�鿴',
				icon : 'view',
				action : function(row, rows, grid) {
					showThickboxWin("?model=rdproject_worklog_rdworklog&action=viewWorkLog&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		]
	});
});