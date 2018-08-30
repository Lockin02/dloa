/*******************************************************************************
 * 即时库存查询,提供一个公用的库存查询，用来查询库存的信息 2011-02-16 by can
 */
var show_page = function(page) {
	$(".stockcheckGrid").yxgrid("reload");
};
function viewProDetail(productId) {
	showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
			+ productId
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");

}

$(function() {
	$("#stockTree").yxtree({
				showLine : false,
				url : '?model=stock_stockinfo_stockinfo&action=getTreeStock',
				event : {
					"node_click" : function(event, treeId, treeNode) {
						var stockcheckGrid = $(".stockcheckGrid")
								.data('yxgrid');
						$("#stockId").val(treeNode.id);
						stockcheckGrid.options.extParam['stockId'] = treeNode.id;
						stockcheckGrid.option("newp",1);//恢复第一页
						stockcheckGrid.reload();
					},
					"node_success" : function() {
						$("#stockTree").yxtree("expandAll");
					}
				}
			});

	$(".stockcheckGrid").yxgrid({
		model : 'stock_inventoryinfo_inventoryinfo',
		action : 'pageInTimeJson&stockId=' + $("#stockId").val()
				+ "&productId=" + $("#productId").val(),
		title : "即时库存查询",
		// 是否显示工具栏
		isToolBar : false,
		// 是否显示查看按钮/菜单
		isViewAction : false,
		// 是否显示修改按钮/菜单
		isEditAction : false,
		// 是否显示添加按钮/菜单
		isAddAction : false,
		// 是否显示删除按钮/菜单
		isDelAction : false,
		isRightMenu : false,
		// 是否显示多选框
		showcheckbox : false,
		isPrintAction : true,

		// menusEx : [{
		// name : 'view',
		// text : "查看",
		// icon : 'view',
		// action : function(row, rows, grid) {
		// showOpenWin("?model=stock_inventoryinfo_inventoryinfo&action=viewInfo&id="
		// + row.id
		// + "&typecode="
		// + row.typecode
		// +
		// "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
		// }
		// }],

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
					align : 'center',
					process : function(v, row) {
						return "<a href='#' onclick='viewProDetail("
								+ row.productId
								+ ")' >"
								+ v
								+ " <img title='物料详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					display : '物料名称',
					name : 'productName',
					width : 170,
					sortable : true

				}, {
					display : 'k3编码',
					name : 'k3Code',
					width : 170,
					sortable : true

				}, {
					name : 'actNum',
					display : '即时库存',
					width : 70,
					sortable : true
				}, {
					name : 'exeNum',
					display : '可执行库存',
					width : 70,
					sortable : true

				}, {
					name : 'assigedNum',
					display : '已分配量',
					width : 70,
					sortable : true,
					hide : true
				}, {
					name : 'lockedNum',
					display : '锁存量',
					width : 70,
					process : function(v, row) {
						// row.productId,row.stockId
						return "<a href='?model=stock_lock_lock&action=readLockNum&productId="
								+ row.productId
								+ "&stockId="
								+ row.stockId
								+ "'><b>" + v + "</></a>";
					},
					sortable : true
				}, {
					name : 'auditNum',
					display : '审批数量',
					width : 70,
					sortable : true
				}, {
					name : 'planInstockNum',
					display : '在途数量',
					width : 70,
					sortable : true
				}, {
					name : 'initialNum',
					display : '期初库存',
					width : 70,
					sortable : true
				}, {
					name : 'safeNum',
					display : '安全库存',
					width : 50,
					sortable : true
				}, {
					name : 'docStatus',
					display : '库存状态',
					sortable : true,
					hide : true
				}],
		// 快速搜索
		searchitems : [{
					display : '物料名称',
					name : 'productName'
				}, {
					display : '物料编号',
					name : 'productCode'

				}, {
					display : 'k3编码',
					name : 'k3Code'
				}],
		// 默认搜索顺序
		sortorder : "ASC",
		// 扩展按钮
		buttonsEx : [{
			name : 'excel',
			text : '导出EXCEL',
			icon : 'excel',
			action : function(rowData, rows, rowIds, grid) {
				var searchCondition = "";
				for (var t in grid.options.searchParam) {
					if (t != "") {
						searchCondition += "&" + t + "="
								+ grid.options.searchParam[t];

					}
				}
				window.open(
						"?model=stock_inventoryinfo_inventoryinfo&action=exportExcelInTime&stockId="
								+ $("#stockId").val() + "&productId="
								+ $("#productId").val() + searchCondition, "",
						"width=200,height=200,top=200,left=200");
			}
		}]

	});
});