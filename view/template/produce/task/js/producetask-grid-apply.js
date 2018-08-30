var show_page = function(page) {
	$("#producetaskGrid").yxgrid("reload");
};
/**
 * ��Ʒ���ò鿴
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId="
			+ thisVal;
	// + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE �鿴����
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id="
			+ thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

$(function() {
	$("#producetaskGrid").yxgrid({
		model : 'produce_task_producetask',
		title : '��������',
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			applyDocId : $("#applyDocId").val()
		},

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'relDocCode',
			display : '��ͬ(Դ��)���',
			sortable : true
		},{
			name : 'docCode',
			display : '���ݱ��',
			sortable : true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docStatus',
			display : '����״̬',
			sortable : true,
			width : '60',
			process : function(v ,row) {
				switch (v) {
					case '0' : return "δ����";break;
					case '1' : return "�ѽ���";break;
					case '2' : return "���ƶ��ƻ�";break;
					case '3' : return "�ر�";break;
					case '4' : return "�����";break;
					default : return "--";
				}
			}
		},{
			name : 'proType',
			display : '��������',
			sortable : true,
			width : 90
		},{
			name : 'purpose',
			display : '��;',
			sortable : true
		},{
			name : 'technology',
			display : '����',
			sortable : true
		},{
			name : 'fileNo',
			display : '�ļ����',
			sortable : true
		},{
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		},{
			name : 'productionBatch',
			display : '��������',
			sortable : true
		},{
			name : 'docUserName',
			display : '�µ���',
			sortable : true
		},{
			name : 'docDate',
			display : '�µ�����',
			sortable : true
		},{
			name : 'recipient',
			display : '������',
			sortable : true
		},{
			name : 'recipientDate',
			display : '��������',
			sortable : true
		},{
			name : 'saleUserName',
			display : '���۴���',
			sortable : true
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250
		}],

		//��չ�Ҽ��˵�
		menusEx : [],

		//��������
		comboEx : [{
			text : '����״̬',
			key : 'docStatus',
			data : [{
				text : 'δ����',
				value : '0'
			},{
				text : '�ѽ���',
				value : '1'
			},{
				text : '���ƶ��ƻ�',
				value : '2'
			},{
				text : '�ر�',
				value : '3'
			},{
				text : '�����',
				value : '4'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p, g) {
				showModalWin("?model=produce_task_producetask&action=toAddByNeed&applyId=" + $("#applyDocId").val() ,'1');
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems : [{
			name : 'docCode',
			display : '���ݱ��'
		},{
			name : 'productCode',
			display : '���ϱ���'
		},{
			name : 'productName',
			display : '��������'
		},{
			name : 'purpose',
			display : '��;'
		},{
			name : 'technology',
			display : '����'
		},{
			name : 'fileNo',
			display : '�ļ����'
		},{
			name : 'customerName',
			display : '�ͻ�����'
		},{
			name : 'relDocCode',
			display : '��ͬ���'
		},{
			name : 'productionBatch',
			display : '��������'
		},{
			name : 'docUserName',
			display : '�µ���'
		},{
			name : 'docDate',
			display : '�µ�����'
		},{
			name : 'recipient',
			display : '������'
		},{
			name : 'recipientDate',
			display : '��������'
		},{
			name : 'saleUserName',
			display : '���۴���'
		}]
	});
});