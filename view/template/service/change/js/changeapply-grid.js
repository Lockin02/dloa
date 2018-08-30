var show_page = function(page) {
	$("#changeapplyGrid").yxsubgrid("reload");
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
	$("#changeapplyGrid").yxsubgrid({
		model : 'service_change_changeapply',
		title : '�豸�������뵥',
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		param : {
			applyUserCode : $("#userId").val()
		},
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
					name : 'rObjCode',
					display : 'Դ��ҵ����',
					sortable : true,
					hide : true
				}, {
					name : 'relDocCode',
					display : 'Դ�����',
					sortable : true,
					process : function(v, row) {
						if (row.relDocType == 'WXSQD') {
							return "<a href='#' onclick='viewApplyDetail("
									+ row.relDocId
									+ ")' >"
									+ v
									+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
						} else {
							return v;
						}

					}
				}, {
					name : 'relDocName',
					display : 'Դ������',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : 'Դ������',
					sortable : true,
					process : function(v) {
						if (v == 'WXSQD') {
							return "ά�����뵥";
						} else {
							return "";
						}
					}
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 200
				}, {
					name : 'adress',
					display : '�ͻ���ַ',
					sortable : true,
					hide : true
				}, {
					name : 'applyUserName',
					display : '����������',
					sortable : true
				}, {
					name : 'applyUserCode',
					display : '�������˺�',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'createId',
					display : '������id',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '�޸���id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}],

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "���" || row.ExaStatus == "���") {
					if (row) {
						showModalWin("?model=service_change_changeapply&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showModalWin("?model=service_change_changeapply&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=service_change_changeapply&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/service/change/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');

				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						data : {
							id : row.id
						},
						url : "?model=service_change_changeapply&action=ajaxdeletes",
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

		subGridOptions : {
			url : '?model=service_change_changeitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ��'
					}, {
						name : 'productName',
						display : '��������',
						width : 250
					}, {
						name : 'pattern',
						display : '����ͺ�'
					}, {
						name : 'unitName',
						display : '��λ'
					}, {
						name : 'serilnoName',
						display : '���к�'
					}, {
						name : 'remark',
						display : '���ԭ��',
						width : 200
					}]
		},
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=service_change_changeapply&action=toAdd")
			}
		},

		toEditConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toView'
		},
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}]
				}],
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : 'Դ�����',
					name : 'relDocCode'
				}, {
					display : 'Դ������',
					name : 'relDocType'
				}, {
					display : '���ϱ��',
					name : 'productCode'
				}, {
					display : '��������',
					name : 'productName'
				}]
	});
});