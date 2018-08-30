var show_page = function (page) {
	$("#produceapplyGrid").yxgrid("reload");
};

/**
 * ��Ʒ���ò鿴
 * @param thisVal
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId=" + thisVal;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE �鿴����
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
	$("#produceapplyGrid").yxgrid({
		model: 'produce_apply_produceapplyitem',
		action: 'mainPageJson',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '�����ƻ�',
		param: {
			startWeekDate: $("#startWeekDate").val(),
			endWeekDate: $("#endWeekDate").val(),
			pDocStatusIn: '1,2,8'
		},
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'relDocCode',
			display: '��ͬ(Դ��)���',
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
			name: 'docCode',
			display: '���ݱ��',
			width: 110,
			sortable: true,
			process: function (v, row) {
				if (row.docStatus == 0) {
					v = '<img title="��������������" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.actualDeliveryDate == '') {
						var nowData = new Date();
						var dateArr = (row.hopeDeliveryDate).split('-');
						var hopeDeliveryDate = new Date(dateArr[0], parseInt(dateArr[1]), parseInt(dateArr[2]));
						if (nowData.getTime() > hopeDeliveryDate.getTime()) {
							v = '<img title="��ʱ����������" src="images/icon/hred.gif">' + v;
						}
					}
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewTab&id=" + row.mainId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width: 200
		}, {
			name: 'tDocStatus',
			display: '����״̬',
			sortable: true,
			process: function (v) {
				if (v == '0') {
					return 'δ����';
				} else if (v == 1) {
					return 'δ�ƶ��ƻ�';
				} else if (v == 2) {
					return '���ƶ��ƻ�';
				}
			}
		}, {
			name: 'productCode',
			display: '���ϱ���',
			sortable: true,
			width: 150,
			process: function (v, row) {
				if (row.state == 1) {
					return v + '<span style="color:red">���ѹرգ�</span>';
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
			name: 'applyDate',
			display: '�µ�����',
			sortable: true
		}, {
			name: 'relDocType',
			display: 'Դ������',
			sortable: true,
			width: 80
		}, {
			name: 'saleUserName',
			display: '���۸�����',
			width: 80,
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
		}],

		//��չ�Ҽ��˵�
		menusEx: [{
			text: '�ƶ������ƻ�',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '1' && row.taskId > 0) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_plan_produceplan&action=toAddByTask&taskId=' + row.taskId, '1');
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toViewTab&id=" + get['mainId'], '1');
				}
			}
		},

		comboEx: [{
			text: '�´�״̬',
			key: 'tDocStatusIn',
			data: [{
				text: 'δ����',
				value: '0'
			}, {
				text: 'δ�ƶ��ƻ�',
				value: '1'
			}, {
				text: '���ƶ��ƻ�',
				value: '2'
			}]
		}],

		searchitems: [{
			display: "���ݱ��",
			name: 'docCode'
		}, {
			display: '���ϱ���',
			name: 'productCode'
		}, {
			display: '��������',
			name: 'productName'
		}, {
			display: "Դ�����",
			name: 'relDocCode'
		}, {
			display: '�ͻ�����',
			name: 'customerName'
		}, {
			display: '���۸�����',
			name: 'saleUserName'
		}, {
			display: '�µ���',
			name: 'applyUserName'
		}]
	});
});