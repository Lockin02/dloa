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
			name: 'qualityNum',
			display: '�ύ�ʼ�����',
			sortable: true,
			width: 80
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
			name: 'meetPick',
			text: "ȷ������",
			icon: 'view',
			action: function (row, rows, rowIds) {
				if(rowIds == undefined){
					alert("������ѡ��1�����ݽ���ȷ�����ϣ�");
					return false;
				} else {
					if (window.confirm(("ȷ������?"))) {
						$.ajax({
							type : "POST",
							url : "?model=produce_plan_produceplan&action=meetPick",
							data : {
								ids : rowIds.toString()
							},
							async : false,
							success : function(msg) {
								if (msg == 1) {
									alert("ȷ�ϳɹ�!");
									show_page(1);
								} else {
									alert("ȷ��ʧ��!");
								}
							}
						});
					}
				}
			}
		}],

		//��չ�Ҽ��˵�
		menusEx: [{
			text : 'ȷ������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isMeetPick == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ������?"))) {
					$.ajax({
						type : "POST",
						url : "?model=produce_plan_produceplan&action=meetPick",
						data : {
							ids : row.id
						},
						async : false,
						success : function(msg) {
							if (msg == 1) {
								alert("ȷ�ϳɹ�!");
								show_page(1);
							} else {
								alert("ȷ��ʧ��!");
							}
						}
					});
				}
			}
		},{
			text: '�׼���Ʒȷ��',
			icon: 'edit',
			showMenuFn: function (row) {
				return row.isFirstInspection == '1';
			},
			action: function (row) {
				showThickboxWin('?model=produce_document_document&action=toUploadFile&title=�׼���Ʒȷ��&topId=2&serviceId=' + row.id + '&serviceNo=' + row.docCode +
					'&serviceType=oa_produce_produceplan&styleOne=firstInspection&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		},{
			text: '��֯����',
			icon: 'edit',
			action: function (row) {
				showThickboxWin('?model=produce_document_document&action=toUploadFile&topId=2&serviceId=' + row.id + '&serviceNo=' + row.docCode +
					'&serviceType=oa_produce_produceplan&styleOne=organize&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		},{
			text : '�ύ�ʼ�',
			icon : 'add',
			showMenuFn : function(row) {
				//if (row.planNum-row.qualityNum>0) {
				//	return true;
				//} else {
				//	return false;
				//}
				// ID2193 �޸Ŀ��ύ�ʼ�Ĺ���
				if(row.planNum == row.qualityNum && row.qualifiedNum == row.planNum){// �����ύ�ʼ�����=���������ʼ�ϸ�����=������
					return false;
				}else if(row.planNum == row.qualityNum && row.qualifiedNum < row.planNum && row.docStatus == '12'){// �����ύ�ʼ�����=���������ʼ�ϸ�����<����,��״̬Ϊ���ڷ���
					return false;
				}else{
					return true;
				}
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
						+ row.id
						+ "&relDocType=ZJSQYDSC"
						+ "&relDocCode=" + row.docCode
						,1,500,1000,row.id
					);
				}
			}
		},{
			text: '�޸�����',
			icon: 'edit',
			showMenuFn : function(row) {
				return row.docStatus == '1';
			},
			action: function (row) {
				showOpenWin('?model=produce_plan_produceplan&action=toEditClassify&id='
					+ row.id
					,1,500,1000,row.id
				);
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