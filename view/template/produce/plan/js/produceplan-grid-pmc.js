var show_page = function (page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function () {
	//���ݹ�������
	var param = {};
	var comboEx = [{
		text: '���ȼ�',
		key: 'urgentLevelCode',
		datacode: 'SCJHYXJ'
	}];
	if ($("#finish").val() == 'yes') {
		param = {
			docStatusIn: '6,8',
			isCancel : 0
		};
		var comboExArr = {
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: '�ѹر�',
				value: '6'
			}, {
				text: '�����',
				value: '8'
			}]
		};
		comboEx.push(comboExArr);
	} else {
		param = {
			isCancel : 0
		};
		var comboExArr = {
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: 'δ����',
				value: '1'
			}, {
				text: '��������',
				value: '2'
			}, {
				text: '��������',
				value: '3'
			}, {
				text: '��������',
				value: '4'
			}, {
				text: '�������',
				value: '5'
			}, {
				text: '�ʼ���',
				value: '9'
			}, {
				text: '�����ʼ�',
				value: '10'
			}, {
				text: '�ʼ����',
				value: '11'
			}, {
				text: '���ڷ���',
				value: '12'
			}, {
				text: '�ѹر�',
				value: '6'
			}, {
				text: '�������',
				value: '7'
			}, {
				text: '�����',
				value: '8'
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#produceplanGrid").yxgrid({
		model: 'produce_plan_produceplan',
		param: param,
		title: '�����ƻ�',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: true,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name : 'isMeetPick',
			display : '�Ƿ�ȷ������',
			sortable : false,
			width : 80,
			align : 'center',
			process : function(v) {
				if (v == '0') {
					return "<img src='images/icon/icon073.gif' title='δȷ��'/>";
				} else if (v == '1'){
					return "<img src='images/icon/green.gif' title='��ȷ��'/>";
				}
			}
		}, {
			name: 'relDocCode',
			display: '��ͬ(Դ��)���',
			sortable: true,
            process: function (v, row) {
            	if(row.relDocTypeCode == 'HTLX-XSHT' || row.relDocTypeCode == 'HTLX-FWHT' ||
            		row.relDocTypeCode == 'HTLX-ZLHT' || row.relDocTypeCode == 'HTLX-YFHT'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.relDocId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            	}
            	return v;
           }
		}, {
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width: 140,
			process: function (v, row) {
				if (row.docStatus == 0) {
					v = '<img title="�����������ƻ�" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.planNum > row.stockNum) {
						var nowData = new Date();
						var dateArr = (row.planEndDate).split('-');
						var planEndDate = new Date(dateArr[0], parseInt(dateArr[1]), parseInt(dateArr[2]));
						if (nowData.getTime() > planEndDate.getTime()) {
							v = '<img title="��ʱ�������ƻ�" src="images/icon/hred.gif">' + v;
						}
					}
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			width: 60,
			align: 'center',
			process: function (v, row) {
				switch (v) {
					case '1':
						return "δ����";
						break;
					case '2':
						return "��������";
						break;
					case '3':
						return "��������";
						break;
					case '4':
						return "��������";
						break;
					case '5':
						return "�������";
						break;
					case '6':
						return "�ѹر�";
						break;
					case '7':
						return "�������";
						break;
					case '8':
						return "�����";
						break;
					case '9':
						return "�ʼ���";
						break;
					case '10':
						return "�����ʼ�";
						break;
					case '11':
						return "�ʼ����";
						break;
					case '12':
						return "���ڷ���";
						break;
					default:
						return "--";
				}
			}
		}, {
			name: 'urgentLevel',
			display: '���ȼ�',
			sortable: true,
			align: 'center'
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true,
			width: 80,
			align: 'center'
		}, {
			name: 'proType',
			display: '��������',
			sortable: true
		},{
			name: 'productName',
			display: '��������',
			sortable: true,
			width: 200
		}, {
			name: 'productCode',
			display: '���ñ���',
			sortable: true
		}, {
			name: 'planNum',
			display: '����',
			sortable: true,
			width: 60
		}, {
			name: 'qualifiedNum',
			display: '�ʼ�ϸ�����',
			sortable: true,
			width: 80
		}, {
			name: 'stockNum',
			display: '�������',
			sortable: true,
			width: 60
		}, {
			name: 'taskCode',
			display: '�������񵥺�',
			sortable: true,
			width: 120
		}, {
			name: 'applyDocCode',
			display: '�������뵥��',
			sortable: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width: 150
		}, {
			name: 'productionBatch',
			display: '��������',
			sortable: true
		}, {
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��',
			sortable: true,
			align: 'center'
		}, {
			name: 'planEndDate',
			display: '�ƻ�����ʱ��',
			sortable: true,
			align: 'center'
		}, {
			name: 'chargeUserName',
			display: '������',
			sortable: true,
			align: 'center'
		}, {
			name: 'saleUserName',
			display: '���۴���',
			sortable: true,
			align: 'center'
		}, {
			name: 'deliveryDate',
			display: '��������',
			sortable: true,
			align: 'center'
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 350
		}],

		//��չ�˵�
		buttonsEx: [{
			name: 'excelOut',
			text: "����",
			icon: 'excel',
			action: function () {
				showThickboxWin("?model=produce_plan_produceplan&action=toExcelOut&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		},{
			name: 'statistics',
			text: "���ϻ��ܼ���",
			icon: 'view',
			action: function (row, rows, rowIds) {
				if(rows){
	                for (var i = 0; i < rows.length; i++) {
	                    if (rows[i].isMeetPick == '0') {
	                        alert('���� ��' + rows[i].docCode + '��δȷ�����ϣ����ܽ������ϻ��ܼ���');
	                        return false;
	                    }
	                }
					showModalWin("index1.php?model=produce_plan_produceplan&action=toStatisticsProduct&idStr=" + rowIds.toString(), '1');
				} else {
					showModalWin("index1.php?model=produce_plan_produceplan&action=toStatisticsProduct&idStr=", '1');
				}
			}
		}],

		//��չ�Ҽ��˵�
		menusEx: [{
			text: '��ӡ',
			icon: 'view',
			action: function (row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id, '1');
			}
		}, {
			text: '�����Ϣ',
			icon: 'view',
			action: function (row) {
				showModalWin("index1.php?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode, '1');
			}
		}, {
			text: '��������',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.isMeetPick == '1' && (row.docStatus == '0' || row.docStatus == '1' || row.docStatus == '3' || row.docStatus == '4')) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_plan_picking&action=toAddByPlan&planId=' + row.id, '1');
			}
		}, {
			text: '��������',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.isMeetPick == '1' && row.docStatus == '5') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_plan_picking&action=toAddByPlanPlus&planId=' + row.id, '1');
			}
		}, {
			text: '�������',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '7' || row.docStatus == '10' || row.docStatus == '11' || row.docStatus == '12') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=stock_withdraw_innotice&action=toAddByProduce&docType=RKPRODUCT&relDocType=RSCJHD&relDocId=' +
					row.id, '1');
			}
		}],

		//��������
		comboEx: comboEx,

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField], '1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		}, {
			name: 'docDate',
			display: '��������'
		},{
			name: 'proType',
			display: '��������'
		}, {
			name: 'productName',
			display: '��������'
		}, {
			name: 'productCode',
			display: '���ñ���'
		}, {
			name: 'taskCode',
			display: '�������񵥺�'
		}, {
			name: 'relDocCode',
			display: '��ͬ���'
		}, {
			name: 'applyDocCode',
			display: '�������뵥��'
		}, {
			name: 'customerName',
			display: '�ͻ�����'
		}, {
			name: 'productionBatch',
			display: '��������'
		}, {
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��'
		}, {
			name: 'planEndDate',
			display: '�ƻ�����ʱ��'
		}, {
			name: 'chargeUserName',
			display: '������'
		}, {
			name: 'urgentLevel',
			display: '���ȼ�'
		}, {
			name: 'saleUserName',
			display: '���۴���'
		}, {
			name: 'deliveryDate',
			display: '��������'
		}]
	});
});