var show_page = function(page) {
	$("#esmweeklogGrid").yxgrid("reload");
};

$(function() {
	$("#esmweeklogGrid").yxgrid({
		model : 'engineering_worklog_esmweeklog',
		param : {
			'createId' : $('#createId').val()
		},
		title : '�ܱ�',
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'weekTitle',
				display : '�ܱ�����',
				sortable : true,
				width : '300'
			}, {
				name : 'weekTimes',
				display : '�ܴ�',
				sortable : true,
				width : 50
			}, {
				name : 'weekBeginDate',
				display : '��ʼ����',
				sortable : true
			}, {
				name : 'weekEndDate',
				display : '��������',
				sortable : true
			}, {
				name : 'depId',
				display : '����id',
				sortable : true,
				hide : true
			}, {
				name : 'depName',
				display : '��������',
				sortable : true
			}, {
				name : 'assessmentId',
				display : '������ID',
				sortable : true,
				hide : true
			}, {
				name : 'assessmentName',
				display : '������',
				sortable : true
			}, {
				name : 'subStatus',
				display : '�ύ״̬',
				sortable : true
			}, {
				name : 'updateTime',
				display : '����ʱ��',
				sortable : true,
				width : 150
			}],
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=engineering_worklog_esmworklog&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750");
			}
		},
		menusEx : [{
			text : '���ܱ�',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}],
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		sortorder : "DESC",
		sortname : "weekBeginDate",
		searchitems : [{
			display : "��������",
			name : 'depName'
		}, {
			display : "������",
			name : 'assessmentName'
		}, {
			display : "�޸�ʱ��",
			name : 'updateTime'
		}]
	});
});