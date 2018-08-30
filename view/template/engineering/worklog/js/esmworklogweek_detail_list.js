var show_page = function(page) {
	$(".awldetailGrid").yxgrid("reload");
};
$(function() {
	$(".awldetailGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		action : 'getdtweeklog',
		title : '�����ܱ�',
		isToolBar : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '����',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '�ܴ�',
			sortable : true,
			// ���⴦���ֶκ���
			process : function(v, row) {
				return "��" + v + "��";
			}
		}, {
			name : 'rankCode',
			display : '����',
			sortable : true
		}, {
			name : 'rankCodeId',
			display : '����id',
			sortable : true,
			hide : true
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
			display : '�ύ������',
			sortable : true
		}, {
			name : 'subStatus',
			display : '�ύ״̬',
			datacode : 'ZBZT',
			sortable : true
		}],
		// ��չ��ť
		buttonsEx : [{
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
			display : '����',
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
		isEditAction : false
	});

});
