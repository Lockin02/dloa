// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".supplinkmanGrid").yxgrid("reload");
};
$(function() {
	$(".supplinkmanGrid").yxgrid({
		tittle : '�����ϵ�� ���� ��ӻ�����Ϣ��2/4��',
		//�������url�����ô����url������ʹ��model��action�Զ���װ
		//url : '',
		// '?model=customer_customer_customer&action=pageJson',
		model : 'supplierManage_temporary_stcontact',
		//						action : 'getById',
		action : 'pageJson&parentId=' + $("#parentId").val(),
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '��ϵ������',
			name : 'name',
			sortable : true,
			//���⴦���ֶκ���
			process : function(v, row) {
				return row.name;
			}
		}
				//								,{
				//									display : '��Ӧ�̱��',
				//									name : 'busiCode',
				//									sortable : true
				//								}
				, {
					display : '�����ַ',
					name : 'email',
					sortable : true
				}, {
					display : '����',
					name : 'plane',
					sortable : true
				}, {
					display : '����',
					name : 'fax',
					sortable : true
				}],
		//��չ��ť
		buttonsEx : [{
			name : 'upgoon',
			text : "��һ��",
			icon : 'edit',
			action : function(row) {
				location = "?model=supplierManage_temporary_temporary&action=toEdit1&id="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
			}
		}, {
			name : 'goon',
			text : "��һ��",
			icon : 'add',
			action : function(row) {
				location = "?model=supplierManage_temporary_stproduct&action=stpToAdd&parentId="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
			}
		}],
		//��չ�Ҽ��˵�
		menusEx : [],
		//��������
		searchitems : [{
			display : '��ϵ������',
			name : 'name'
		}],
		// title : '�ͻ���Ϣ',
		//ҵ���������
		boName : '��Ӧ����ϵ��',
		//Ĭ�������ֶ���
		sortname : "name",
		//Ĭ������˳��
		sortorder : "ASC",
		//��ʾ�鿴��ť
		isViewAction : true,
		//�鿴��չ��Ϣ
		toViewConfig : {
			action : 'toRead'
		},
		//�޸���չ��Ϣ
		toEditConfig : {
			action : "toEdit"
		},

		//��д��ӷ���
		toAddConfig : {
			text : '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ "&parentId="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
						+ c.plusUrl
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			},
			/**
			 * ���������õĺ�̨����
			 */
			action : 'toAdd',
			/**
			 * ׷�ӵ�url
			 */
			plusUrl : '',
			/**
			 * ������Ĭ�Ͽ��
			 */
			formWidth : 0,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 0
		},

		toViewConfig : {
			text : '�鿴',
			/**
			 * Ĭ�ϵ���鿴��ť�����¼�
			 */
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ p.toViewConfig.action
							+ c.plusUrl
							+ "&id="
							+ rowObj.data('data').id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 300 + "&width=" + 500);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			},
			/**
			 * ���ر�Ĭ�ϵ��õĺ�̨����
			 */
			action : 'init',
			/**
			 * ׷�ӵ�url
			 */
			plusUrl : '',
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 0,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 0
		}

	});

});