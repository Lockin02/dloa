var show_page = function(page) {
	$(".pjlogGrid").yxgrid("reload");
};
$(function() {
	$(".pjlogGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		action : 'projectWeekLog',
		param : {
			"pjId" : $("#pjId").val()
		},
		title : '��Ŀ��־',
		isToolBar : false,
		showcheckbox: false,
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
		buttonsEx : [],
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
			display : '�ܴ�',
			name : 'weekTimes'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��־',
		// Ĭ�������ֶ���
		sortname : "weekTitle",
		// Ĭ������˳��
		sortorder : "ASC",
		// ���ز鿴��ť
		isViewAction : false,
		// ���ر༭��ť
		isEditAction : false,
		// ������Ӱ�ť
		isAddAction : false,
		// ����ɾ����ť
		isDelAction : false,
		// ���ر༭��ť
		idEditAction : false

	})
});
