var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};

$(function() {
	$("#weeklogGrid").yxgrid({
		model : 'rdproject_worklog_rdweeklog',
		action : 'pageJsonWorklogResult',
		title : '��־��ѯ���',
		param : {
			"w_projectId" : $("#projectIds").val(),
			"departmentIds" : $("#departmentIds").val(),
			"personIds" : $("#personIds").val(),
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
				width : 150
			}, {
				name : 'effortRate',
				display : '���������',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " %";
				}
			}, {
				name : 'workloadDay',
				display : '����Ͷ��',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " Сʱ";
				}
			}, {
				name : 'workloadSurplus',
				display : 'Ԥ��ʣ��',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " Сʱ";
				}
			}, {
				name : 'planEndDate',
				display : 'Ԥ�����',
				sortable : true,
				width : 80
			}, {
				name : 'executionDate',
				display : 'ִ������ ',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '��д��',
				sortable : true,
				width : 90
			}, {
				name : 'updateTime',
				display : '��дʱ��',
				sortable : true,
				width : 130
			}
		],
		buttonsEx : [
	        {
				name : 'back',
				text : "����",
				icon : 'edit',
				action : function() {
					history.back();
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