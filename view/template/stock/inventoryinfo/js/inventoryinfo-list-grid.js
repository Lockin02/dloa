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
		title : stockName + "期初库存信息",
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
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=520&width=800");
			}
		}, {
			name : 'edit',
			text : "修改",
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
			text : '删除',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm("确认要删除?")) {
					$.ajax({
						type : "POST",
						url : "?model=stock_inventoryinfo_inventoryinfo&action=ajaxdeleteInv",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败，仓库已经发生出入库或此物料初始化日期小于财务当前周期!');
							}
						}
					});
				}
			}
		}],

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '仓库名称',
					name : 'stockName',
					width : 100,
					sortable : true
				}, {
					display : '物料类型',
					name : 'proType',
					sortable : true,
					align : 'center'
				}, {
					display : '物料编号',
					name : 'productCode',
					sortable : true,
					width : 170,
					align : 'center'
				}, {
					display : '物料名称',
					name : 'productName',
					width : 170,
					sortable : true
				}, {
					name : 'initialNum',
					display : '期初库存',
					width : 70,
					sortable : true
				}, {
					name : 'price',
					display : '成本价',
					width : 70,
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'sumAmount',
					display : '金额',
					width : 70,
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'safeNum',
					display : '安全库存',
					width : 70,
					sortable : true
				}, {
					name : 'actNum',
					display : '即时库存',
					width : 70,
					sortable : true,
					hide : true
				}, {
					name : 'exeNum',
					display : '可执行库存',
					sortable : true,
					hide : true
				}, {
					name : 'assigedNum',
					display : '已分配量',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'lockedNum',
					display : '锁存量',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'planInstockNum',
					display : '预计入库量',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'maxNum',
					display : '最大库存',
					width : 70,
					sortable : true
				}, {
					name : 'miniNum',
					display : '最小库存',
					width : 70,
					sortable : true

					// }, {
				// name : 'docStatus',
				// display : '库存状态',
				// sortable : true,
				// hide : true
			}],
		buttonsEx : [{
			name : 'import',
			text : "导入EXCEL",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_inventoryinfo_inventoryinfo&action=toUploadExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		}, {
			name : 'return',
			text : '返回',
			icon : 'back',
			action : function(row, rows, grid) {
				location = "?model=stock_stockinfo_stockinfo";

			}
		}],
		// 快速搜索
		searchitems : [{
					display : '物料名称',
					name : 'productName'
				}, {
					display : '物料编号',
					name : 'productCode'
				}],
		// 默认搜索顺序
		sortorder : "ASC"

	});
});