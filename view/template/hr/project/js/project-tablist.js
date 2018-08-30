var show_page = function(page) {
	$("#tablistGrid").yxgrid("reload");
};
$(function() {
	$("#tablistGrid").yxgrid({
		model : 'hr_project_project',
		title : '��Ŀ����',
		param : {"userNo" : $("#userNo").val()},
		showcheckbox:false,
		isAddAction : false,
		isEditAction : false,
		isDelAction:false,
		isOpButton : false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width:80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_project_project&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'userName',
			display : 'Ա������',
			width:80,
			sortable : true
		}, {
			name : 'deptName',
			display : '��������',
			sortable : true
		}, {
			name : 'jobName',
			display : 'ְλ',
			sortable : true
		},{
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width:150
		}, {
			name : 'projectManager',
			display : '��Ŀ����',
			sortable : true
		},  {
			name : 'beginDate',
			display : '�μ���Ŀ��ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '�μ���Ŀ����ʱ��',
			sortable : true
		}],
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
						display : "����",
						name : 'deptNameSearch'
					},{
						display : "ְλ",
						name : 'jobNameSearch'
					},{
						display : "��Ŀ����",
						name : 'projectNameSearch'
					},{
						display : "��Ŀ����",
						name : 'projectManagerSearch'
					}]
	});
});