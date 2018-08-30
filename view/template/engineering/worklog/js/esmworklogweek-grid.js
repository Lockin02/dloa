var show_page = function(page) {
	$(".esmworklogweekGrid").yxgrid("reload");
};
$(function() {
	$(".esmworklogweekGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		action : 'getmyweeklog',
		title : '������־�ܱ�',
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTimes',
			display : '�ܴ�',
			// ���⴦���ֶκ���
			process : function(v, row) {
				return "��" + v + "��";
			},
			sortable : true
		}, {
			name : 'weekTitle',
			display : '����',
			width : 300,
			sortable : true
		}, {
			name : 'weekBeginDate',
			display : '��ʼ����',
			sortable : true
		}, {
			name : 'weekEndDate',
			display : '��������',
			sortable : true
		}, {
			name : 'rankCode',
			display : '����',
			sortable : true
		}, {
			name : 'directlyId',
			display : 'ֱ���ϼ�id',
			sortable : true,
			hide : true
		}, {
			name : 'directlyName',
			display : 'ֱ���ϼ�����',
			sortable : true
		}, {
			name : 'existence',
			display : '���ܴ������⼰�õ��İ���',
			sortable : true,
			hide : true
		}, {
			name : 'improvement',
			display : '���ܼ���������֤',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '��д��',
			sortable : true
		}, {
			name : 'assessmentName',
			display : '������',
			sortable : true
		}, {
			name : 'subStatus',
			display : '״̬',
			datacode : 'ZBZT',
			sortable : true
		}],
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		// ��������
		searchitems : [{
			display : '��־����',
			name : 'weekTitle'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��־',
		// Ĭ�������ֶ���
		sortname : "weekTitle",
		toAddConfig : {
			text : '��д������־',
			/**
			 * Ĭ�ϵ���鿴��ť�����¼�
			 */
			toAddFn : function(p, g) {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toAdd&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 400 + "&width=" + 800);
			}
		},
		toEditConfig : {
			text : '�ܽ�',
			/**
			 * Ĭ�ϵ���鿴��ť�����¼�
			 */
			toAddFn : function(p, g) {
				showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toEdit&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 600 + "&width=" + 800);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'submit',
			text : '�ύ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.subStatus == 'ZBSHZ' || row.subStatus == 'ZBYKH') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toSubmit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'submit',
			text : '�޸��ύ',
			icon : 'edit',
			showMenuFn : function(row) {
				if ( row.subStatus == 'ZBYKH' || row.subStatus == 'ZBWTJ' ) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toSubmit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'summary',
			text : '�ܽ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.subStatus == 'ZBSHZ' || row.subStatus == 'ZBYKH') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toEdit&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 600 + "&width=" + 800);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'viewass',
			text : '�鿴��ϸ',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=myassTaskview&id="
							+ row.id
							+ "&subStatus="
							+ row.subStatus
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		// ��չ��������ť
		buttonsEx : []
	})

});