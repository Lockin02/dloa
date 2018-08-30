/**
 * 下拉出库单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockout', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						model : 'stock_outstock_stockout',
						// 列信息
						colModel : [
									{
										display : 'id',
										name : 'id',
										sortable : true,
										hide : true
									},
									{
										name : 'isRed',
										display : '红蓝色',
										sortable : true,
										align : 'center',
										width : '35',
										process : function(v, row) {
											if (row.isRed == '0') {
												return "<img src='images/icon/hblue.gif' />";
											} else {
												return "<img src='images/icon/hred.gif' />";
											}
										}
									}, {
										name : 'docCode',
										display : '单据编号',
										sortable : true

									}, {
										name : 'docType',
										display : '出库单类型',
										sortable : true,
										hide : true
									}, {
										name : 'relDocId',
										display : '源单id',
										sortable : true,
										hide : true
									}, {
										name : 'relDocType',
										display : '源单类型',
										sortable : true,
										datacode : 'RKDYDLX2'
									}, {
										name : 'relDocCode',
										display : '源单编号',
										sortable : true
									}, {
										name : 'relDocName',
										display : '源单名称',
										sortable : true,
										hide : true

									}, {
										name : 'stockId',
										display : '发料仓库id',
										sortable : true,
										hide : true
									}, {
										name : 'stockCode',
										display : '发料仓库代码',
										sortable : true
									}, {
										name : 'stockName',
										display : '发料仓库名称',
										sortable : true
									}, {
										name : 'docStatus',
										display : '单据状态',
										sortable : true,
										process : function(v, row) {
											if (row.docStatus == 'WSH') {
												return "未审核";
											} else {
												return "已审核";
											}
										}
									}],
						// 快速搜索
						searchitems : [{
									display : '单据编号',
									name : 'docCode'
								}],
						// 默认搜索字段名
						sortname : "docCode",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);