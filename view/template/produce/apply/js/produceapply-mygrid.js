var show_page = function (page) {
	$("#produceapplyGrid").yxsubgrid("reload");
};

/**
 * ��Ʒ���ò鿴
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId=" + thisVal; // + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE �鿴����
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

$(function () {
	$("#produceapplyGrid").yxsubgrid({
		model: 'produce_apply_produceapply',
		param: {
			"applyUserId": $("#userId").val()
		},
		isAddAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '�ҵ��������뵥',
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'docCode',
			display: '���ݱ��',
			width: 80,
			sortable: true,
			process: function (v, row) {
				if (row.docStatus == 8) { //���������
					v = '<span style="color:red;">' + v + '</span>';
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewTab&noSee=true&id=" +
					row.id + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docType',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'relDocType',
			display: '��ͬ����',
			sortable: true,
			width: 80
		}, {
			name: 'relDocCode',
			display: '��ͬ���',
			width: 150,
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
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width: 200
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 150
		}, {
			name: 'saleUserName',
			display: '���۸�����',
			width: 80,
			sortable: true
		}, {
			name: 'applyUserName',
			display: '�µ���',
			width: 80,
			sortable: true
		}, {
			name: 'applyDate',
			display: '�µ�����',
			sortable: true
		}, {
			name: 'docStatus',
			display: '�´�״̬',
			sortable: true,
			width: 80,
			process: function (v, row) {
				switch (v) {
				case '0':
					return "δ�´�";
					break;
				case '1':
					return "�����´�";
					break;
				case '2':
					return "ȫ���´�";
					break;
				case '3':
					return "�ر�";
					break;
				case '4':
					return "���";
					break;
				case '5':
					return "����";
					break;
				case '6':
					return "������";
					break;
				case '7':
					return "�������";
					break;
				case '8':
					return "���������";
					break;
				case '9':
					return "���ִ��";
					break;
				}
			}
		}, {
			name: 'hopeDeliveryDate',
			display: '������������',
			width: 80,
			sortable: true
		}, {
			name: 'actualDeliveryDate',
			display: 'ʵ�ʽ�������',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 300,
			align: 'left'
		}, {
			name: 'createName',
			display: '������',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'updateName',
			display: '�޸���',
			width: 80,
			sortable: true,
			hide: true
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_apply_produceapplyitem&action=subItemJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '���ϱ���',
				sortable: true,
				width: 150,
				process: function (v, row) {
					if (row.state == 1) {
						return v + '<span style="color:red">���ѹرգ�</span>';
					} else if (row.state == 2) {
						return v + '<span style="color:green">���Ѵ�أ�</span>';
					} else {
						return v;
					}
				}
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
				name: 'planEndDate',
				display: '�ƻ�����ʱ��',
				sortable: true
			}, {
				name: 'produceNum',
				display: '��������',
				sortable: true
			}, {
				name: 'exeNum',
				display: '���´�����',
				sortable: true
			}, {
				name: 'stockNum',
				display: '���������',
				sortable: true
			}, {
				name: 'jmpz',
				display: '��������',
				process: function (v, row) {
					if (row.licenseConfigId > 0) {
						return "<a title='" + row.remark + "' href='#' onclick='showLicense(" + row.licenseConfigId +
							")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			}, {
				name: 'cppz',
				display: '��Ʒ����',
				sortable: true,
				process: function (v, row) {
					if (row.goodsConfigId > 0) {
						return "<a title='" + row.remark + "' href='#' onclick='showGoodsConfig(" + row.goodsConfigId +
							")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			}]
		},

		//��չ�˵�
		buttonsEx: [{
			name: 'add',
			text: "����",
			icon: 'add',
			action: function (row) {
				showModalWin('?model=produce_apply_produceapply&action=toAddDepartment', 1);
			}
		}],

		//��չ�Ҽ��˵�
		menusEx: [{
			text: '�鿴���ԭ��',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.backReason != '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toViewBack&id=' + row.id +
					'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		}, {
			text: '�༭���',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.docStatus == '9') { //����״̬Ϊ���ִ��
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin("?model=produce_apply_produceapply&action=toEditBack&id=" + row.id, 1);
			}
		}, {
			text: '���',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.docStatus == '1') { //����״̬Ϊ�����´�
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row.projectName != '') { //�������ű��
					showModalWin("?model=produce_apply_produceapply&action=toChange&department=true&id=" + row.id, 1);
				} else {
					showModalWin("?model=produce_apply_produceapply&action=toChange&id=" + row.id, 1);
				}
			}
		}, {
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if ((row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') && row.projectName != '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_produceapply&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		toEditConfig: {
			showMenuFn: function (row) {
				//��ء����桢�������
				if (row.docStatus == '4' || row.docStatus == '5' || row.docStatus == '7') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get["projectName"] != '') {
						//�������ű༭
						showModalWin("?model=produce_apply_produceapply&action=toEdit&department=true&id=" + get[p.keyField], 1);
					} else {
						showModalWin("?model=produce_apply_produceapply&action=toEdit&id=" + get[p.keyField], 1);
					}
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toViewTab&noSee=true&id=" + get[p.keyField], 1);
				}
			}
		},

		comboEx: [{
			text: '�´�״̬',
			key: 'docStatus',
			data: [{
				text: 'δ�´�',
				value: '0'
			}, {
				text: '�����´�',
				value: '1'
			}, {
				text: 'ȫ���´�',
				value: '2'
			}, {
				text: '�ر�',
				value: '3'
			}, {
				text: '���',
				value: '4'
			}, {
				text: '����',
				value: '5'
			}, {
				text: '������',
				value: '6'
			}, {
				text: '�������',
				value: '7'
			}, {
				text: '���������',
				value: '8'
			}, {
				text: '���ִ��',
				value: '9'
			}]
		}, {
			text: '��ͬ����',
			key: 'relDocTypeCode',
			data: [{
				text: '���ۺ�ͬ',
				value: 'HTLX-XSHT'
			}, {
				text: '�����ͬ',
				value: 'HTLX-FWHT'
			}, {
				text: '���޺�ͬ',
				value: 'HTLX-ZLHT'
			}, {
				text: '�з���ͬ',
				value: 'HTLX-YFHT'
			}, {
				text: '���к�ͬ',
				value: 'allContract'
			}, {
				text: '�з�����',
				value: 'SCYDLX-01'
			}, {
				text: '��������',
				value: 'SCYDLX-02'
			}, {
				text: '������',
				value: 'SCYDLX-03'
			}]
		}],

		searchitems: [{
			display: "���ݱ��",
			name: 'docCode'
		}, {
			display: "��ͬ���",
			name: 'relDocCode'
		}, {
			display: '�ͻ�����',
			name: 'customerName'
		}, {
			display: '��Ŀ����',
			name: 'projectName'
		}, {
			display: '���۸�����',
			name: 'saleUserName'
		}, {
			display: '�µ�����',
			name: 'applyDate'
		}, {
			display: '������������',
			name: 'hopeDeliveryDate'
		}, {
			display: 'ʵ�ʽ�������',
			name: 'actualDeliveryDate'
		}, {
			display: '��  ע',
			name: 'remark'
		}]
	});
});