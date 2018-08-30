var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

function hasEqu(objId) {
	var equNum = 0
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_borrow_borrowequ&action=getEquById',
		data : {
			id : objId
		},
		async : false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	var limits = $('#limits').val();
	if (limits == '�ͻ�') {
		var ifshow = false;
		var param = {
			'ExaStatus' : '���',
			'DeliveryStatus2' : 'YFH,TZFH',
			'limits' : limits
		};
	} else {
		var ifshow = true;
		var param = {
			'ExaStatusArr' : '����,���',
			'DeliveryStatus2' : 'YFH,TZFH',
			'isproShipcondition' : '1',
			'limits' : limits
		};
	}

	param.isNotDelayApply = 1;
	$("#shippingGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'shipmentsPageJson',
		param : param,
		title : limits + '�����÷���',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isDelAction : false,
		autoload : false,
		//����Ϣ
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
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_borrow_borrow"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '
						+ "<font color='gray'>" + v + "</font>" + '</a>';
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
			display : 'isship',
			name : 'isship',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			width : '75',
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 80,
			sortable : true
		}, {
			name : 'chanceId',
			display : '�̻�Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 160,
			sortable : true,
			hide : ifshow
		}, {
			name : 'objCode',
			display : '����ҵ����',
			width : '150',
			sortable : true
		}, {
			name : 'Code',
			display : '���',
			width : '150',
			sortable : true,
			process : function(v, row) {
				if (row.changeTips == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id + "&objType=oa_borrow_borrow"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id + "&objType=oa_borrow_borrow"
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
			name : 'Type',
			display : '����',
			width : '40',
			sortable : true
		}, {
			name : 'limits',
			display : '��Χ',
			width : '40',
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
			name : 'customerName',
			display : '�ͻ�����',
			hide : ifshow,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'salesName',
			display : '���۸�����',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'scienceName',
			display : '����������',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			width : 280,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 90,
			hide : true,
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		},{
			display:'���۸�����',
			name:'salesName'
		}, {
			display : '����ҵ����',
			name : 'objCode'
		},{
			display : '������',
			name : 'createName'
		},{
			display:'��������',
			name:'createTime'
		},
			{
				display: '�ͻ�����',
				name: 'customerName'
			},
			{
				display: '��������',
				name: 'productName'
			},
			{
				display: '���ϱ���',
				name: 'productNo'
			},{
            display: '���к�',
            name: 'serialName2'
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC',
		// ���ӱ������
		subGridOptions : {
			url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'docType' : 'oa_borrow_borrow'
			}, {
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'productNo',
				display : '���ϱ��',
				process : function( v,data,rowData,$row ){
					if( data.changeTips==1 ){
						return '<img title="����༭������" src="images/changeedit.gif" />' + v;
					}else if( data.changeTips==2 ){
						return '<img title="�������������" src="images/new.gif" />' + v;
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
					if (data.changeTips != 0) {
							return "<font color=red>" + v + "</font>";
					} else {
							return v;
					}
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
//				name : 'lockNum',
//				display : '��������',
//				width : 50,
//				process : function(v) {
//					if (v == '') {
//						return 0;
//					} else
//						return v;
//				}
//			}, {
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
			}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				window.open('?model=stock_outplan_outplan&action=viewByBorrow&id='
						+ row.id + "&objType=oa_borrow_borrow"
						+ "&linkId="
						+ row.linkId
						+ "&skey="
						+ row['skey_'],'borrowassign');
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
					url : '?model=common_contract_allsource&action=closeCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_borrow_borrow'
					},
					//				    async: false,
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
					url : '?model=common_contract_allsource&action=recoverCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_borrow_borrow'
					},
					//				    async: false,
					success : function(data) {
						alert("�ָ��ɹ�");
						show_page();
						return false;
					}
				});
			}
		}]
	});

});