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

$(function() {
	$("#applyitemGrid").yxgrid({
		model : 'service_repair_applyitem',
		title : 'δ�ύά���嵥',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : true,
		param : {
			"quoteIdNull" : null
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
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true,
					width : 50
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
					},
					width : 50
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
					},
					width : 50

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

		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "���ϱ��",
					name : 'productCode'
				}, {

					display : "��������",
					name : 'productName'
				}],
		buttonsEx : [{
			name : 'business',
			text : "�ύ����",
			icon : 'business',
			action : function(row) {
				// alert($("#applyitemGrid").yxgrid("getCheckedRowIds").toString())
				var ids = $("#applyitemGrid").yxgrid("getCheckedRowIds")
						.toString();
				// .toString();
				// alert(ids)
				showModalWin("?model=service_repair_repairquote&action=toAdd&itemIds="
						+ ids
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
			}
		}],
		menusEx : [{
			text : '���ر���',
			icon : 'business',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ�����ر�����"))) {
					$.ajax({
						type : "POST",
						data : {
							id : row.id
						},
						url : "?model=service_repair_applyitem&action=cancelQuote",
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('���سɹ���');
							} else {
								alert('����ʧ�ܣ��ö�������Ѿ�������!');
							}
						}
					});
				}
			}
		}]
	});
});