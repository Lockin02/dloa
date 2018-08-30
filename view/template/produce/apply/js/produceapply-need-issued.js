var show_page = function(page) {
	$("#produceapplyGrid").yxsubgrid("reload");
};

/**
 * ��Ʒ���ò鿴
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId="
			+ thisVal;// + "&goodsName=" + goodsName;

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
	$("#produceapplyGrid").yxsubgrid({
		model : 'produce_apply_produceapply',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		title : '��������',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'relDocCode',
			display : 'Դ�����',
			width : 150,
			sortable : true
		},{
			name : 'docCode',
			display : '���ݱ��',
			width : 80,
			sortable : true,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docType',
			display : '��������',
			sortable : true,
			hide : true
		},{
			name : 'relDocType',
			display : 'Դ������',
			sortable : true,
			width : 80
		},{
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 200
		},{
			name : 'saleUserName',
			display : '���۸�����',
			width : 80,
			sortable : true
		},{
			name : 'applyUserName',
			display : '�µ���',
			width : 80,
			sortable : true
		},{
			name : 'applyDate',
			display : '�µ�����',
			sortable : true
		},{
			name : 'docStatus',
			display : '�´�״̬',
			sortable : true,
			width : 80,
			process : function(v, row) {
				switch (v) {
					case '0' : return "δ�´�";
					case '1' : return "�����´�";
					case '2' : return "ȫ���´�";
					case '3' : return "�ر�";
					case '4' : return "���";
				}
			}
		},{
			width : 80,
			name : 'hopeDeliveryDate',
			display : '������������',
			sortable : true
		},{
			name : 'actualDeliveryDate',
			display : 'ʵ�ʽ�������',
			sortable : true
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 300,
			align : 'left'
		},{
			name : 'createName',
			display : '������',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'updateName',
			display : '�޸���',
			width : 80,
			sortable : true,
			hide : true
		}],

		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_apply_produceapplyitem&action=subItemJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				display : '���ϱ���',
				sortable : true
			},{
				name : 'productName',
				display : '��������',
				width : 200,
				sortable : true
			},{
				name : 'pattern',
				display : '����ͺ�',
				sortable : true
			},{
				name : 'unitName',
				display : '��λ',
				sortable : true
			},{
				name : 'planEndDate',
				display : '�ƻ�����ʱ��',
				sortable : true
			},{
				name : 'produceNum',
				display : '��������',
				sortable : true
			},{
				name : 'exeNum',
				display : '���´�����',
				sortable : true
			},{
				name : 'stockNum',
				display : '���������',
				sortable : true
			},{
				name : 'jmpz',
				display : '��������',
				process : function(v, row) {
					if (row.licenseConfigId > 0) {
						return "<a title='"
								+ row.remark
								+ "' href='#' onclick='showLicense("
								+ row.licenseConfigId
								+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			},{
				name : 'cppz',
				display : '��Ʒ����',
				sortable : true,
				process : function(v, row) {
					if (row.goodsConfigId > 0) {
						return "<a title='"
								+ row.remark
								+ "' href='#' onclick='showGoodsConfig("
								+ row.goodsConfigId
								+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			}]
		},

		toViewConfig : {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴���ԭ��',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.backReason != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toViewBack&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		},{
			text : '�´���������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "0" || row.docStatus == "1") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900');
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == "0") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toBack&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900');
			}
		}],

		comboEx : [ {
			text : '�´�״̬',
			key : 'docStatus',
			data : [{
				text : 'δ�´�',
				value : '0'
			},{
				text : '�����´�',
				value : '1'
			},{
				text : 'ȫ���´�',
				value : '2'
			},{
				text : '�ѹر�',
				value : '3'
			},{
				text : '���',
				value : '4'
			}]
		},{
			text : 'Դ������',
			key : 'relDocTypeCode',
			datacode : 'HTLX'
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'docCode'
		},{
			display : "Դ�����",
			name : 'relDocCode'
		},{
			display : '�ͻ�����',
			name : 'customerName'
		},{
			display : '���۸�����',
			name : 'saleUserName'
		},{
			display : '�µ���',
			name : 'applyUserName'
		},{
			display : '�µ�����',
			name : 'applyDate'
		},{
			display : '������������',
			name : 'hopeDeliveryDate'
		},{
			display : 'ʵ�ʽ�������',
			name : 'actualDeliveryDate'
		},{
			display : '��  ע',
			name : 'remark'
		}]
	});
});