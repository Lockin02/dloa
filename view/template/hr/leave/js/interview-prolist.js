var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_leave_interview',
		param : {
			'interviewerIdSearch' : $("#userId").val()
		},
		title : '��ְ--��̸��¼��',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isOpButton : false,
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_leave_interview&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '��д��̸��¼',
			icon : 'add',
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=toWrite&id='
						+ row.id);
			}

		}, {
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=toPersonView&id='
						+ row.id);
			}

		}],
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : 'Ա�����',
					sortable : true
				}, {
					name : 'userName',
					display : 'Ա������',
					sortable : true
				}, {
					name : 'deptName',
					display : '��������',
					sortable : true
				}, {
					name : 'entryDate',
					display : '��ְ����',
					sortable : true
				}, {
					name : 'jobName',
					display : 'ְλ',
					sortable : true
				}, {
					name : 'quitDate',
					display : '��ְ����',
					sortable : true
				}, {
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
				}]
	});
});