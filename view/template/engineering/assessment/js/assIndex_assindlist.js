// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assIndexGrid").yxgrid("reload");
};
$(function() {
	$(".assIndexGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'engineering_assessment_assIndex',

		// action : 'pageJson',
		title : 'ָ������',
		showcheckbox : true, // ��ʾcheckbox
		isToolBar : true, // ��ʾ�б��Ϸ��Ĺ�����

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'ָ������',
			name : 'name',
			sortable : true
		}, {
			display : '�����',
			name : 'sortNo',
			sortable : true
		}, {
			display : '��ϸ',
			name : 'detail',
			sortable : true,
			width : '600'
		}],
		// ��չ��ť
		buttonsEx : [],
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'edit',
			text : '�༭',
			icon : 'edit',
			action : function(row, rows, grid) {
				showThickboxWin("?model=engineering_assessment_assIndex&action=toEdit&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}, {
			name : 'open',
			text : '��',
			icon : 'edit',
			action : function(row, rows, grid) {
				showThickboxWin("?model=engineering_assessment_assConfig&action=toassConlist&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],
		// ��������
		searchitems : [{
			display : 'ָ������',
			name : 'name'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : 'ָ������',
		// Ĭ�������ֶ���
		sortname : "name",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// ������Ӱ�ť
		isAddAction : true,
		// ����ɾ����ť
		isDelAction : true,
		isEditAction : false

	});

});