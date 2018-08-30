var show_page = function(page) {

	$(".subitemGrid").yxgrid("reload");
};
$(function() {
			$(".subitemGrid").yxgrid({
						model : 'stock_inventoryinfo_inventoryinfo',
						action : 'pageSubItemJson',
						title : "库存状态信息",
						isToolBar : true,
						isAddAction : false,
						isViewAction : false,
						isEditAction : false,
						isRightMenu : false,
						isDelAction : false,
						isRightMenu : false,
						// 列信息
						colModel : [{
									display : '物料编号',
									name : 'productCode',
									sortable : true,
									align : 'center'
								}, {
									display : '物料名称',
									name : 'productName',
									sortable : true,
									width : 250
								}, {
									name : 'initialNum',
									display : '期初库存',
									sortable : true,
									width : 80

								}, {
									name : 'actNum',
									display : '即时库存',
									sortable : true,
									width : 80
								}, {
									name : 'exeNum',
									display : '可执行库存',
									sortable : true,
									width : 80
								}, {
									name : 'assigedNum',
									display : '已分配量',
									sortable : true,
									width : 80
								}, {
									name : 'lockedNum',
									display : '锁存量',
									sortable : true,
									width : 80
								}, {
									name : 'planInstockNum',
									display : '预计入库量',
									sortable : true,
									width : 80
								}],
						showcheckbox : false, // 显示checkbox
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