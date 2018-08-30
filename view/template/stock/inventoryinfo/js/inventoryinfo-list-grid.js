var show_page = function(page) {
	$(".inventoryinfoGrid").yxgrid("reload");
};
$(function() {
	var stockName = "";
	if ($("#stockName").val() != "") {
		stockName = "[" + $("#stockName").val() + "]";
	}

	$(".inventoryinfoGrid").yxgrid({
		model : 'stock_inventoryinfo_inventoryinfo',
		action : 'inventoryinfoJson&stockId=' + $('#stockId').val(),
		title : stockName + "�ڳ������Ϣ",
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		toAddConfig : {
			toAddFn : function(p) {
				var addParam = ""
				if ($('#stockId').val() != "") {
					addParam = "&stockId=" + $('#stockId').val()
							+ "&stockName=" + $('#stockName').val()
							+ "&stockCode=" + $('#stockCode').val();
				}

				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=toAdd"
						+ addParam
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=520&width=900");
			}
		},

		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=520&width=800");
			}
		}, {
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				var showResult = true;
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_inventoryinfo_inventoryinfo&action=checkEditAjax",
					data : {
						id : row.id
					},
					success : function(result) {
						if (result == 0)
							showResult = false;
					}
				})
				return showResult;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=520&width=900");
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=stock_inventoryinfo_inventoryinfo&action=ajaxdeleteInv",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ�ܣ��ֿ��Ѿ���������������ϳ�ʼ������С�ڲ���ǰ����!');
							}
						}
					});
				}
			}
		}],

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ֿ�����',
					name : 'stockName',
					width : 100,
					sortable : true
				}, {
					display : '��������',
					name : 'proType',
					sortable : true,
					align : 'center'
				}, {
					display : '���ϱ��',
					name : 'productCode',
					sortable : true,
					width : 170,
					align : 'center'
				}, {
					display : '��������',
					name : 'productName',
					width : 170,
					sortable : true
				}, {
					name : 'initialNum',
					display : '�ڳ����',
					width : 70,
					sortable : true
				}, {
					name : 'price',
					display : '�ɱ���',
					width : 70,
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'sumAmount',
					display : '���',
					width : 70,
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'safeNum',
					display : '��ȫ���',
					width : 70,
					sortable : true
				}, {
					name : 'actNum',
					display : '��ʱ���',
					width : 70,
					sortable : true,
					hide : true
				}, {
					name : 'exeNum',
					display : '��ִ�п��',
					sortable : true,
					hide : true
				}, {
					name : 'assigedNum',
					display : '�ѷ�����',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'lockedNum',
					display : '������',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'planInstockNum',
					display : 'Ԥ�������',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'maxNum',
					display : '�����',
					width : 70,
					sortable : true
				}, {
					name : 'miniNum',
					display : '��С���',
					width : 70,
					sortable : true

					// }, {
				// name : 'docStatus',
				// display : '���״̬',
				// sortable : true,
				// hide : true
			}],
		buttonsEx : [{
			name : 'import',
			text : "����EXCEL",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=toUploadExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		}, {
			name : 'return',
			text : '����',
			icon : 'back',
			action : function(row, rows, grid) {
				location = "?model=stock_stockinfo_stockinfo";

			}
		}],
		// ��������
		searchitems : [{
					display : '��������',
					name : 'productName'
				}, {
					display : '���ϱ��',
					name : 'productCode'
				}],
		// Ĭ������˳��
		sortorder : "ASC"

	});
});