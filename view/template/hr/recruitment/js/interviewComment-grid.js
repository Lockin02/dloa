var show_page = function(page) {
	$("#interviewCommentGrid").yxgrid("reload");
};
$(function() {
	$("#interviewCommentGrid").yxgrid({
		model : 'hr_recruitment_interviewComment',
		title : '��������',
		//����Ϣ
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'parentCode',
			display : 'Դ�����',
			sortable : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true
		}, {
			name : 'invitationCode',
			display : '����֪ͨ���',
			sortable : true
		}, {
			name : 'interviewType',
			display : '��������',
			sortable : true
		}, {
			name : 'userAccount',
			display : 'Ա���˺�',
			sortable : true
		}, {
			name : 'userName',
			display : '����',
			sortable : true
		}, {
			name : 'sexy',
			display : '�Ա�',
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
			name : 'projectGroup',
			display : '������Ŀ��',
			sortable : true
		}, {
			name : 'useWriteEva',
			display : '����-��������',
			sortable : true
		}, {
			name : 'interviewEva',
			display : '����-��������',
			sortable : true
		}, {
			name : 'interviewer',
			display : '���Թ�',
			sortable : true
		}, {
			name : 'interviewDate',
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
			display : "����",
			name : 'userName'
		},{
			display : "ӦƸ��λ",
			name : 'positionsName'
		},{
			display : "���˲���",
			name : 'deptName'
		},{
			display : "���Թ�",
			name : 'interviewer'
		}]
	});
}); 