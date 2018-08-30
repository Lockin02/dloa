var show_page = function(page) {
	$("#myOrderInfoGrid").yxgrid("reload");
};
$(function() {
	$("#myOrderInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'myOrderJson',
		title : '�ҵĺ�ͬ',
		param : {
			'prinvipalId' : $("#user").val()
		},
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,
        customCode : 'myOrderInfo',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'tablename',
			display : '��ͬ����',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "���ۺ�ͬ";
				} else if (v == 'oa_sale_service') {
					return "�����ͬ";
				} else if (v == 'oa_sale_lease') {
					return "���޺�ͬ";
				} else if (v == 'oa_sale_rdproject') {
					return "�з���ͬ";
				}
			}
		}, {
			name : 'orderCode',
			display : '������ͬ��',
			sortable : true,
			width : 210
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			sortable : true,
			width : 210
		}, {
			name : 'orderName',
			display : '��ͬ����',
			sortable : true,
			width : 210
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'orderTempMoney',
			display : 'Ԥ�ƺ�ͬ���',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'orderMoney',
			display : 'ǩԼ��ͬ���',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomeMoney',
			display : '���ս��',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'surOrderMoney',
			display : 'ǩԼ��ͬӦ���˿����',
			sortable : false,
			width : 120,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(v) + "</font>"
			}
		}, {
			name : 'surincomeMoney',
			display : '����Ӧ���˿����',
			sortable : false,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(v) + "</font>"
			}
		},{
					name : 'budgetAll',
					display : '��Ԥ��',
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
					name : 'budgetOutsourcing',
					display : '�������Ԥ��',
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
					name : 'feeFieldCount',
					display : '�ֳ�����(ʵʱ)',
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
					name : 'feeOutsourcing',
					display : '�������(ʵʱ)',
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
					name : 'feeAll',
					display : '�ܷ���(ʵʱ)',
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
					name : 'projectProcess',
					display : '����������',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (v == "") {
							return "0.00%";
						}
						return v + "%";
					}
				}, {
					name : 'processMoney',
					display : '��������ִ�к�ͬ��',
					sortable : false,
					width : 80,

					process : function(v,row) {
						if(row.id == "allMoney" || row.id==undefined){
						   return "";
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 100
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
				} else if (v == '5') {
					return "�Ѳ��";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 100
		}, {
			name : 'sign',
			display : '�Ƿ�ǩԼ',
			sortable : true,
			width : 70
		}, {
			name : 'orderstate',
			display : 'ֽ�ʺ�ͬ״̬',
			sortable : true,
			width : 100
		}, {
			name : 'parentOrder',
			display : '����ͬ����',
			sortable : true,
			hide : true
		}],
		buttonsEx : [{
			name : 'export',
			text : "��ͬ����",
			icon : 'excel',
			action : function(row) {
				var type = $("#tablename").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var i = 1;
				var colId = "";
				var colName = "";
				$("#myOrderInfoGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window
						.open("?model=projectmanagent_order_order&action=myExportExcel&colId="
								+ colId
								+ "&colName="
								+ colName
								+ "&type="
								+ type
								+ "&state="
								+ state
								+ "&ExaStatus="
								+ ExaStatus
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],

		comboEx : [{
			text : '��ͬ����',
			key : 'tablename',
			data : [{
				text : '���ۺ�ͬ',
				value : 'oa_sale_order'
			}, {
				text : '���޺�ͬ',
				value : 'oa_sale_lease'
			}, {
				text : '�����ͬ',
				value : 'oa_sale_service'
			}, {
				text : '�з���ͬ',
				value : 'oa_sale_rdproject'
			}]
		}, {
			text : '��ͬ״̬',
			key : 'state',
			data : [{
				text : 'δ�ύ',
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
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}
				//		   ,{
				//				text : '�������',
				//				icon : 'view',
				//	            showMenuFn : function (row) {
				//	               if (row.ExaStatus=='����'){
				//	                   return true;
				//	               }
				//	                   return true;
				//	            },
				//				action : function(row) {
				//                     if(row.tablename == 'oa_sale_order'){
				//				         showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//					  } else if (row.tablename == 'oa_sale_service'){
				//					     showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  } else if (row.tablename == 'oa_sale_lease'){
				//	                     showThickboxWin('controller/contract/rental/readview.php?itemtype=oa_sale_lease&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  } else if (row.tablename == 'oa_sale_rdproject') {
				//	                     showThickboxWin('controller/rdproject/yxrdproject/readview.php?itemtype=oa_sale_rdproject&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  }
				//
				//				}
				//			}
		, {
			text : '����',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.orgid
								+ '&type='
								+ row.tablename
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '�����ϴ�',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toUploadFile&id='
						+ row.orgid
						+ '&type='
						+ row.tablename
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {
			text : '�����޸�',
			icon : 'edit',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=productedit&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		}, {
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}],
		//		sortname : "createTime",
		//��������ҳ����
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//���ñ༭ҳ����
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//���ò鿴ҳ����
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});
});