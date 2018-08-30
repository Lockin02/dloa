var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
	$("#schemeGrid").yxgrid({
		model : 'supplierManage_scheme_scheme',
		action : 'pageJson',
		title : '��������',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'schemeCode',
			display : '��������',
			sortable : true
		}, {
			name : 'schemeName',
			display : '��������',
			sortable : true
		}, {
			name : 'schemeTypeName',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'formManName',
			display : '�Ƶ���',
			sortable : true
		}, {
			name : 'formDate',
			display : '����ʱ��',
			sortable : true
		}],
//		comboEx : [{
//			text : '����״̬',
//			key : 'ExaStatus',
//			data : [{
//				text : '��������',
//				value : '��������'
//			}, {
//				text : '���ύ',
//				value : '���ύ'
//			}, {
//				text : '���',
//				value : '���'
//			}, {
//				text : '���',
//				value : '���'
//			}]
//		}],
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

		//��չ��ť
		buttonsEx : [{
			name : 'add',
			text : '����',
			icon : 'add',
			action : function(row, rows, grid) {
					location="index1.php?model=supplierManage_scheme_scheme&action=toAdd";
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [
//			{
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
//					showThickboxWin('controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//
//		}, {
//			name : 'aduit',
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "���" || row.ExaStatus == "���")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_supp_scheme&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		},
			{
			text : '�༭',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
//					return true;
//				}
//				return false;
//			},
			action : function(row) {
				if(row){
				  location="index1.php?model=supplierManage_scheme_scheme&action=init&id="+row.id;
				}
			}
		},
			{
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
						url : "?model=supplierManage_scheme_scheme&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#schemeGrid").yxgrid("reload");
						}
					});
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��������',
			name : 'schemeCode'
		}, {
			display : '��������',
			name : 'schemeName'
		}]
	});
});
