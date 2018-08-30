var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

function hasEqu(objId) {
	var equNum = 0
	$.ajax({
		type: 'POST',
		url: '?model=projectmanagent_borrow_borrowequ&action=getEquById',
		data: {
			id: objId
		},
		async: false,
		success: function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	var limits = $('#limits').val();
	if (limits == '�ͻ�') {
		var ifshow = true;
		var param = {
			'ExaStatusArr': "���,���������",
			'lExaStatusArr': "���,���������",
			'DeliveryStatus2': 'WFH,BFFH',
			'limits': limits
		};
	} else {
		var ifshow = false;
		var param = {
			'ExaStatusArr': '����,���,���������',
			'lExaStatusArr': "���,���������",
			'DeliveryStatus2': 'WFH,BFFH',
			'isproShipconditionAs': '1',
			'limits': limits
		};
	}
	param.isDelayApply = 1;
	$("#shippingGrid")
		.yxsubgrid(
		{
			model: 'projectmanagent_borrow_borrow',
			action: 'shipmentsPageJson',
			param: param,
			title: limits + '�����÷���',
			// ��ť
			isViewAction: false,
			isAddAction: false,
			isEditAction: false,
			showcheckbox: false,
			isDelAction: false,
			comboEx: [
				{
					text: '�´�״̬',
					key: 'makeStatus',
					data: [
						{
							text: 'δ�´�',
							value: 'WXD'
						},
						{
							text: '�����´�',
							value: 'BFXD'
						},
						{
							text: '���´�',
							value: 'YXD'
						}
					]
				},
				{
					text: '����״̬',
					key: 'DeliveryStatus',
					data: [
						{
							text: 'δ����',
							value: 'WFH'
						},
						{
							text: '���ַ���',
							value: 'BFFH'
						}
					]
				}
			],
//						 buttonsEx : [{
//							 name : 'export',
//							 text : "�������ݵ���",
//							 icon : 'excel',
//							 action : function(row) {
//								 window.open("?model=projectmanagent_borrow_borrow&action=exportExcel&limits="
//								 + "&1width=200,height=200,top=200,left=200,resizable=yes")
//						 	}
//						 }],
			// ����Ϣ
			colModel: [
				{
					name: 'status2',
					display: '�´�״̬',
					sortable: false,
					width: '20',
					align: 'center',
					process: function(v, row) {
						if (row.isDelayApply == '1') {
							return "<img src='images/icon/icon071.gif' />";
						}else if (row.makeStatus == 'YXD') {
							return "<img src='images/icon/icon073.gif' />";
						} else {
							return "<img src='images/icon/green.gif' />";
						}
					}
				},
				{
					name: 'rate',
					display: '����',
					sortable: false,
					process: function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
							+ row.id
							+ "&docType=oa_borrow_borrow"
							+ "&objCode="
							+ row.objCode
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '
							+ "<font color='gray'>"
							+ v
							+ "</font>" + '</a>';
					}
				},
				{
					name: 'isship',
					display: '���˽��ô���',
					sortable: false,
					process: function(v, row) {
						if (v == '0') {
							return "-";
						} else if (v == '1') {
							return "<span style='color:red'>��<span>";
						} else {
							return "-";
						}
					},
					width: 50,
					hide: ifshow
				},
				{
					display: '��������״̬',
					name: 'lExaStatus',
					sortable: true,
					hide: true
				},
				{
					display: '����������Id',
					name: 'lid',
					sortable: true,
					hide: true
				},
				{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},
				{
					name: 'ExaDT',
					display: '����ʱ��',
					width: '75',
					sortable: true
				},
				{
					name: 'deliveryDate',
					display: '��������',
					width: 80,
					sortable: true
				},
				{
					name: 'chanceId',
					display: '�̻�Id',
					sortable: true,
					hide: true
				},
				{
					name: 'customerName',
					display: '�ͻ�����',
					width: 160,
					sortable: true,
					show: ifshow
				},
				{
					name: 'objCode',
					display: '����ҵ����',
					width: '150',
					sortable: true
				},
				{
					name: 'Code',
					display: '���',
					width: '150',
					sortable: true,
					process: function(v, row) {
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
				},
				{
					name: 'Type',
					display: '����',
					width: '40',
					sortable: true
				},
				{
					name: 'limits',
					display: '��Χ',
					width: '40',
					sortable: true
				},
				{
					name: 'dealStatus',
					display: '����״̬',
					sortable: true,
					process: function(v) {
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
					width: 50
				},
				{
					name: 'DeliveryStatus',
					display: '����״̬',
					process: function(v) {
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
					width: '60',
					sortable: true
				},
				{
					name: 'makeStatus',
					display: '�´�״̬',
					sortable: true,
					process: function(v) {
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
					width: 60,
					sortable: true
				},
				{
					name: 'customerName',
					display: '�ͻ�����',
					show: ifshow,
					sortable: true
				},
				{
					name: 'createName',
					display: '������',
					width: 80,
					sortable: true
				},
				{
					name: 'salesName',
					display: '���۸�����',
					width: 80,
					show: ifshow,
					sortable: true
				},
				{
					name: 'scienceName',
					display: '����������',
					width: 80,
					show: ifshow,
					sortable: true
				},
				{
					name: 'remark',
					display: '��ע',
					width: 280,
					sortable: true
				},
				{
					name: 'ExaStatus',
					display: '����״̬',
					width: 90,
					hide: true,
					sortable: true
				}
			],
			/**
			 * ��������
			 */
			searchitems: [
				{
					display: '���',
					name: 'Code'
				},
				{
					display: '���۸�����',
					name: 'salesName'
				},
				{
					display: '������',
					name: 'createNmae'
				},
				{
					display: '��������',
					name: 'createTime'
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
				},
				{
					display: '���к�',
					name: 'serialName2'
				}
			],
			sortname: 'ExaDT',
			sortorder: 'DESC',
			// ���ӱ������
			subGridOptions: {
				subgridcheck: true,
				url: '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
				// ���ݵ���̨�Ĳ�����������
				param: [
					{
						'docType': 'oa_borrow_borrow'
					},
					{
						paramId: 'borrowId',// ���ݸ���̨�Ĳ�������
						colId: 'id'// ��ȡ���������ݵ�������
					}
				],
				// ��ʾ����
				afterProcess: function(data, rowDate, $tr) {
					if (data.number <= data.executedNum) {
						$tr.find("td").css("background-color", "#A1A1A1");
					}
				},
				// ��ʾ����
				colModel: [
					{
						name: 'productNo',
						display: '���ϱ��',
						process: function(v, data, rowData, $row) {
							if (data.changeTips == 1) {
								return '<img title="����༭������" src="images/changeedit.gif" />' + v;
							} else if (data.changeTips == 2) {
								return '<img title="�������������" src="images/new.gif" />' + v;
							} else {
								return v;
							}
						},
						width: 95
					},
					{
						name: 'productName',
						width: 200,
						display: '��������',
						process: function(v, data, rowData, $row) {
							if (data.changeTips != 0) {
								return "<font color=red>" + v + "</font>";
							} else {
								return v;
							}
						}
					},
					{
						name: 'productModel',
						display: '����ͺ�'
						// ,width : 40
					},
					{
						name: 'number',
						display: '����',
						width: 40
					},
					{
// name : 'lockNum',
// display : '��������',
// width : 50,
// process : function(v) {
// if (v == '') {
// return 0;
// } else
// return v;
// }
// }, {
						name: 'exeNum',
						display: '�������',
						width: 50,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'lockedNum',
						display : '������',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'issuedShipNum',
						display: '���´﷢������',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'executedNum',
						display: '�ѷ�������',
						width: 60
					},
					{
						name: 'issuedPurNum',
						display: '���´�ɹ�����',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'issuedProNum',
						display: '���´���������',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'backNum',
						display: '�˿�����',
						width: 60
					},
					{
						name: 'arrivalPeriod',
						display: '��׼������',
						width: 80,
						process: function(v) {
							if (v == null) {
								return '0';
							} else {
								return v;
							}
						}
					}
				]
			},
			// ��չ�Ҽ��˵�
			menusEx: [
				{
					text: '�鿴��ϸ',
					icon: 'view',
					action: function(row) {
						showOpenWin('?model=stock_outplan_outplan&action=viewByBorrow&id='
						+ row.id
						+ "&objType=oa_borrow_borrow"
						+ "&linkId=" + row.lid
						+ "&skey=" + row['skey_']);
					}
				},
				{
					text: '�������',
					icon: 'lock',
					showMenuFn: function(row) {
						if (row.DeliveryStatus != 'YFH' && row.isship == '0'
							&& (row.ExaStatus == '���' || row.ExaStatus == '����' || row.createId == 'quanzhou.luo')// ��Ȩ�������ĵ���
							&& row.DeliveryStatus != 'TZFH') {
							return true;
						}
						return false;
					},
					action: function(row) {
						var objCode = row.objCode;
						showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id
						+ "&objCode="
						+ objCode
						+ "&objType=oa_borrow_borrow&skey="
						+ row['skey_']);
					}
				},
				{
					text: '�´﷢���ƻ�',
					icon: 'add',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3) && row.isship == '0'
							&& (row.ExaStatus == '���' || row.ExaStatus == '����' || row.createId == 'quanzhou.luo')// ��Ȩ�������ĵ���
							&& row.makeStatus != 'YXD'
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var idArr = g
							.getSubSelectRowCheckIds(rows);
						showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&equIds="
						+ idArr
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
					}
				},
				{
					text: '�´�ɹ�����',
					icon: 'edit',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3) && row.isship == '0'
							&& (row.ExaStatus == '���' || row.ExaStatus == '����' || row.createId == 'quanzhou.luo')// ��Ȩ�������ĵ���
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var idArr = g.getSubSelectRowCheckIds(rows);
						if (row.orderCode == '')
							var codeValue = row.orderTempCode;
						else
							var codeValue = row.orderCode;
						showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
						+ row.id
						+ "&orderCode="
						+ row.Code
						+ "&orderName="
						+ "&purchType=oa_borrow_borrow"
						+ "&skey="
						+ row['skey_']
						+ "&equIdArr="
						+ idArr
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
					}
				},
				{
					text: '�´���������',
					icon: 'add',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3)
							&& (row.ExaStatus == '���' || row.ExaStatus == '����' || row.createId == 'quanzhou.luo')// ��Ȩ�������ĵ���
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var eqIdArr = g
							.getSubSelectRowCheckIds(rows);
						showOpenWin("?model=produce_apply_produceapply&action=toApply&relDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&relDocType=BORROW"
						+ "&skey=" + row['skey_']);
					}
				},
				{
					text: "�ر�����",
					icon: 'delete',
					showMenuFn: function(row) {
						if (row.DeliveryStatus != 'TZFH' && row.isship == '0' && row.isDelayApply == '0') {
							return true;
						}
						return false;
					},
					action: function(row) {
						$.ajax({
							type: 'POST',
							url: '?model=contract_common_allcontract&action=closeCont&skey='
							+ row['skey_'],
							data: {
								id: row.id,
								type: 'oa_borrow_borrow'
							},
							// async: false,
							success: function(data) {
								alert("�رճɹ�");
								show_page();
								return false;
							}
						});
					}
				},
				{
					text: '���²ֿ�',
					icon: 'edit',
					showMenuFn: function(row) {
						if (row.isship == '1') {
							return true;
						}
						return false;
					},
					action: function(row) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=toBackStorage&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				},
				{
					text: '��������',
					icon: 'edit',
					showMenuFn: function(row) {
						if (row.isDelayApply == '1') {
							return true;
						}
						return false;
					},
					action: function(row) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=toNoticeDelayApply&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				}
			]
		});
});