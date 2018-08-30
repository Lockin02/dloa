/**
 * �ʲ�������Ϣ�б�
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_disposal_scrap',
		title : '�ʲ�����',
		isToolBar : true,
		showcheckbox : false,
		// isViewAction : false,
		// isEditAction : false,
		// isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ϱ��',
			name : 'billNo',
			sortable : true,
			width : 110
		}, {
			display : '������������',
			name : 'scrapDate',
			sortable : true,
			width : 80
		}, {
			display : '�������벿��Id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '�������벿��',
			name : 'deptName',
			sortable : true,
			width : 90
		}, {
			display : '����������',
			name : 'proposer',
			sortable : true
		}, {
			display : '��������',
			name : 'scrapNum',
			sortable : true,
			width : 60
		}, {
			display : '����ԭ��',
			name : 'reason',
			sortable : true,
			width : 70
		},
		// {
		// display : '�����ܽ��',
		// name : 'amount',
		// sortable : true
		// },
		{
			display : '�����ܲ�ֵ',
			name : 'salvage',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '�����ܾ�ֵ',
			name : 'netValue',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '����ȷ��״̬',
			name : 'financeStatus',
			sortable : true,
			width : 70
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true,
			width : 70
		}, {
			display : '����ʱ��',
			name : 'ExaDT',
			sortable : true,
			// ֻ������״̬Ϊ��ɻ���ʱ����ʾ
			process : function(v,row) {
				if(row.ExaStatus == '���' || row.ExaStatus == '���'){
					return v;
				}
				return '';
			},
			width : 70
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			width : 150
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_disposal_scrapitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '��Ƭ���',
				name : 'assetCode',
				width : 160
			}, {
				display : '�ʲ�����',
				name : 'assetName',
				width : 150
			}, {
				display : '����ͺ�',
				name : 'spec'
			}, {
				display : '��������',
				name : 'buyDate',
				width : 80
			}, {
				display : '�ʲ�ԭֵ',
				name : 'origina',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '��ֵ',
				name : 'salvage',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '��ֵ',
				name : 'netValue',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
//				display : '�����۾�',
//				name : 'depreciation',
//				// �б��ʽ��ǧ��λ
//				process : function(v) {
//					return moneyFormat2(v);
//				}
//			}, {
				display : '��ע',
				name : 'remark',
				width : 150
			}]
		},
		toAddConfig : {
			formWidth : 1000,
			formHeight : 600
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 450,
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���ύ" || row.ExaStatus == "���") && (row.financeStatus == '���ύ' || row.financeStatus == '���')) {
					return true;
				}
				return false;
			}
		},
		// ��չ��ť
		// buttonEx : [{
		// name : 'Add',
		// // hide : true,
		// text : "����",
		// icon : 'add',
		// /**
		// * row ���һ��ѡ�е��� rows ѡ�е��У���ѡ�� rowIds
		// * ѡ�е���id���� grid ��ǰ���ʵ��
		// */
		// action : function(row, rows, grid) {
		// showWin('replacement-add.htm','900','600','�����ʲ��û�');
		// }
		//
		//
		// }],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_disposal_scrap&action=init&perm=view&id="
							+ row.id
							+ "&skey=" + row['skey_'],1,700,1900);

				}
			}
		}, {
//			name : 'aduit',
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "���" || row.ExaStatus == "���" || row.ExaStatus == "��������")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_scrap&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
//			name : 'clear',
//			text : '����Ƭ',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "���")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					location = "?model=asset_assetcard_clean&action=toCleanScrap&billNo="
//							+ row.billNo + "&allocateID=" + row.id;
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '���ύ' || row.ExaStatus == '���') && (row.financeStatus == '���ύ' || row.financeStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_disposal_scrap&action=deletes&id="
								+ row.id,
						success : function(msg) {
							// alert(msg);
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		},{
			text : '����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.financeStatus == "����ȷ��") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ���ز������ʼ�֪ͨ�����Ա?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_disposal_scrap&action=recall",
						data : {
							id : row.id
						},
						async : false,
						success : function(msg) {
							if (msg == 1) {
								alert("���سɹ�!");
								show_page(1);
							} else {
								alert("����ʧ��!");
							}
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '���ϵ����',
			name : 'billNo'
		}, {
			display : '����������',
			name : 'proposer'
		}, {
			display : '�������벿��',
			name : 'deptName'
		}],
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
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
