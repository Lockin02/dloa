var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};
$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '����֪ͨ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			"interviewType":$("#interviewType").val(),
			parentId : $("#applyid").val()
		},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
				}
		}, {
			name : 'parentCode',
			display : 'Դ�����',
			width:130,
			sortable : true
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			width:70,
			sortable : true
		}, {
			name : 'sex',
			display : '�Ա�',
			width:60,
			sortable : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
				}
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'email',
			display : '��������',
			width:150,
			sortable : true
		}, {
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		}, {
			name : 'deptName',
			display : '���˲���',
			sortable : true
		}, {
			name : 'interviewDate',
			display : '����ʱ��',
			width:70,
			sortable : true
		}, {
			name : 'interviewPlace',
			display : '���Եص�',
			width:70,
			sortable : true
		}, {
			name : 'stateC',
			display : '״̬',
			width:70
		},{
			name : 'interviewerName',
			display : '���Թ�',
			sortable : true
		}],
		lockCol:['formCode','parentCode','applicantName'],//����������
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "����",
			name : 'applicantName'
		},{
			display : "ӦƸְλ",
			name : 'positionsName'
		},{
			display : "���˲���",
			name : 'deptName'
		}]
	});
});