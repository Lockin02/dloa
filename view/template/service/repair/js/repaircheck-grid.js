var show_page = function(page) {
	$("#repaircheckGrid").yxgrid("reload");
};


/**
 * �鿴�����걨����ϸ��Ϣ
 *
 * @param {}
 *            id
 */
function viewCheckDetail(applyDocId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : applyDocId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ applyDocId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}


$(function() {
	$("#repaircheckGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '���ά������',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true
				}, {
					name : 'applyDocCode',
					display : 'ά�����뵥���',
					process : function(v, row) {
						return "<a href='#' onclick='viewCheckDetail("
								+ row.applyDocId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'issuedUserCode',
					display : '�´���code',
					sortable : true,
					hide : true
				}, {
					name : 'issuedUserName',
					display : '�´���',
					sortable : true,
					hide : true
				}, {
					name : 'issuedTime',
					display : '�´�ʱ��',
					sortable : true,
					width : 150,
					hide : true
				},{
					name : 'repairDeptCode',
					display : '���ά�޲���code',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptName',
					display : '���ά�޲���',
					sortable : true
				}, {
					name : 'repairUserCode',
					display : '���ά����Աcode',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserName',
					display : '���ά����Ա',
					sortable : true
				}, {
					name : 'productType',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '���ϱ��',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					width : 250
				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true,
					hide : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true,
					hide : true
				}, {
					name : 'serilnoName',
					display : '���к�',
					sortable : true,
					hide : true
				}, {
					name : 'fittings',
					display : '�����Ϣ',
					sortable : true,
					hide : true
				}, {
					name : 'troubleInfo',
					display : '��������',
					sortable : true
				}, {
					name : 'checkInfo',
					display : '��⴦����',
					sortable : true
				}, {
					name : 'isAgree',
					display : '�Ƿ�ͬ��ά��',
					sortable : true,
					width : 70,
					process : function(v) {
						if (v == '0') {
							return "��";
						} else if (v == '1') {
							return "��";
						} else if (v == '2') {
							return "δȷ��";
						} else {
							return "δȷ��";
						}
					}
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					width : 50,
					process : function(v) {
						if (v == 'YJC') {
							return "�Ѽ��";
						} else if (v == 'YWX') {
							return "��ά��";
						} else if (v == 'WJC') {
							return "δ���";
						} else if (v == 'DHCJ') {
							return "����ؼ�";
						} else {
							return "δ���";
						}
					}
				}, {
					name : 'finishTime',
					display : 'ά�����ʱ��',
					sortable : true

				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},

		menusEx : [{
			text : '֪ͨά��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isAgree == "2" && row.docStatus == "YJC") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toIsagree&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		},{
			text : '����ؼ�',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isAgree == "2" && row.docStatus == "YJC") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ������ؼ���?"))) {
					$.ajax({
						type : "POST",
						url : "?model=service_repair_repaircheck&action=ajaxStateBack",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('��سɹ���');
								$("#repaircheckGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == "WJC") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						data : {
							id : row.id
						},
						url : "?model=service_repair_repaircheck&action=ajaxdeletes",
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
							}
						}
					});
				}
			}
		}],
		comboEx : [{
					text : '����״̬',
					key : 'docStatus',
					data : [{
								text : 'δ���',
								value : 'WJC'
							}, {
								text : '�Ѽ��',
								value : 'YJC'
							}, {
								text : '��ά��',
								value : 'YWX'
							}]
				}],
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : '��������',
					name : 'productName'
				}]
	});
});