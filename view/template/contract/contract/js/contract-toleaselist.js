var show_page = function(page) {
	$("#leaseGrid").yxgrid("reload");
};
$(function() {
	$("#leaseGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'conPageJson',
		title : '���޺�ͬ����',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
			'isTemp' : '0',
			'contractType' : 'HTLX-ZLHT'
		},

		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'leaselist',
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			showMenuFn : function(row) {
				if (row ) {
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
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '��ɺ�ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.state == 2) && row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ����ɡ� ״̬��"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=completeOrder&id="
								+ row.id,
						success : function(msg) {
							$("#leaseGrid").yxgrid("reload");
						}
					});
				}
			}
		}
//		, {
//			text : 'ִ�к�ͬ',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row && (row.state == 4)) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ��ִ���С� ״̬��"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_contract_contract&action=exeOrder&id="
//								+ row.id,
//						success : function(msg) {
//							$("#leaseGrid").yxgrid("reload");
//						}
//					});
//				}
//			}
//		}
		],
         lockCol:['flag','exeStatus','status2'],//����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '��ͨ��',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined || row.id == '') {
				 return "�ϼ�";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'isNeedStamp',
			display : '�Ƿ����',
			sortable : true,
			width : 80,
			process : function (v,row){
			   if (row.id == "allMoney" || row.id == undefined) {
					return "";
			   }else{
                   if(v == '0'){
			         return "��";
				   }else{
				      return "��";
				   }
			   }
			}
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
			},
			hide : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
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
		}
				// , {
				// name : 'contractTempMoney',
				// display : 'Ԥ�ƺ�ͬ���',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.contractMoney == ''
				// || row.contractMoney == 0.00
				// || row.id == 'allMoney') {
				// return moneyFormat2(v);
				// } else {
				// return "<font color = '#B2AB9B'>" + moneyFormat2(v)
				// + "</font>";
				// }
				//
				// }
				// }
				, {
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
									+ moneyFormat2(v) + "</font>" + '</a>';
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
					name : 'invoiceApplyMoney',
					display : '��Ʊ�����ܽ��',
					sortable : true,
					width : 80,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'surplusInvoiceMoney',
					display : 'ʣ�࿪Ʊ���',
					sortable : true,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'incomeMoney',
					display : '���ս��',
					width : 60,
					sortable : true,
					process : function(v, row) {
						if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
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
				}, {
					name : 'exeStatus',
					display : 'ִ�н���',
					sortable : true,
					width : 50,
					process : function(v, row) {
							return "<p onclick='exeStatusView("+row.id+");' style='cursor:pointer;color:blue;' >"+v+"</p>";
						}
				},{
					name : 'status2',
					display : '״̬',
					sortable : false,
					width : '20',
					align : 'center',
					// hide : aaa,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined || row.id == '') {
							 return "";
						 }
						if (row.state == '3' || row.state == '7') {
							return "<img src='images/icon/icon073.gif' />";
						} else if(row.ExaStatus == '���'){
							return "<img src='images/icon/icon070.gif' />";
						} else {
						    return "<img src='images/icon/icon072.gif' />";
						}
					}
				}],

		comboEx : [{
			text : '��ͬ״̬',
			key : 'state',
			data : [{
				text : '����',
				value : '0'
			},{
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
			}, {
				text : '�Ѻϲ�',
				value : '5'
			}, {
				text : '�Ѳ��',
				value : '6'
			}, {
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
		}],

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
		sortname : "createTime"

	});
});

// ִ�н�����ʾ
function exeStatusView(cid){
//	showModalDialog(url, '',"dialogWidth:900px;dialogHeight:500px;");
    showModalWin("?model=contract_contract_contract&action=exeStatusView&cid=" + cid);
}