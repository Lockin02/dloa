var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	//���ݹ�������
	var param = {};
	var comboEx = [{
		text : '���ȼ�',
		key : 'urgentLevelCode',
		datacode : 'SCJHYXJ'
	}];
	if ($("#finish").val() == 'yes') {
		param = {
			docStatusIn : '6,8',
			isCancel : 0
		};
		var comboExArr = {
			text : '����״̬',
			key : 'docStatus',
			data : [{
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
		model : 'produce_plan_produceplan',
		action : 'pageJsonFeedback',
		param : param,
		title : '�����ƻ�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
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
		},{
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width : 140,
			process : function (v ,row) {
				if (row.docStatus == 0) {
					v = '<img title="�����������ƻ�" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.planNum > row.stockNum) {
						var nowData = new Date();
						var dateArr = (row.planEndDate).split('-');
						var planEndDate = new Date(dateArr[0] ,parseInt(dateArr[1]) ,parseInt(dateArr[2]));
						if (nowData.getTime() > planEndDate.getTime()) {
							v = '<img title="��ʱ�������ƻ�" src="images/icon/hred.gif">' + v;
						}
					}
				}
				//�ѽ����ʼ����뵫δ���������Ľ�����ʾ
				if (row.feedbackState == 2) {
					v = v + '<img title="����������" src="images/w_vActionSignIn.gif">';
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			width : 60,
			align : 'center',
			process : function(v ,row) {
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
		},{
			name: 'urgentLevel',
			display: '���ȼ�',
			sortable: true,
			align : 'center'
		},{
			name: 'docDate',
			display: '��������',
			sortable: true,
			width : 80,
			align : 'center'
		},{
			name: 'proType',
			display: '��������',
			sortable: true,
			width : 100
		},{
			name: 'productName',
			display: '��������',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '���ϱ���',
			sortable: true
		},{
			name : 'pattern',
			display : '����ͺ�',
			sortable: true
		},{
			name: 'planNum',
			display: '����',
			sortable: true,
			width : 60
		},{
			name: 'qualityNum',
			display: '�ύ�ʼ�����',
			sortable: true,
			width : 80
		},{
			name: 'qualifiedNum',
			display: '�ʼ�ϸ�����',
			sortable: true,
			width : 80
		},{
			name: 'stockNum',
			display: '�������',
			sortable: true,
			width : 60
		},{
			name: 'taskCode',
			display: '�������񵥺�',
			sortable: true,
			width : 120
		},{
			name: 'applyDocCode',
			display: '�������뵥��',
			sortable: true
		},{
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width : 150
		},{
			name: 'productionBatch',
			display: '��������',
			sortable: true
		},{
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��',
			sortable: true,
			align : 'center'
		},{
			name: 'planEndDate',
			display: '�ƻ�����ʱ��',
			sortable: true,
			align : 'center'
		},{
			name: 'chargeUserName',
			display: '������',
			sortable: true,
			align : 'center'
		},{
			name: 'saleUserName',
			display: '���۴���',
			sortable: true,
			align : 'center'
		},{
			name: 'deliveryDate',
			display: '��������',
			sortable: true,
			align : 'center'
		},{
			name: 'remark',
			display: '��ע',
			sortable: true,
			width : 350
		}],

		//��չ�˵�
		buttonsEx : [{
			name : 'excelOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=produce_plan_produceplan&action=toExcelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : '��ӡ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id ,'1');
			}
		},{
			text : '�����Ϣ',
			icon : 'view',
			action : function(row) {
				showModalWin("index1.php?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode ,'1');
			}
		},{
			text : '����',
			icon : 'excel',
			action : function(row) {
				if (row) {
					window.open("?model=produce_plan_produceplan&action=excelOutOne&id=" + row.id);
				}
			}
		},{
			text : 'ȷ������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toSureProcess&id=' + row.id ,'1');
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus != '4' && row.docStatus != '5' && row.docStatus != '6') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toEdit&id=' + row.id ,'1');
			}
		},{
			text : '���ȷ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '1') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toFeedback&id=' + row.id ,'1');
			}
		},{
			text : '�ر�����',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.docStatus == '0' || row.docStatus == '1')&&(row.ExaStatus!='��������'||row.ExaStatus!='���')) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_plan_produceplan&action=toClose&id=' + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
			}
		},{
			text : 'ȡ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.docStatus == '0' || row.docStatus == '1')&&(row.ExaStatus!='��������'||row.ExaStatus!='���')) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȡ�������񵥷��ص�������������ȴ����Ƿ�ȷ��ȡ����')) {
					$.ajax({
						type : 'POST',
						url : '?model=produce_plan_produceplan&action=toCancel',
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 0) {
								alert('ȡ��ʧ��');
							} else {
								alert("��ȡ����");
								show_page();
							}
							return false;
						}
					});
				}
			}
		},{
			name: 'aduit',
			text: '�ر��������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_produceplan&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		//��������
		comboEx : comboEx,

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		},{
			name: 'docDate',
			display: '��������'
		},{
			name: 'proType',
			display: '��������'
		},{
			name: 'productName',
			display: '��������'
		},{
			name: 'productCode',
			display: '���ϱ���'
		},{
			name : 'pattern',
			display : '����ͺ�'
		},{
			name: 'taskCode',
			display: '�������񵥺�'
		},{
			name: 'relDocCode',
			display: '��ͬ���'
		},{
			name: 'applyDocCode',
			display: '�������뵥��'
		},{
			name: 'customerName',
			display: '�ͻ�����'
		},{
			name: 'productionBatch',
			display: '��������'
		},{
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��'
		},{
			name: 'planEndDate',
			display: '�ƻ�����ʱ��'
		},{
			name: 'chargeUserName',
			display: '������'
		},{
			name: 'urgentLevel',
			display: '���ȼ�'
		},{
			name: 'saleUserName',
			display: '���۴���'
		},{
			name: 'deliveryDate',
			display: '��������'
		}]
	});
});