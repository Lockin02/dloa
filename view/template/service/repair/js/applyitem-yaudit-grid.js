var show_page = function(page) {
	$("#applyitemGrid").yxgrid("reload");
};

/**
 * �鿴ά�����뵥������Ϣ
 *
 * @param {}
 *            id
 */
function viewApplyDetail(mainId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : mainId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ mainId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}

/**
 * �鿴�����걨����ϸ��Ϣ
 *
 * @param {}
 *            id
 */
function viewQuoteDetail(quoteId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairquote&action=md5RowAjax",
				data : {
					"id" : quoteId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairquote&action=toView&id="
			+ quoteId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}

$(function() {
	$("#applyitemGrid").yxgrid({
		model : 'service_repair_applyitem',
		title : '�����ά���嵥',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			"quoteIdAudit" : null
		},
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '���Ϸ���',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '���ϱ��',
					sortable : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					width : 250
				}, {
					name : 'applyCode',
					display : 'ά�����뵥���',
					process : function(v, row) {
						return "<a href='#' onclick='viewApplyDetail("
								+ row.mainId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'quoteCode',
					display : '�걨�����',
					process : function(v, row) {
						return "<a href='#' onclick='viewQuoteDetail("
								+ row.quoteId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true
				}, {
					name : 'serilnoName',
					display : '���к�',
					sortable : true
				}, {
					name : 'fittings',
					display : '�����Ϣ',
					sortable : true,
					hide : true
				}, {
					name : 'place',
					display : '���ڵص�',
					sortable : true,
					hide : true
				}, {
					name : 'troubleInfo',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'checkInfo',
					display : '��⴦����',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}, {
					name : 'isGurantee',
					display : '�Ƿ����',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "��";
						} else {
							return "��";
						}
					}
				}, {
					name : 'repairType',
					display : '��������',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "�շ�ά��";
						}
						if (val = "1") {
							return "����ά��";
						} else {
							return "�ڲ�ά��";
						}
					}

				}, {
					name : 'repairCost',
					display : 'ά�޷���',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'cost',
					display : '��ȡ����',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'isDetect',
					display : '�Ƿ����´���ά��',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "δ�´�";
						} else {
							return "���´�";
						}
					},
					hide : true
				}, {
					name : 'delivery',
					display : '�Ƿ��ѷ���',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "δ����";
						} else {
							return "�ѷ���";
						}
					},
					hide : true

				}],
		searchitems : [{
					display : "���ϱ��",
					name : 'productCode'
				}, {

					display : "��������",
					name : 'productName'
				}],
		toViewConfig : {
			action : 'toView'
		}
	});
});