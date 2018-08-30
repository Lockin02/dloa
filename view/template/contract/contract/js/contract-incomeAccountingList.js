var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [], mergeArr = {
		text : "�������",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=contract_contract_contract&action=FinancialImportexcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=contract_contract_contract&action=getLimits',
		data : {
			'limitName' : '�������'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(mergeArr);
			}
		}
	});
	var param = {
		'states' : '0,1,2,3,4,5,6,7',
		'isTemp' : '0'
	}
	if ($("#lastAdd").val()) {
		param.lastAdd = $("#lastAdd").val();
	}
	if ($("#lastChange").val()) {
		param.lastChange = $("#lastChange").val();
	}
	$("#contractGrid").yxgrid({
		model : 'contract_contract_contract',
		//		action : 'conPageJson',
		title : '��ͬ����',
		param : param,
		leftLayout : true,
		title : '��ͬ��Ϣ',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractIncomeTypeInfo',
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '����鿴',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.becomeNum != '0' && row.becomeNum != '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=showViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : 'ȷ��������㷽ʽ',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=incomeAcc&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
			}
		}],

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'incomeAccounting',
			display : '������㷽ʽ',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "";
				} else if (v == 'AFPQRSR') {
					return "����Ʊȷ������";
				} else if (v == 'AJDQRSR') {
					return "������ȷ������";
				} else if (v == 'HEFS') {
					return "��Ϸ�ʽ";
				}
			}
		}, {
			name : 'isNeedStamp',
			display : '�Ƿ����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined) {
					return "";
				} else {
					if (v == '0') {
						return "��";
					} else {
						return "��";
					}
				}
			},
			hide : true
		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'signSubject',
			display : 'ǩԼ����',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == '') {
					return "���ϼ�";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 100
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			sortable : true,
			datacode : 'KHLX',
			width : 70
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'signStatus',
			display : 'ǩ��״̬',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '0') {
					return "δǩ��";
				} else if (v == '1') {
					return "��ǩ��";
				} else if (v == '2') {
					return "���δǩ��";
				}
			}
		}, {
			name : 'contractMoney',
			display : '��ͬ���',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'deductMoney',
			display : '�ۿ���',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
							+ row.id
							+ '&tablename='
							+ row.contractType
							+ '&moneyType=deductMoney'
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
							+ "<font color = '#4169E1'>"
							+ moneyFormat2(v)
							+ "</font>" + '</a>';
				}
			}
		}, {
			name : 'badMoney',
			display : '����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined) {
					return moneyFormat2(v);
				}
				if (v == "") {
					return "0.00";
				}
				return moneyFormat2(v);
			}
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'winRate',
			display : '��ͬӮ��',
			sortable : true,
			width : 70
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '��ͬ������',
			sortable : true,
			width : 80
		}, {
			name : 'state',
			display : '��ͬ״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�ύ";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "ִ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�����";
				} else if (v == '5') {
					return "�Ѻϲ�";
				} else if (v == '6') {
					return "�Ѳ��";
				} else if (v == '7') {
					return "�쳣�ر�";
				}
			},
			width : 60
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120
		}, {
			name : 'prinvipalDept',
			display : '�����˲���',
			sortable : true,
			hide : true
		}, {
			name : 'prinvipalDeptId',
			display : '�����˲���Id',
			sortable : true,
			hide : true
		}],
		comboEx : [{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-FWHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�з���ͬ',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '��ͬ״̬',
			key : 'state',
			data : [{
				text : '������',
				value : '1'
			}, {
				text : 'ִ����',
				value : '2'
			}, {
				text : '�����',
				value : '4'
			}, {
				text : '�ѹر�',
				value : '3'
			}
					//			, {
					//				text : '�Ѻϲ�',
					//				value : '5'
					//			}, {
					//				text : '�Ѳ��',
					//				value : '6'
					//			}
					, {
						text : '�쳣�ر�',
						value : '7'
					}]
		}, {
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '���������',
				value : '���������'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}, {
			text : 'ǩԼ����',
			key : 'signSubject',
			datacode : 'QYZT'
		}, {
			text : '������㷽ʽ',
			key : 'incomeAccounting',
			data : [{
				text : 'δȷ��',
				value : ''
			}, {
				text : '����Ʊȷ������',
				value : 'AFPQRSR'
			}, {
				text : '������ȷ������',
				value : 'AJDQRSR'
			}, {
				text : '��Ϸ�ʽ',
				value : 'HEFS'
			}]
		}],
		// ���ӱ������
		// subGridOptions : {
		// url : '?model=contract_contract_product&action=pageJson',// ��ȡ�ӱ�����url
		// // ���ݵ���̨�Ĳ�����������
		// param : [{
		// paramId : 'contractId',// ���ݸ���̨�Ĳ�������
		// colId : 'id'// ��ȡ���������ݵ�������
		//
		// }],
		// // param:{
		// // 'contractId' : $("#contractId").val(),
		// // 'dir' : 'ASC',
		// // 'prinvipalId':$("#prinvipalId").val(),
		// // 'createId':$("#createId").val(),
		// // 'areaPrincipalId':$("#areaPrincipalId").val(),
		// // // 'isTemp' : '0',
		// // 'isDel' : '0'
		// // },
		// // ��ʾ����
		// colModel : [{
		// name : 'conProductName',
		// width : 200,
		// display : '��Ʒ����'
		// }, {
		// name : 'conProductDes',
		// display : '��Ʒ����',
		// width : 80
		// }, {
		// name : 'number',
		// display : '����',
		// width : 80
		// }, {
		// name : 'price',
		// display : '����',
		// width : 80
		// }, {
		// name : 'money',
		// display : '���',
		// width : 80
		// }, {
		// name : 'licenseButton',
		// display : '��������',
		// process : function(v, row) {
		// if (row.license != "") {
		// return "<a href='#' onclick='showLicense(\'"
		// + row.license + "\')'>�鿴</a>";
		// } else {
		// return "";
		// }
		// }
		// }, {
		// name : 'deployButton',
		// display : '��Ʒ����',
		// process : function(v, row) {
		// if (row.deploy != "") {
		// return "<a href='#' onclick='showGoods(\""
		// + row.deploy + "\",\""
		// + row.conProductName + "\")'>�鿴</a>";
		// } else {
		// return "";
		// }
		// }
		// }]
		// },
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
		sortname : "createTime",
		buttonsEx : buttonsArr,

		// �߼�����
		advSearchOptions : {
			modelName : 'contractIncomeAcc',
			// ѡ���ֶκ��������ֵ����
			selectFn : function($valInput) {
				$valInput.yxcombogrid_area("remove");
			},
			searchConfig : [{
				name : '��������',
				value : 'c.createTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			}, {
				name : '��ͬ����',
				value : 'c.contractType',
				type : 'select',
				datacode : 'HTLX'
			}, {
				name : '�ͻ�����',
				value : 'c.customerType',
				type : 'select',
				datacode : 'KHLX'
			}, {
				name : 'ʣ�࿪Ʊ���',
				value : 'c.surplusInvoiceMoney'
			}, {
				name : 'ǩԼ��ͬӦ���˿����',
				value : 'c.surOrderMoney'
			}, {
				name : '����Ӧ���˿����',
				value : 'c.surincomeMoney'
			}, {
				name : '��������',
				value : 'c.areaPrincipal',
				changeFn : function($t, $valInput, rowNum) {
					$valInput.yxcombogrid_area({
						hiddenId : 'areaPrincipalId' + rowNum,
						nameCol : 'areaPrincipal' + rowNum,
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : '��ͬ������',
				value : 'c.prinvipalName',
				changeFn : function($t, $valInput, rowNum) {

					$valInput.yxselect_user({
						hiddenId : 'prinvipalId' + rowNum,
						nameCol : 'prinvipalName' + rowNum,
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}]
		}
	});
});