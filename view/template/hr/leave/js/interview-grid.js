var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_leave_interview',
		title : '��ְ--��̸��¼��',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isOpButton:false,
		isViewAction : false,
		bodyAlign:'center',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_leave_interview&action=toView&id=" + data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
			}
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
               showModalWin('?model=hr_leave_interview&action=toView&id='
							+ row.id );
			}

//		},{
//			text : '�༭',
//			icon : 'view',
//			action : function(row) {
//				showModalWin('?model=hr_leave_interview&action=toEdit&id=' + row.id );
//			}
//
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_interview&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#interviewGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width:80
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width:70
		},{
			name : 'deptName',
			display : '��������',
			sortable : true,
			width:80
		},{
			name : 'jobName',
			display : 'ְλ',
			sortable : true,
			width:80
		},{
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width:80
		},{
			name : 'quitDate',
			display : '��ְ����',
			sortable : true,
			width:80
		},{
			name : 'interviewer',
			display : '��̸��',
			sortable : true,
			width:200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "Ա������",
			name : 'userNameSearch'
		},{
			display : "��������",
			name : 'deptNameSearch'
		},{
			display : "ְλ",
			name : 'jobNameSearch'
		},{
			display : "��ְ����",
			name : 'entryDate'
		},{
			display : "��ְ����",
			name : 'quitDate'
		},{
			display : "��̸��",
			name : 'interviewer'
		}]
	});
});