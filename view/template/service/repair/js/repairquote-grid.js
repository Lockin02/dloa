var show_page = function(page) {
	$("#repairquoteGrid").yxsubgrid("reload");
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
	$("#repairquoteGrid").yxsubgrid({
		model : 'service_repair_repairquote',
		title : 'ά�ޱ����걨��',
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
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
					name : 'chargeUserCode',
					display : '�ϱ���code',
					sortable : true,
					hide : true
				}, {
					name : 'chargeUserName',
					display : '�ϱ�������',
					sortable : true
				}, {
					name : 'docDate',
					display : '�ϱ�ʱ��',
					sortable : true,
					width : 150
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true
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
//		toViewConfig : {
//			toViewFn : function(p, g) {
//				action : showModalWin("?model=service_repair_repairquote&action=toView&id="
//						+ g.getSelectedRow().data('data')['id']
//						+ "&skey="
//						+ g.getSelectedRow().data('data')['skey_'])
//			}
//		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "���" || row.ExaStatus == "���") {
					if (row) {
						showModalWin("?model=service_repair_repairquote&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showModalWin("?model=service_repair_repairquote&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		},{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���")
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=service_repair_repairquote&action=toEdit&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ") {
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
						url : "?model=service_repair_repairquote&action=ajaxdeletes",
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
					var maxCost=0;
					$.ajax({
						type : "POST",
						async : false,
						data : {
							id : row.id
						},
						url : "?model=service_repair_repairquote&action=getItemMaxMoney",
						success : function(result) {
							maxCost=result;
						}
					});
											showThickboxWin("controller/service/repair/ewf_index.php?actTo=ewfSelect&billId="
													+ row.id
													+ "&flowMoney="
													+ maxCost
													+ "&examCode=oa_service_repair_quote&formName=ά�ޱ�������"
													+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");

				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=service_repair_applyItem&action=pageJson',
			param : [{
						paramId : 'quoteId',
						colId : 'id'
					}],
			colModel : [{
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
					}]
		},
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : '�ϱ�������',
					name : 'chargeUserName'
				}],
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							}]
				}]
	});
});