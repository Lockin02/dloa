var show_page = function (page) {
	$("#pickingGrid").yxsubgrid("reload");
};

$(function () {
	//���ݹ�������
//	var param = {};
//	var comboEx = [];
//	if ($("#finish").val() == 'yes') {
//		var isAdd = false;
//		param = {
//			createId : $("#userId").val(),
//			docStatusIn : '2,4,5'
//		};
//		var comboExArr = {
//				text: '����״̬',
//				key: 'docStatus',
//				data: [{
//					text: '�������',
//					value: '2'
//				}, {
//					text: '�������',
//					value: '4'
//				}, {
//					text: '�������',
//					value: '5'
//				}]
//			};
//	} else {
//		var isAdd = true;
//		param = {
//			createId : $("#userId").val(),
//			docStatusIn : '0,1,3'
//		};
//		var comboExArr = {
//			text: '����״̬',
//			key: 'docStatus',
//			data: [{
//				text: 'δ�ύ',
//				value: '0'
//			}, {
//				text: '������',
//				value: '1'
//			}, {
//				text: '���',
//				value: '3'
//			}]
//		};
//	}
//	comboEx.push(comboExArr);

	$("#pickingGrid").yxsubgrid({
		model: 'produce_plan_picking',
		param: {createId : $("#userId").val()},
		title: '�����������뵥',
//		isAddAction: isAdd,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'relDocCode',
			display: 'Դ�����',
			sortable: true,
			width: 130,
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
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "δ�ύ";
					break;
				case '1':
					return "������";
					break;
				case '2':
					return "�������";
					break;
				case '3':
					return "���";
					break;
				case '4':
					return "�������";
					break;
				case '5':
					return '�������';
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true
		}, {
			name: 'relDocName',
			display: 'Դ������',
			sortable: true,
			width: 200
		}, {
			name: 'relDocType',
			display: 'Դ������',
			sortable: true
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'moduleName',
			display: '�������',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 400
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_plan_pickingitem&action=pageJson',
			param: [{
				paramId: 'pickingId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '���ϱ���',
				sortable: true,
				width: 120
			}, {
				name: 'productName',
				display: '��������',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '����ͺ�',
				sortable: true
			}, {
				name: 'unitName',
				display: '��λ',
				sortable: true
			}, {
				name: 'applyNum',
				display: '��������',
				width: 60,
				sortable: true
			}, {
				name: 'realityNum',
				display: '��������',
				width: 60,
				sortable: true
			}, {
				name: 'planDate',
				display: '�ƻ���������',
				width: 80,
				sortable: true
			}, {
				name: 'realityDate',
				display: 'ʵ����������',
				width: 80,
				sortable: true
			}, {
				name: 'relDocCode',
				display: 'Դ�����',
				sortable: true,
				width: 130,
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
				name: 'relDocName',
				display: 'Դ������',
				sortable: true,
				width: 200
			}, {
				name: 'customerName',
				display: '�ͻ�����',
				width: 200,
				sortable: true
			}]
		},

		//�����Ҽ��˵�
		menusEx: [{
			text: "����",
			icon: 'excel',
			action: function (row, rows, grid) {
				if (row) {
					window.open("?model=produce_plan_picking&action=excelOut&id=" + row.id);
				}
			}
		}, {
			text: "�ύ����",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId=' + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}, {
			text: '��������',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == "��������") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��Ҫ��������?")) {
						$.ajax({
							type: "POST",
							url: "?model=common_workflow_workflow&action=isAuditedContract",
							data: {
								billId: row.id,
								examCode: 'oa_produce_picking'
							},
							success: function (msg) {
								if (msg == '1') {
									alert('����ʧ�ܣ�ԭ��\n1.�ѳ�������,�����ظ�����\n2.�����Ѿ�����������Ϣ�����ܳ�������');
									return false;
								} else {
									$.ajax({
										type: 'GET',
										url: 'controller/produce/plan/ewf_index.php?actTo=delWork',
										data: {
											billId: row.id
										},
										async: false,
										success: function (data) {
											alert(data);
											show_page(1);
										}
									});
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.docStatus == '0' || row.docStatus == '3') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=produce_plan_picking&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							}else{
								alert('ɾ��ʧ�ܣ�');
							}
						}
					});
				}
			}
		}, {
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}, {
			name: 'add',
			text: '��������',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '2' || row.docStatus == '4' || row.docStatus == '5') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=produce_plan_back&action=toAddByPicking&pickingId=" + row.id, 1);
				}
			}
		}],
		buttonsEx:[{
	        name: 'view',
	        text: "�߼���ѯ",
	        icon: 'view',
	        action: function () {
	            showThickboxWin("?model=produce_plan_picking&action=toSearch&"
	            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
	        }
		}],
		comboEx: [{
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: 'δ�ύ',
				value: '0'
			}, {
				text: '������',
				value: '1'
			}, {
				text: '�������',
				value: '2'
			}, {
				text: '���',
				value: '3'
			}, {
				text: '�������',
				value: '4'
			}, {
				text: '�������',
				value: '5'
			}]
		}],

		toAddConfig: {
			toAddFn: function () {
				showModalWin("?model=produce_plan_picking&action=toAdd", '1');
			}
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.docStatus == '0' || row.docStatus == '3') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get['relDocId'] > 0) {
						showModalWin("?model=produce_plan_picking&action=toEdit&id=" + get[p.keyField], '1');
					} else {
						showModalWin("?model=produce_plan_picking&action=toEdit&isCaculate=true&id=" + get[p.keyField], '1');
					}
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		}, {
			name: 'docDate',
			display: '��������'
		}, {
			name: 'irelDocCode',
			display: 'Դ�����(��ͬ��)'
		}, {
			name: 'irelDocName',
			display: 'Դ������'
		}, {
			name: 'irelDocType',
			display: 'Դ������'
		}, {
			name: 'icustomerName',
			display: '�ͻ�����'
		}, {
			name: 'iproductCode',
			display: '���ϱ���'
		}, {
			name: 'iproductName',
			display: '��������'
		}, {
			name: 'createName',
			display: '������'
		}]
	});
});