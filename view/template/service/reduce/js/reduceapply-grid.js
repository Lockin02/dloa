var show_page = function(page) {
	$("#reduceapplyGrid").yxsubgrid("reload");
};
function viewReduceDetail(applyId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : applyId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ applyId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");

}
$(function() {
	$("#reduceapplyGrid").yxsubgrid({
		model : 'service_reduce_reduceapply',
		title : 'ά�޷��ü������뵥',
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
					name : 'applyCode',
					display : 'ά�����뵥���',
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='viewReduceDetail("
								+ row.applyId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
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
					name : 'subCost',
					display : 'ά�޷���',
					sortable : true,
					hide : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'subReduceCost',
					display : '�������',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
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
				}], // ���ӱ������

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "���" || row.ExaStatus == "���") {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=toView&id="
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
					showModalWin("?model=service_reduce_reduceapply&action=toEdit&id="
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
					showThickboxWin('controller/service/reduce/ewf_index.php?actTo=ewfSelect&billId='
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
						url : "?model=service_reduce_reduceapply&action=ajaxdeletes",
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
			url : '?model=service_reduce_reduceitem&action=pageJson',
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
						name : 'productType',
						display : '���Ϸ���',
						hide : true
					}, {
						name : 'unitName',
						display : '��λ'
					}, {
						name : 'serilnoName',
						display : '���к�'
					}, {
						name : 'fittings',
						display : '�����Ϣ',
						hide : true
					}, {
						name : 'cost',
						display : '��ȡ����',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}, {
						name : 'reduceCost',
						display : '�������',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}]
		},
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=service_reduce_reduceapply&action=toAdd")
			}
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
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
					display : 'ά�����뵥���',
					name : 'applyCode'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : '���ϱ��',
					name : 'productCode'
				}, {
					display : '��������',
					name : 'productName'
				}]
	});
});