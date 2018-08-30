var show_page = function(page) {
	$("#shippedGrid").yxsubgrid("reload");
};
$(function() {
	$("#shippedGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		action : 'shipmentsPageJson',
		customCode : 'presentShippedGrid',
		param : {
			"ExaStatus" : "���",
			"DeliveryStatus2" : "YFH,TZFH"
		},
		title : '��������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_present_present"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
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
			name : 'customerName',
			display : '�ͻ�����',
			width : 150,
			sortable : true
		}, {
			name : 'orderCode',
			display : 'Դ�����',
			width : 170,
			sortable : true
		}, {
			name : 'orderName',
			display : 'Դ������',
			width : 170,
			hide : true,
			sortable : true
		}, {
			name : 'Code',
			display : '���',
			width : 120,
			sortable : true,
			process : function(v, row) {
				if( v=='' ){
					v='��'
				}
				if (row.changeTips == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
//			name : 'limits',
//			display : 'Դ������',
//			width : 60,
//			sortable : true,
//			process : function(v) {
//				if (v == 'oa_sale_order') {
//					return "���۷���";
//				} else if (v == 'oa_sale_lease') {
//					return "���޷���";
//				} else if (v == 'oa_sale_service') {
//					return "���񷢻�";
//				} else if (v == 'oa_sale_rdproject') {
//					return "�з�����";
//				} else if (v == 'oa_borrow_borrow') {
//					return "���÷���";
//				}
//			}
//		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
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
			width : 60,
			sortable : true
		}, {
			name : 'issuedStatus',
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
			name : 'salesName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'reason',
			display : '��������',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			hide : true,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 60,
			sortable : true
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120
		}, {
			name : 'rObjCode',
			display : 'Դ��ҵ����',
			width : 120
		}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [ {
				'docType' : 'oa_present_present'
			}, {
				paramId : 'presentId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			} ],
			// ��ʾ����
			colModel : [{
				name : 'productNo',
				display : '���ϱ��',
				process : function( v,data,rowData,$row ){
					if( data.changeTips==1 ){
						return '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />' + v;
					}else if( data.changeTips==2 ){
						return '<img title="��������Ĳ�Ʒ" src="images/new.gif" />' + v;
					}else{
						return v;
					}
				},
				width : 95
			}, {
				name : 'productName',
				width : 200,
				display : '��������',
				process : function(v,row){
			    	if(row.changeTips == 1 || row.changeTips == 2){
			    		return "<font color = 'red'>"+ v + "</font>"
			    	}else
			    		return v;
				}
			}, {
				name : 'productModel',
				display : '����ͺ�'
//						,width : 40
			}, {
				name : 'number',
				display : '����',
				width : 40
			}, {
//								name : 'lockNum',
//								display : '��������',
//								width : 50,
//								process : function(v) {
//									if (v == '') {
//										return 0;
//									} else
//										return v;
//								}
//							}, {
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
			}, {
				name : 'arrivalPeriod',
				display : '��׼������',
				width : 80,
				process : function(v) {
					if (v == null) {
						return '0';
					} else {
						return v;
					}
				}
			} ]
		},
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByPresent&id='
						+ row.id
						+��"&linkId="
						+ row.linkId
						+ "&objType=oa_present_present"
						+ "&skey=" + row['skey_']);
			}
		}, {
//			text : '���ȱ�ע',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
//						+ row.id
//						+ "&docType=oa_present_present"
//						+ "&objCode="
//						+ row.objCode
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
//			}
//		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=contract_common_allcontract&action=closeCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_present_present'
					},
					// async: false,
					success : function(data) {
						alert("�رճɹ�");
						show_page();
						return false;
					}
				});
			}
		}, {
			text : "�ָ�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�ָ�������')) {
					$.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							type : 'oa_present_present'
						},
						// async: false,
						success : function(data) {
							alert("�ָ��ɹ�");
							show_page();
							return false;
						}
					});
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		}, {
			display : 'Դ����',
			name : 'orderCode'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : 'Դ��ҵ����',
			name : 'rObjCode'
		},{
			display : '������',
			name : 'createName'
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC'
	});
});