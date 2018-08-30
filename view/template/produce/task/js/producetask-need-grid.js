var show_page = function (page) {
	$("#producetaskGrid").yxgrid("reload");
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
	$("#producetaskGrid").yxgrid({
		model: 'produce_task_producetask',
		title: '��������',
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		isOpButton: false,
		showcheckbox: false,
		customCode: 'produce_task_producetask',

		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
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
			width: 150,
			process: function (v, row) {
				if (row.docStatus == 0) {
					v = '<img title="��������������" src="images/new.gif">' + v;
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toViewTab&id=" + row.id + "&relDocId=" + row.relDocId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width: 150
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			width: '60',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "δ����";
					break;
				case '1':
					return "�ѽ���";
					break;
				case '5':
					return "�ѷ���";
					break;
				case '2':
					return "���ƶ��ƻ�";
					break;
				case '3':
					return "�ر�";
					break;
				case '4':
					return "�����";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'proType',
			display: '��������',
			sortable: true,
			width: 150
		}, {
			name: 'purpose',
			display: '��;',
			sortable: true
		}, {
			name: 'technology',
			display: '����',
			sortable: true
		}, {
			name: 'fileNo',
			display: '�ļ����',
			sortable: true
		},{
			name: 'productionBatch',
			display: '��������',
			sortable: true
		}, {
			name: 'docUserName',
			display: '�µ���',
			sortable: true
		}, {
			name: 'docDate',
			display: '�µ�����',
			sortable: true
		}, {
			name: 'recipient',
			display: '������',
			sortable: true
		}, {
			name: 'recipientDate',
			display: '��������',
			sortable: true
		}, {
			name: 'saleUserName',
			display: '���۴���',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 250
		}],

		//��չ�˵�
		buttonsEx: [{
			name: 'excelOut',
			text: "����",
			icon: 'excel',
			action: function (row) {
				showThickboxWin("?model=produce_task_producetask&action=toExcelOut&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		}],

		//��������
		comboEx: [{
			text: "��������",
			key: 'taskTypeCode',
			datacode: 'RWLX'
	    },{
			text: '����״̬',
			key: 'docStatusArr',
			value: '0,1,2,5',
			data: [{
				text: 'δ����',
				value: '0'
			}, {
				text: '�ѽ���',
				value: '1'
			}, {
				text: '���ƶ��ƻ�',
				value: '2'
			}, {
				text: '�ѷ���',
				value: '5'
			}, {
				text: '�ѹر�',
				value: '3'
			}, {
				text: 'δ���',
				value: '0,1,2,5'
			}, {
				text: '�����',
				value: '4'
			}]
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toViewTab&id=" + get[p.keyField] + "&relDocId=" + get.relDocId, '1');
				}
			}
		},

		searchitems: [{
			display: "���ݱ��",
			name: 'docCode'
		}, {
			display: "��������",
			name: 'proType'
		}, {
			display: "��;",
			name: 'purpose'
		}, {
			display: "����",
			name: 'technology'
		}, {
			display: "�ļ����",
			name: 'fileNo'
		}, {
			display: "�ͻ�����",
			name: 'customerName'
		}, {
			display: "��ͬ���",
			name: 'relDocCode'
		}, {
			display: "��������",
			name: 'productionBatch'
		}, {
			display: "�µ���",
			name: 'docUserName'
		}, {
			display: "�µ�����",
			name: 'docDate'
		}, {
			display: "������",
			name: 'recipient'
		}, {
			display: "��������",
			name: 'recipientDate'
		}, {
			display: "���۴���",
			name: 'saleUserName'
		}]
	});
});