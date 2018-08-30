var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};
$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '����֪ͨ',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,
		param : {
			linkid : $("#linkid").val()
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
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"&skey="+row['skey_']+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800" +"\",1)'>" + v + "</a>";
				}
		}, {
			name : 'parentCode',
			display : 'Դ�����',
			sortable : true
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true
		}, {
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 50
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
			sortable : true
		}, {
			name : 'interviewPlace',
			display : '���Եص�',
			sortable : true
		}, {
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
			display : "����",
			name : 'applicantName'
		},{
			display : "ӦƸ��λ",
			name : 'positionsName'
		},{
			display : "���˲���",
			name : 'deptName'
		},{
			display : "�������",
			name : 'resumeCode'
		}]
	});
});