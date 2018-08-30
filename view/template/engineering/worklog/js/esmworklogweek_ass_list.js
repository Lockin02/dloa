var show_page = function(page) {
	$(".assworklogweekGrid").yxgrid("reload");
};
$(function() {
	$(".assworklogweekGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		title : '�ܱ�������Ϣ',
		showcheckbox: false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '��־����',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '�ܴ�',
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
			name : 'createName',
			display : '����������',
			sortable : true
		}, {
			name : 'subStatus',
			display : '�ύ״̬',
			datacode : 'ZBZT',
			sortable : true
		}],
		// ��չ��ť
		buttonsEx : [{

			name : 'excel',
			text : '����EXCEL(������)',
			icon : 'excel',
			action : function(row, rows, grid) {
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcelpage&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
			}
		},{

			name : 'excel',
			text : '����EXCEL(ȫ��)',
			icon : 'excel',
			action : function(row, rows, grid) {
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcels&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
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
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// ������Ӱ�ť
		isAddAction : false,
		// ����ɾ����ť
		isDelAction : false,
		// ���ر༭��ť
		isEditAction : false,
		toViewConfig : {
			action : 'toRead',
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 800
		}
	})
})
