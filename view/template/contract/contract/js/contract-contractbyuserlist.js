var show_page = function(page) {
	$("#contractbyuserlist").yxgrid("reload");
};
$(function() {
	$("#contractbyuserlist").yxgrid({
		model : 'contract_contract_contract',
		action : 'MyconPageJson',
		title : '��ͬ����',
		param : {
			'states' : '2,4',
			'mycontractArr' : $("#userId").val(),
			'isTemp' : '0'
		},

		title : '��ͬ��Ϣ',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mycontractA',
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
		}],

        lockCol:['flag'],//����������
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
					return v;
					// return "���ϼ�";
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
					name : 'uninvoiceMoney',
					display : '����Ʊ���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if( row.id == undefined ) return moneyFormat2(v);
						if (v == '') {
							return "0.00";
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_uninvoice_uninvoice&action=toObjList&objId='
									+ row.id
									+ '&objType=KPRK-12'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'
									+ moneyFormat2(v) + '</a>';
						}
					}
				}, {
					name : 'deductMoney',
					display : '�ۿ���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if( row.id == undefined ) return moneyFormat2(v);
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
					name : 'surOrderMoney',
					display : 'ǩԼ��ͬӦ���˿����',
					sortable : true,
					width : 120,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'surincomeMoney',
					display : '����Ӧ���˿����',
					sortable : true,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'financeconfirmPlan',
					display : '����ȷ�Ͻ���',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == undefined) {
							return "";
						}
						var financePlan = moneyFormat2(row.serviceconfirmMoney
								/ (accSub(row.contractMoney, row.deductMoney)));
						if (isNaN(financePlan)) {
							return "0.00%";
						} else {
							financePlan = parseFloat(financePlan).toFixed(2);
							return financePlan * 100 + "%";
						}

					}
				},{
				    name : 'isSubApp',
				    display : '�ύ״̬',
				    sortable : true,
				    width : 60,
				    process : function (v,row){
				       if(v == '0'){
				          return "δ�ύ";
				       }else if(v == '1'){
				       	  return "���ύ";
				       }else{
				          return "--";
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
					name : 'AreaLeaderNow',
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
				text : '����',
				value : '0'
			}, {
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
			// // �߼�����
			// advSearchOptions : {
			// modelName : 'orderInfo',
			// // ѡ���ֶκ��������ֵ����
			// selectFn : function($valInput) {
			// $valInput.yxcombogrid_area("remove");
			// },
			// searchConfig : [{
			// name : '��������',
			// value : 'c.createTime',
			// changeFn : function($t, $valInput) {
			// $valInput.click(function() {
			// WdatePicker({
			// dateFmt : 'yyyy-MM-dd'
			// });
			// });
			// }
			// }, {
			// name : '��������',
			// value : 'c.areaPrincipal',
			// changeFn : function($t, $valInput, rowNum) {
			// if (!$("#areaPrincipalId" + rowNum)[0]) {
			// $hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			// + rowNum + "' value=''>");
			// $valInput.after($hiddenCmp);
			// }
			// $valInput.yxcombogrid_area({
			// hiddenId : 'areaPrincipalId' + rowNum,
			// height : 200,
			// width : 550,
			// gridOptions : {
			// showcheckbox : true
			// }
			// });
			// }
			// }]
			//		}
	});
});