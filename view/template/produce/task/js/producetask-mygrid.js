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
	//���ݹ�������
	var param = {};
	var comboEx = [];
	if ($("#taskType").val() == 'finish') {
		param = {
			docStatusIn : '4'
		};
	} else {
		param = {
			docStatusIn : '0,1,2,3'
		};
		var comboExArr = {
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
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#producetaskGrid").yxgrid({
		model : 'produce_task_producetask',
		param : param,
		title : '��������',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
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
					case '3' : return "ִ����";break;
					case '4' : return "�����";break;
					default : return "--";
				}
			}
		},{
			name : 'productCode',
			display : '���ϱ���',
			sortable : true,
			width : 90
		},{
			name : 'productName',
			display : '��������',
			sortable : true,
			width : 150
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
			name : 'relDocCode',
			display : '��ͬ���',
			sortable : true
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
	buttonsEx : [{
			name : 'add',
			text : "�����ƻ�����",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=produce_apply_produceapply&action=toSendplanReport',1);
			}
		},{
			name : 'add',
			text : "�����ƻ�����",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toProduceplanReport',1);
			}
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : '��������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (window.confirm("ȷ��Ҫ����?")) {
					$.ajax({
						type : "POST",
						url : "?model=produce_task_producetask&action=receiveTask",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == '0') {
								alert('���ճɹ���');
								show_page();
							} else {
								alert('����ʧ�ܣ�');
							}
						}
					});
				}
			}
		},{
			text : '�ƶ������ƻ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus != '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toAddByTask&taskId=' + row.id ,'1');
			}
		}],

		//��������
		comboEx : comboEx,

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems : [{
			display : "���ݱ��",
			name : 'docCode'
		},{
			display : "��������",
			name : 'productName'
		},{
			display : "���ϱ���",
			name : 'productCode'
		},{
			display : "��;",
			name : 'purpose'
		},{
			display : "����",
			name : 'technology'
		},{
			display : "�ļ����",
			name : 'fileNo'
		},{
			display : "�ͻ�����",
			name : 'customerName'
		},{
			display : "��ͬ���",
			name : 'relDocCode'
		},{
			display : "��������",
			name : 'productionBatch'
		},{
			display : "�µ���",
			name : 'docUserName'
		},{
			display : "�µ�����",
			name : 'docDate'
		},{
			display : "������",
			name : 'recipient'
		},{
			display : "��������",
			name : 'recipientDate'
		},{
			display : "���۴���",
			name : 'saleUserName'
		}]
	});
});