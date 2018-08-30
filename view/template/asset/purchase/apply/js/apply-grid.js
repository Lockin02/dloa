var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		action : 'pageJson',
		title : '�ɹ����뵥',
		showcheckbox : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true
		}, {
			name : 'applyTime',
			display : '��������',
			sortable : true
		}, {
			name : 'applicantName',
			display : '����������',
			sortable : true
		}, {
			name : 'applyDetName',
			display : '���벿��',
			sortable : true
		}, {
			name : 'userName',
			display : 'ʹ��������',
			sortable : true
		}, {
			name : 'useDetName',
			display : 'ʹ�ò���',
			sortable : true
		}, {
//			name : 'purchCategory',
//			display : '�ɹ�����',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
			name : 'assetUse',
			display : '�ʲ���;',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'inputProductName',
				width : 200,
				display : '��������'
			}, {
				name : 'pattem',
				display : "���"
			}, {
				name : 'unitName',
				display : "��λ",
				width : 50
			}, {
				name : 'applyAmount',
				display : "��������",
				width : 70
			}, {
				name : 'dateHope',
				display : "ϣ����������"
			}, {
				name : 'remark',
				display : "��ע"
			}]
		},
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			}, {
				text : '���ύ',
				value : '���ύ'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		toEditConfig : {
			/**
			 * �༭��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �༭��Ĭ�ϸ߶�
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
//			text : '�ύ����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ '&flowDept='
//							+ row.useDetId
//							+ '&billDept='
//							+ row.useDetId
//							+ '&flowMoney='
//							+ row.amounts
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//
//		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���" || row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_purchase_apply&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_apply_apply&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#applyGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		}, {
			display : '���벿��',
			name : 'applyDetName'
		}, {
			display : '������',
			name : 'applicantName'
		}, {
			display : 'ʹ�ò���',
			name : 'useDetName'
		}, {
			display : '��������',
			name : 'productName'
		}]
	});
});