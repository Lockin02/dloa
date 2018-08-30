var show_page = function(page) {
	$("#shipconditionGrid").yxsubgrid("reload");
};
$(function() {
	$("#shipconditionGrid").yxsubgrid({
		model : 'contract_contract_contract',
		title : '��ͬ����',
		param : {
			'ExaStatus' : '���',
			'prinvipalId' : $("#userId").val(),
			'shipCondition' : '1',
			'isTemp' : '0'
		},

		title : '�ӳٷ�����ͬ�б�',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mycontract',
		// ��չ�Ҽ��˵�
         menusEx : [
          	{
			text : '�鿴',
			icon : 'view',
			action: function(row){
                showModalWin('?model=contract_contract_contract&action=init&id='
						+ row.id
                        + '&perm=view'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			   }
          	},{
			text : '��ͬ����֪ͨ',
			icon : 'add',
			action: function(row){
			       showThickboxWin('?model=contract_contract_contract&action=informShipments&id='
						+ row.id
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500');
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
			width : 80
		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			sortable : true,
			width : 60
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
					return  '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
						+ row.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
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
		},{
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
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'surplusInvoiceMoney',
			display : 'ʣ�࿪Ʊ���',
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else {
					return "<font color = 'blue'>" + v + "</font>"
				}
			}
		}, {
			name : 'incomeMoney',
			display : '���ս��',
			width : 60,
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
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
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v != '') {
					return "<font color = 'blue'>" + moneyFormat2(v);
					+"</font>"
				} else {
					return "<font color = 'blue'>"
							+ moneyFormat2(accSub(row.orderMoney,
									row.incomeMoney, 2)) + "</font>"
				}

			}
		}, {
			name : 'surincomeMoney',
			display : '����Ӧ���˿����',
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v != '') {
					return "<font color = 'blue'>" + moneyFormat2(v);
					+"</font>"
				} else {
					return "<font color = 'blue'>"
							+ moneyFormat2(accSub(row.invoiceMoney,
									row.incomeMoney, 2)) + "</font>"
				}
			}
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'dealStatus',
			display : '����״̬',
			sortable : true,
			width : 60,
			process : function(v){
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else if (v == '2') {
					return "���δ����";
				} else if (v == '3') {
					return "�ѹر�";
				}
			}
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
			name : 'prinvipalId',
			display : '��ͬ������Id',
			sortable : true,
			hide : true,
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
				}
			},
			width : 60
		}, {
			name : 'softMoney',
			display : '������',
			width : 80,
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'hardMoney',
			display : 'Ӳ�����',
			width : 80,
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'serviceMoney',
			display : '������',
			width : 80,
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'repairMoney',
			display : 'ά�޽��',
			width : 80,
			sortable : true,
			process : function(v, row) {
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120
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
			}, {
				text : '�Ѻϲ�',
				value : '5'
			}, {
				text : '�Ѳ��',
				value : '6'
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
							 	return  '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=goods_goods_goodsbaseinfo&action=toView&id='
									+ row.conProductId
									+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
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
					},{
						name : 'licenseButton',
						display : '��������',
						process : function(v,row){
							if(row.license != ""){
								return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>�鿴</a>";
							}else{
							    return "";
							}
						}
					},{
						name : 'deployButton',
						display : '��Ʒ����',
						process : function(v,row){
							if(row.deploy != ""){
								return "<a href='#' onclick='showGoods(\""+ row.deploy + "\",\""+ row.conProductName + "\")'>�鿴</a>";
							}else{
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
		sortname : "createTime"
			//		// �߼�����
			//		advSearchOptions : {
			//			modelName : 'orderInfo',
			//			// ѡ���ֶκ��������ֵ����
			//			selectFn : function($valInput) {
			//				$valInput.yxcombogrid_area("remove");
			//			},
			//			searchConfig : [{
			//						name : '��������',
			//						value : 'c.createTime',
			//						changeFn : function($t, $valInput) {
			//							$valInput.click(function() {
			//										WdatePicker({
			//													dateFmt : 'yyyy-MM-dd'
			//												});
			//									});
			//						}
			//					}, {
			//						name : '��������',
			//						value : 'c.areaPrincipal',
			//						changeFn : function($t, $valInput, rowNum) {
			//							if (!$("#areaPrincipalId" + rowNum)[0]) {
			//								$hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			//										+ rowNum + "' value=''>");
			//								$valInput.after($hiddenCmp);
			//							}
			//							$valInput.yxcombogrid_area({
			//										hiddenId : 'areaPrincipalId' + rowNum,
			//										height : 200,
			//										width : 550,
			//										gridOptions : {
			//											showcheckbox : true
			//										}
			//									});
			//						}
			//					}]
			//		}
	});
});