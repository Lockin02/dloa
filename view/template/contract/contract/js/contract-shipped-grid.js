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
			'states' : '2,4,3,7',
			'DeliveryStatusArr' : 'YFH,TZFH',
			'ExaStatus' : '���'
		},

		title : '��������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		autoload : false,
		customCode : 'contractShipInfo',
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ row.id + "&skey=" + row['skey_'] + "&linkId=" + row.lid);
			}
		}, {
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
					url : '?model=common_contract_allsource&action=closeCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
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
			text : "�ָ�",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=common_contract_allsource&action=recoverCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
					},
					// async: false,
					success : function(data) {
						alert("�ָ��ɹ�");
						show_page();
						return false;
					}
				});
			}
		}],

		// ����Ϣ
		colModel : [{
			display : '����ʱ��',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
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
				if (row.isBecome != '0') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v
							+ "</font>" + '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>" + '</a>';
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
			name : 'dealStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else if (v == '2') {
					return "���δ����";
				} else if (v == '3') {
					return "�ѹر�";
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
				} else if ( v == 'TZFH' ){
					return "ֹͣ����"
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120,
			hide : true
		}, {
            name : 'outstockDate',
            display : '�������ʱ��',
            sortable : true,
            width : 80
        }],
		comboEx : [{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-FWHT'
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
				text : '�ѷ���',
				value : 'YFH'
			}, {
				text : 'ֹͣ����',
				value : 'TZFH'
			}]
		}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck:true,
			url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
						'docType' : 'oa_contract_contract'
					}, {
						paramId : 'contractId',// ���ݸ���̨�Ĳ�������
						colId : 'id'// ��ȡ���������ݵ�������
					}],
			// ��ʾ����
			colModel : [{
						name : 'productCode',
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
						process : function(v, data, rowData, $row) {
							if (data.changeTips == 1) {
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "������Ϊ������ת���۵�����");
									return "<img src='images/icon/icon147.gif'  title='������ת��������'/>"
											+ "<font color=red>"
											+ v
											+ "</font>";
								} else {
									return "<font color=red>" + v + "</font>";
								}
							} else {
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "������Ϊ������ת���۵�����");
									return "<img src='images/icon/icon147.gif'  title='������ת��������'/>"
											+ v;
								} else {
									return v;
								}
							}
							if (row.changeTips != 0) {
								return "<font color = 'red'>" + v + "</font>"
							} else
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
//						name : 'lockNum',
//						display : '��������',
//						width : 50,
//						process : function(v) {
//							if (v == '') {
//								return 0;
//							} else
//								return v;
//						}
//					}, {
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
						width : 60,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
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
		},{
			display : '������',
			name : 'createName'
		}],
		sortname : "id"
	});
});