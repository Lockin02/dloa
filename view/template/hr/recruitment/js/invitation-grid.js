var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};

$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '����֪ͨ',
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'parentCode',
			display : 'Դ�����',
			sortable : true
		},{
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 30
		},{
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		},{
			name : 'email',
			display : '��������',
			sortable : true
		},{
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		},{
			name : 'deptName',
			display : '���˲���',
			sortable : true
		},{
			name : 'interviewDate',
			display : '����ʱ��',
			sortable : true
		},{
			name : 'interviewPlace',
			display : '���Եص�',
			sortable : true
		},{
			name : 'stateC',
			display : '״̬',
			sortable : true
		},{
			name : 'interviewerName',
			display : '�������Թ�',
			sortable : true
		},{
			name : 'hrInterviewer',
			display : '�������Թ�',
			sortable : true
		},{
			name : 'userWrite',
			display : '��������',
			sortable : true
		},{
			name : 'interview',
			display : '��������',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "Դ�����",
			name : 'parentCode'
		},{
			display : "ӦƸ������",
			name : 'applicantName'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "�������",
			name : 'resumeCode'
		},{
			display : "��ϵ�绰",
			name : 'phone'
		},{
			display : "��������",
			name : 'email'
		},{
			display : "ӦƸ��λ",
			name : 'positionsName'
		},{
			display : "���˲���",
			name : 'deptName'
		},{
			display : "����ʱ��",
			name : 'interviewDateSea'
		},{
			display : "���Եص�",
			name : 'interviewPlace'
		},{
			display : "�������Թ�",
			name : 'interviewerName'
		},{
			display : "�������Թ�",
			name : 'hrInterviewer'
		}]
	});
});