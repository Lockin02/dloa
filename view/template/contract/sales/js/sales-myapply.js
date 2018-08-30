var show_page = function(page) {
	$("#MyApply").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#MyApply").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_sales_sales',
		action : 'myApplyPageJson',
		/**
		 * �Ƿ���ʾ�鿴��ť/�˵�
		 */
		isViewAction : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 */
		isEditAction : false,
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 */
		isDelAction : false,
		// �Ƿ���ʾ������ť
		isAddAction : false,
		// �Ƿ���ʾ������
		isToolBar : false,
		// �Ƿ���ʾcheckbox
		showcheckbox : false,

		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '��ͬ��Ϣ',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
					+ row.id );
			}
		}, {
			text : '��ͬ��ʷ',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=versionShow&contNumber='
						+ row.contName
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return true;
				}
				return false;
			},
			action : function(row) {

				parent.location = 'controller/contract/sales/ewf_index.php?actTo=ewfSelect&formName=���ۺ�ͬ����&examCode=oa_contract_sales&billId='
						+ row.id

			}
		}, {
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=init&id='
						+ row.id );
			}
		}, {
			text : '���±༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=editAfterBackAction&id='
						+ row.id );
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ����')) {
					$.ajax({
						type : "POST",
						url : '?model=contract_sales_sales&action=deletes&id='
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page()
							}
						}
					});
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ����')) {
					$.ajax({
						type : "POST",
						url : '?model=contract_sales_sales&action=deletes&id='
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page()
							}
						}
					});
				}
			}
		}],

		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '������ͬ��',
			name : 'contNumber',
			sortable : true,
			width : 150
			// }, {
			// display : 'ϵͳ��ͬ��',
			// name : 'contNumber',
			// sortable : true,
			//
			// width : 150
			}, {
				display : '��ͬ����',
				name : 'contName',
				sortable : true,
				width : 150
			}, {
				display : '�ͻ�����',
				name : 'customerName',
				sortable : true,
				width : 150
			}, {
				display : '�ͻ���ͬ��',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '�ͻ�����',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true
			}, {
				display : '�ͻ�����ʡ��',
				name : 'province',
				sortable : true
			}, {
				display : '����������',
				name : 'principalName',
				sortable : true
			}, {
				display : '��ͬ״̬',
				name : 'contStatus',
				sortable : true,
				process : function(v) {
					switch (v) {
						case '' :
							return 'δ����';
							break;
						case '0' :
							return 'δ����';
							break;
						case '1' :
							return '��ִ��';
							break;
						case '2' :
							return '���������';
							break;
						case '3' :
							return '�����';
							break;
						case '4' :
							return '��عر�';
							break;
						case '5' :
							return '����ɾ��';
							break;
						case '6' :
							return '�����ر�';
							break;
						case '9' :
							return '�ѹر�';
							break;
						default :
							return 'δ����';
							break;
					}
				}
			}, {
				display : '����״̬',
				name : 'ExaStatus',
				sortable : true
			}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�ҵĺ�ͬ����'
	});
});