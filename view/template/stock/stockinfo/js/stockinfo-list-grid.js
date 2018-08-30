// 用于新增/修改后回调刷新表格
var show_page = function() {
	$(".stockinfoGrid").yxgrid("reload");
};
$(function() {
	$(".stockinfoGrid").yxgrid({
		model: 'stock_stockinfo_stockinfo',
		title: "仓库信息管理",
		menuWidth: 140,
		isViewAction: false,
		isEditAction: false,
		isAddAction: true,
		isDelAction: false,
		showcheckbox: false, // 显示checkbox
		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '仓库代码',
			name: 'stockCode',
			sortable: true,
			align: 'center'
		}, {
			display: '仓库名称',
			name: 'stockName',
			sortable: true,
			width: '150',
			align: 'center'
		}, {
			display: '仓库类型',
			name: 'stockType',
			sortable: true,
			datacode: 'CKLX',
			width: '50',
			align: 'center'
		}, {
			display: '仓库用途',
			name: 'stockUseCode',
			sortable: true,
			align: 'center',
			datacode: 'CKYT'
		}, {
			display: '所属公司',
			name: 'businessBelongName',
			sortable: true,
			align: 'center'
		}, {
			display: '仓库管理员',
			name: 'chargeUserName',
			sortable: true,
			width: '100',
			align: 'center'
		}, {
			display: '仓库地址',
			name: 'adress',
			sortable: true,
			width: '250',
			align: 'center'
		}],
		menusEx: [{
			name: 'view',
			text: "查看",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=view&id="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}, {
			name: 'edit',
			text: "修改仓库信息",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.id != "-1";
			},
			action: function(row) {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=init&id="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.id != "-1";
			},
			action: function(row) {
				if (window.confirm("确认要删除?")) {
					$.ajax({
						type: "POST",
						url: "?model=stock_stockinfo_stockinfo&action=ajaxdeleteStock",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败，请确认仓库是否已经初始化!');
							}
						}
					});
				}
			}
		}, {
			name: 'view',
			text: "库存信息",
			icon: 'business',
			action: function(row) {
				location = "?model=stock_inventoryinfo_inventoryinfo&action=index&stockId="
				+ row.id
				+ "&stockName="
				+ row.stockName
				+ "&stockCode=" + row.stockCode;
			}
		}],
		// 快速搜索
		searchitems: [{
			display: '仓库名称',
			name: 'stockName'
		}, {
			display: '仓库代码',
			name: 'stockCode'
		}],
		buttonsEx: [{
			name: 'import',
			text: "导入EXCEL",
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=toUploadExcel"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		}, {
			name: 'check',
			text: "库存校验",
			icon: 'excel',
			action: function() {
				showModalWin("?model=stock_stockinfo_stockinfo&action=toCheckStockBalance", 1, "check");
			}
		}],
		// 默认搜索顺序
		sortname: 'id',
		sortorder: 'DSC'
	});
});