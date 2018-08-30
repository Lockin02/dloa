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
		isOpButton:false,
		bodyAlign:'center',
		param : {
			interviewDate : "1",//ʵ����û�����ã���Ҫ�õ�searchArr
			linkid : $("#linkid").val(),
			state : 1
		},

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
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"&skey="+row['skey_']+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800" +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'parentCode',
			display : 'Դ�����',
			width:130,
			sortable : true
		},{
			name : 'applicantName',
			display : 'ӦƸ������',
			width:70,
			sortable : true
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 50
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
			width:120,
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
			width:70,
			sortable : true
		},{
			name : 'interviewPlace',
			display : '���Եص�',
			width:70,
			sortable : true
		},{
			name : 'stateC',
			display : '״̬',
			width:60,
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

		lockCol:['formCode','parentCode','applicantName'],//����������

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		menusEx : [{
			text : '�����������',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.sign == 0) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interviewComment&action=toAdd&setid=" + row.id,'1');
			}
		},{
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.sign==1){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interviewComment&action=toEdit&id=" + row.commentid,'1');
			}
		}],

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