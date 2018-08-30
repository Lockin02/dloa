/**
 * �ʲ��黹��Ϣ�б�
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_return',
		title : '�ʲ��黹',
		isToolBar : true,
		// isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�黹�����',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '��/�쵥Id',
			name : 'borrowId',
			sortable : true,
			hide : true
		}, {
			display : '��/�쵥���',
			name : 'borrowNo',
			width : 110,
			sortable : true
		}, {
			display : '�黹����',
			name : 'returnType',
			sortable : true,
			process : function(val) {
				if (val == "other") {
					return "����";
				}
				if (val == "oa_asset_borrow") {
					return "����";
				}
				if (val == "oa_asset_charge") {
					return "����";
				}
				if (val == "asset") {
					return "�����黹";
				}
			}

		}, {
			display : '�黹����id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '�黹��������',
			name : 'deptName',
			sortable : true
		}, {
			display : '�黹��Id',
			name : 'returnManId',
			sortable : true,
			hide : true
		}, {
			display : '�Ƿ�ǩ��',
			name : 'isSign',
			sortable : true,
			process : function(val) {
				if (val == "1") {
					return "��";
				} else {
					return "��"
				}
			}
		}, {
			display : '�黹��',
			name : 'returnMan',
			sortable : true
		}, {
			display : '�黹����',
			name : 'returnDate',
			sortable : true
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_returnitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '��Ƭ���',
				name : 'assetCode',
				width : 130
			}, {
				display : '�ʲ�����',
				name : 'assetName'
			}, {
				display : '����ͺ�',
				name : 'spec',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '��������',
				name : 'buyDate',
				// type : 'date',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : 'Ԥ��ʹ���ڼ���',
				name : 'estimateDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '�Ѿ�ʹ���ڼ���',
				name : 'alreadyDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : 'ʣ��ʹ���ڼ���',
				name : 'residueYears',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '��ע',
				name : 'remark',
				tclass : 'txt'
			}]
		},
		toAddConfig : {
			formWidth : 900,
			formHeight : 400
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 300
		},
		// toDelConfig : {
		// showMenuFn : function(row) {
		// if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
		// return true;
		// }
		// return false;
		// }
		// },
		// ��չ�Ҽ��˵�
		menusEx : [{
			// text : '�ύ����',
			// icon : 'add',
			// showMenuFn : function(row) {
			// if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
			// return true;
			// }
			// return false;
			// },
			// action : function(row, rows, grid) {
			// if (row) {
			// showThickboxWin('controller/asset/daily/ewf_index_return.php?actTo=ewfSelect&billId='
			// + row.id
			// +
			// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			// } else {
			// alert("��ѡ��һ������");
			// }
			// }
			//
			// }, {
			// name : 'aduit',
			// text : '�������',
			// icon : 'view',
			// showMenuFn : function(row) {
			// if ((row.ExaStatus == "���" || row.ExaStatus == "���" ||
			// row.ExaStatus == "��������")) {
			// return true;
			// }
			// return false;
			// },
			// action : function(row, rows, grid) {
			// if (row) {
			// showThickboxWin("controller/common/readview.php?itemtype=oa_asset_return&pid="
			// + row.id
			// +
			// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
			// }
			// }
			// }, {
			name : 'edit',
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '0') {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (confirm('ȷ���Ƿ�Ҫǩ�գ�')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_daily_return&action=toSign',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert("ǩ�ճɹ�");
								show_page();
							} else {
								alert("ǩ��ʧ��")
							}
							return false;
						}
					});
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
						url : "?model=asset_daily_return&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '��/�쵥���',
			name : 'borrowNo'
		}, {
			display : '�黹�����',
			name : 'billNo'
		}, {
			display : '�黹��',
			name : 'applicat'
		}, {
			display : '�黹����',
			name : 'deptName'
		}, {
			display : "��Ƭ���",
			name : 'productCode'
		}, {
			display : "�ʲ�����",
			name : 'productName'
		}],
		// comboEx : [{
		// text : '����״̬',
		// key : 'ExaStatus',
		// data : [{
		// text : '��������',
		// value : '��������'
		// }, {
		// text : '���ύ',
		// value : '���ύ'
		// }, {
		// text : '���',
		// value : '���'
		// }, {
		// text : '���',
		// value : '���'
		//			}]
		//		}],
		// ҵ���������
		//	boName : 'ȫ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
