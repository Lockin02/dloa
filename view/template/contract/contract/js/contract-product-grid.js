var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};

$(function() {
	var shipCondition = $('#shipCondition').val();
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'shipmentsJson',
		title : '��������',
		param : {
			'states' : '2,4',
			'shipCondition' : shipCondition,
			'ExaStatus' : '���'
		},

		title : '��������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractShipInfo',
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=showViewTab&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '�鿴��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_equ&action=toViewTab&id='
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			text : 'ȷ�Ϸ�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && row.lExaStatus == '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && row.lExaStatus == 'δ����') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '�������ϱ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus == 1 && row.lExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row) {

				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_equ_link&pid='
						+ row.lid
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],

		// ����Ϣ
		colModel : [{
			display : '����ʱ��',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '����',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע��'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			display : '��ͬ��������״̬',
			name : 'lExaStatus',
			sortable : true
		}, {
			display : '��ͬ����������Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
//			name : 'createTime',
//			display : '����ʱ��',
//			sortable : true,
//			width : 80
//		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isR == 1) {
					return "<font color = '#0000FF'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#0000FF'>" + v + "</font>"
							+ '</a>';
				} else if (row.isBecome == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
//			name : 'state',
//			display : '��ͬ״̬',
//			sortable : true,
//			process : function(v) {
//				if (v == '0') {
//					return "δ�ύ";
//				} else if (v == '1') {
//					return "������";
//				} else if (v == '2') {
//					return "ִ����";
//				} else if (v == '3') {
//					return "�ѹر�";
//				} else if (v == '4') {
//					return "�����";
//				} else if (v == '5') {
//					return "�Ѻϲ�";
//				} else if (v == '6') {
//					return "�Ѳ��";
//				}
//			},
//			width : 60
//		}, {
			name : 'dealStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				}
			},
			width : 50
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'makeStatus',
			display : '�´�״̬',
			sortable : true,
			process : function(v) {
				if (v == 'BFXD') {
					return "�����´�";
				} else if (v == 'YXD') {
					return "���´�";
				} else {
					return "δ�´�"
				}
			},
			width : 50
		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else if ( v == 'WFH' ){
					return "δ����"
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120,
			hide : true
		}],
		comboEx : [{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-FWHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�з���ͬ',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '�´�״̬',
			key : 'makeStatus',
			data : [{
				text : 'δ�´�',
				value : 'WXD'
			}, {
				text : '�����´�',
				value : 'BFXD'
			}, {
				text : '���´�',
				value : 'YXD'
			}]
		}, {
			text : '����״̬',
			key : 'DeliveryStatus',
			data : [{
				text : 'δ����',
				value : 'WFH'
			}, {
				text : '���ַ���',
				value : 'BFFH'
			}, {
				text : '�ѷ���',
				value : 'YFH'
			}]
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=contract_contract_product&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'contractId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=goods_goods_goodsbaseinfo&action=toView&id='
							+ row.conProductId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}, {
				name : 'conProductDes',
				display : '��Ʒ����',
				width : 80
			}, {
				name : 'number',
				display : '����',
				width : 80
			}, {
				name : 'price',
				display : '����',
				width : 80
			}, {
				name : 'money',
				display : '���',
				width : 80
			}, {
				name : 'licenseButton',
				display : '��������',
				process : function(v, row) {
					if (row.license != "") {
						return "<a href='#' onclick='showLicense(\""
								+ row.license + "\")'>�鿴</a>";
					} else {
						return "";
					}
				}
			}, {
				name : 'deployButton',
				display : '��Ʒ����',
				process : function(v, row) {
					if (row.deploy != "") {
						return "<a href='#' onclick='showGoods(\"" + row.deploy
								+ "\",\"" + row.conProductName + "\")'>�鿴</a>";
					} else {
						return "";
					}
				}
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}],
		sortname : "id"
	});
});