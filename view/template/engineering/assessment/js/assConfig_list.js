// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assConfigGrid").yxgrid("reload");
};
$(function() {
	$(".assConfigGrid").yxgrid({
		//�������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model: 'engineering_assessment_assConfig',

		// action : 'pageJson',
		title: '����ָ������',
		showcheckbox: true,
		//��ʾcheckbox
		isToolBar: true,
		//��ʾ�б��Ϸ��Ĺ�����
		param: {
			id: $("#id").val()
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			display: '��Ŀ',
			name: 'name',
			sortable: true
		},
		{
			display: '��ֵ',
			name: 'score',
			sortable: true
		}],
		//��չ��ť
		buttonsEx: [],
		//��չ�Ҽ��˵�
		menusEx: [],
		//��������
		searchitems: [{
			display: '��Ŀ',
			name: 'name'
		}],
		// title : '�ͻ���Ϣ',
		//ҵ���������
		boName: '��Ŀ',
		//Ĭ�������ֶ���
		sortname: "name",
		//Ĭ������˳��
		sortorder: "ASC",
		//��ʾ�鿴��ť
		isViewAction: false,
		//������Ӱ�ť
		isAddAction: true,
		//���ر༭��ť
		isEditAction: false,
		//����ɾ����ť
		isDelAction: true,





		menusEx : [{
				name : 'edit',
				text : "�༭",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=engineering_assessment_assConfig&action=toEdit&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=400");
				}
			}],



		toAddConfig: {
			text: '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn: function(p, g) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth: p.formWidth;
				var h = c.formHeight ? c.formHeight: p.formHeight;
				var rowObj = g.getSelectedRow();
				showThickboxWin("?model=" + p.model + "&action=" + c.action + "&id=" + $("#id").val()
				//													+ c.plusUrl
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=" + 300 + "&width=" + 400);
			},
			/**
			 * ���������õĺ�̨����
			 */
			action: 'toAdd',
			/**
			 * ׷�ӵ�url
			 */
			plusUrl: '',
			/**
			 * ������Ĭ�Ͽ��
			 */
			formWidth: 0,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight: 0
		},
//		//�޸���չ��Ϣ
//		toEditConfig: {
//			text: '�༭',
//			/**
//			 * Ĭ�ϵ��������ť�����¼�
//			 */
//			toAddFn: function(p, g) {
//				var c = p.toAddConfig;
//				var w = c.formWidth ? c.formWidth: p.formWidth;
//				var h = c.formHeight ? c.formHeight: p.formHeight;
//				var rowObj = g.getSelectedRow();
//				showThickboxWin("?model=" + p.model + "&action=" + c.toEdit + "&id=" + $("#id").val()
//				//													+ c.plusUrl
//				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=" + 300 + "&width=" + 400);
//			},
//			/**
//			 * ���������õĺ�̨����
//			 */
//			action: 'toEdit',
//			/**
//			 * ׷�ӵ�url
//			 */
//			plusUrl: '',
//			/**
//			 * ������Ĭ�Ͽ��
//			 */
//			formWidth: 400,
//			/**
//			 * ������Ĭ�ϸ߶�
//			 */
//			formHeight: 300
//		},

		buttonsEx : [{
			separator : true
		},{
			name : 'close',
			text : "�ر�",
			icon : 'edit',
			action : function() {
				self.parent.tb_remove();
				if(self.parent.show_page)self.parent.show_page(1);
			}
		}]
	});

});