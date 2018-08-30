var show_page = function(page) {
	$("#mycontractGrid").yxgrid("reload");
};
$(function() {
	$("#mycontractGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'MyconPageJson',
		title : '��ͬ����',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
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
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.ExaStatus == 'δ����' || row.ExaStatus == '���') && row.isSubApp == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=init&id='
						+ row.id
						+ '&perm=edit'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.ExaStatus == 'δ����' || row.ExaStatus == '���') && row.isSubApp == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ���ύ������?"))) {
					if (row.isEngConfirm == '1' || row.isSaleConfirm == '1' || row.isRdproConfirm == '1') {
                         if(row.isSubApp == "1"){
                           alert("���ύȷ�ϳɱ����㣡");
                           $("#mycontractGrid").yxgrid("reload");
                         }else{
                           $.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxSubApp",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
								   alert("���ύȷ�ϳɱ����㣡");
								   $("#mycontractGrid").yxgrid("reload");
								}
							}
						});
                         }
					} else {
						$.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxFlowDeptIds",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
									if (row.winRate == '50%') {
										showThickboxWin('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId='
												+ row.id
												+ '&billDept='
												+ msg
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
									} else {
										showThickboxWin('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId='
												+ row.id
												+ '&billDept='
												+ msg
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
									}
								}
							}
						});
					}
				}
			}
		},{
				name : 'cancel',
				text : '��������',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == '��������') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						$.ajax({
							type : "POST",
							url : "?model=common_workflow_workflow&action=isAuditedContract",
							data : {
								billId : row.id,
								examCode : 'oa_contract_contract'
							},
							success : function(msg) {
								if (msg == '1') {
									alert('�����Ѿ�����������Ϣ�����ܳ���������');
									return false;
								}else{
									alert(msg)
									switch(msg){
										case '��ͬ����A' : var url = 'controller/contract/contract/ewf_index_50.php?actTo=delWork&billId=';break;
										case '��ͬ����B' : var url = 'controller/contract/contract/ewf_index_Other.php?actTo=delWork&billId=';break;
										case '��ͬ����TA' :var url = 'controller/contract/contract/ewf_index_50_list_temp.php?actTo=delWork&billId=';break;
										case '��ͬ����TB' :var url = 'controller/contract/contract/ewf_index_Other_list_temp.php?actTo=delWork&billId=';break;
									}
									$.ajax({
										    type: "GET",
										    url: url,
										    data: {"billId" : row.id },
										    async: false,
										    success: function(data){
										    	alert(data)
										    	show_page();
											}
										});
								}
							}
						});
					} else {
						alert("��ѡ��һ������");
					}
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
			text : '�����ϴ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=toUploadFile&id='
						+ row.id
						+ '&type=oa_contract_contract'
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {
			text : '����',
			icon : 'add',
			action : function(row) {
				window
						.open('?model=contract_common_relcontract&action=importCont&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row && (row.state == '0' || row.ExaStatus == '���') && row.isSubApp == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#mycontractGrid").yxgrid("reload");
							} else {
								alert('ɾ��ʧ��! ');
							}
						}
					});
				}
			}
		}, {
			text : '��Ʊ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
						+ row.id
						+ '&invoiceapply[objCode]='
						+ row.contractCode
						+ '&invoiceapply[objType]=KPRK-12');
			}
		}, {
			text : '¼�벻��Ʊ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
					+ row.id
					+ '&objCode='
					+ row.contractCode
					+ '&objType=KPRK-12'
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
			}
		}, {
			text : '�ۿ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_deduct_deduct&action=toAdd&contractId='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
			}
		}, {
			name : 'stamp',
			text : '�������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && row.status == 3) {
					return false;
				}
				if (row && (row.ExaStatus == "���" && row.isStamp != "1"))
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (row.isNeedStamp == '1') {
						alert('�˺�ͬ���������,�����ظ�����');
						return false;
					}
					showThickboxWin("?model=contract_contract_contract&action=toStamp&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�����ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')
						&& row.ExaStatus == '���' && row.isSubAppChange == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toChange&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
			}
		}
				// , {
				// text : '�������',
				// icon : 'edit',
				// // showMenuFn : function(row) {
				// // if (row && (row.createTime < '2012-09-09') &&
				// row.ExaStatus == '���' && (row.state == '2' || row.state ==
				// '4')){
				// // return true;
				// // }
				// // return false;
				// // },
				// action : function(row) {
				// showThickboxWin('?model=contract_contract_contract&action=toChangeEqu&contractId='
				// + row.id
				// + "&skey="
				// + row['skey_']
				// +
				// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000');
				// }
				// }
		, {
			text : '��ͬ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=toShare&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500');
			}
		}, {
			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=closeContract&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
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