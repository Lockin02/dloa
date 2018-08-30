var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};

$(function() {
	$("#weeklogGrid").yxgrid({
		model : 'rdproject_worklog_rdworklog',
//		action : 'pageJsonWorklogResult',
		title : '��־��ѯ���',
		param : {
			"projectIds" : $("#projectIds").val(),
			"memberIds" : $("#memberIds").val(),
			"beginDate" : $("#beginDate").val(),
			"overDate" : $("#overDate").val()
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
				width : 130
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
				width : 90
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
		buttonsEx : [
	        {
				name : 'back',
				text : "���ز�ѯҳ",
				icon : 'edit',
				action : function() {
					location.href = "?model=rdproject_worklog_rdweeklog&action=searchLogForManeger";
				}
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
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		],
		searchitems : [{
			display : '��������',
			name : 'wtaskName'
		},{
			display : '��Ŀ����',
			name : 'wprojectName'
		},{
			display : '��д��',
			name : 'wcreateName'
		},{
			display : 'ִ������',
			name : 'executionDate'
		}]
	});
});