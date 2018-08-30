var show_page = function(page) {
	$("#shippedGrid").yxsubgrid("reload");
};
$(function() {
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		action : 'shipmentsPageJson',
		customCode : 'exchangeShipmentsGrid',
		param : {
			'ExaStatusArr':"���,���������",
//			'lExaStatusArr':"���,���������",
			"DeliveryStatus2" : "YFH,TZFH"
		},
		title : '��������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
//
//		buttonsEx : [{
//			name : 'export',
//			text : "�������ݵ���",
//			icon : 'excel',
//			action : function(row) {
//				window.open("?model=contract_common_allcontract&action=preExportExcel"
//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
//			}
//		}],
		// ����Ϣ
		colModel : [{
			name : 'status2',
			display : '�´�״̬',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.makeStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
				}, {
					name : 'rate',
					display : '����',
					sortable : false,
					process : function(v,row){
						return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
								+ row.id
								+ "&docType=oa_contract_exchangeapply"
								+ "&objCode="
								+ row.objCode
								+ "&skey="
								+ row['skey_']
								+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '+"<font color='gray'>"+v+"</font>"+'</a>';
					}
				}, {
					display : '��������״̬',
					name : 'lExaStatus',
					sortable : true
				}, {
					display : '����������Id',
					name : 'lid',
					sortable : true,
					hide : true
				}, {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					width : 70,
					sortable : true
				}, {
					name : 'deliveryDate',
					display : '��������',
					width : 80,
					sortable : true
				}, {
					name : 'exchangeCode',
					display : '���������',
					sortable : true
				}, {
					name : 'contractCode',
					display : 'Դ����',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'saleUserName',
					display : '���۸�����',
					sortable : true
				}, {
					name : 'DeliveryStatus',
					display : '����״̬',
					process : function(v) {
						if (v == 'WFH') {
							return "δ����";
						} else if (v == 'YFH') {
							return "�ѷ���";
						} else if (v == 'BFFH') {
							return "���ַ���";
						} else if (v == 'TZFH') {
							return "ֹͣ����";
						}
					},
					width : '60',
					sortable : true
				}, {
					name : 'makeStatus',
					display : '�´�״̬',
					sortable : true,
					process : function(v) {
						if (v == 'WXD') {
							return "δ�´�";
						} else if (v == 'BFXD') {
							return "�����´�";
						} else if (v == 'YXD') {
							return "���´�";
						} else if (v == 'WXFH') {
							return "���跢��";
						}
					},
					width : 60,
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					width : 60,
					sortable : true
				}
//				, {
//					name : 'objCode',
//					display : 'ҵ����',
//					width : 120
//				}
//				, {
//					name : 'rObjCode',
//					display : 'Դ��ҵ����',
//					width : 120
//				}
				],
		// ���ӱ������
		subGridOptions : {
			url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'docType' : 'oa_contract_exchangeapply'
			}, {
				paramId : 'exchangeId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
						name : 'productName',
						width : 200,
						display : '��������'
					}, {
						name : 'number',
						display : '����',
						width : 40
					}, {
					    name : 'lockNum',
					    display : '��������',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
						name : 'exeNum',
						display : '�������',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedShipNum',
						display : '���´﷢������',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'executedNum',
						display : '�ѷ�������',
						width : 60
					}, {
						name : 'issuedPurNum',
						display : '���´�ɹ�����',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedProNum',
						display : '���´���������',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
					    name : 'backNum',
					    display : '�˿�����',
						width : 60
					},{
						name : 'projArraDate',
						display : '�ƻ���������',
						width : 80,
						process : function(v) {
							if (v == null) {
								return '��';
							} else {
								return v;
							}
						}
					}]
		},
		comboEx : [{
					text : '����״̬',
					key : 'DeliveryStatus',
					data : [{
								text : '�ѷ���',
								value : 'YFH'
							}, {
								text : 'ֹͣ����',
								value : 'TZFH'
							}]
				}],

		menusEx : [{
			text : '�鿴��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchangeequ&action=toEquView&linkId='
						+ row.linkId + "&skey=" + row['skey_']);
			}
//		}, {
//			text : 'ȷ�Ϸ�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ( row.dealStatus == 0 && row.lExaStatus == '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquAdd&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '�༭��������',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.dealStatus == 0 && (row.lExaStatus == 'δ����'||row.lExaStatus == '���')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquEdit&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '�������ϱ��',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if ( (row.dealStatus == 1||row.dealStatus == 2) && row.lExaStatus == '���') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquChange&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '�������',
//			icon : 'lock',
//			showMenuFn : function(row) {
//				if (row.ExaStatus != '���������' && row.DeliveryStatus != 'YFH'
//						&& row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				var objCode = row.objCode;
//				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
//						+ row.id + "&objCode=" + objCode
//						+ "&objType=oa_contract_exchangeapply&skey=" + row['skey_']);
//			}
//		}, {
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.lExaStatus != '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_exchange_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
//		},{
//			text : '�´﷢���ƻ�',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.makeStatus != 'YXD'
//						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ "&docType=oa_contract_exchangeapply"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
//			}
//		}, {
//			text : '�´�ɹ�����',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (false&&(row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (row.orderCode == '')
//					var codeValue = row.orderTempCode;
//				else
//					var codeValue = row.orderCode;
//				showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
//						+ row.id
//						+ "&orderCode="
//						+ row.Code
//						+ "&orderName="
//						+ "&purchType=oa_contract_exchangeapply"
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
//			}
//		}, {
//			text : '�´���������',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus == 7 || row.DeliveryStatus == 10) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin("?model=produce_protask_protask&action=toAdd&id="
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ "&docType=oa_contract_exchangeapply"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
//			}
//		}, {
//			text : "�ر�",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				$.ajax({
//					type : 'POST',
//					url : '?model=contract_common_allcontract&action=closeCont&skey='
//							+ row['skey_'],
//					data : {
//						id : row.id,
//						type : 'oa_contract_exchangeapply'
//					},
//					// async: false,
//					success : function(data) {
//						alert("�رճɹ�");
//						show_page();
//						return false;
//					}
//				});
//			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '���',
					name : 'Code'
				}
//				, {
//					display : 'ҵ����',
//					name : 'objCode'
//				}
//				, {
//					display : 'Դ��ҵ����',
//					name : 'rObjCode'
//				}
				,{
				    display : '���������',
				    name : 'exchangeCode'
				}],
		sortname : 'ExaDT',
		sortorder : 'DESC'
	});
});