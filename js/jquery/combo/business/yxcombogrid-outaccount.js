/**
 * 下拉外包结算表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outaccount', {
				options : {
					hiddenId : 'id',
					nameCol : 'formCode',
					gridOptions : {
						model : 'outsourcing_account_basic',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '单据编号',
									name : 'formCode',
									width:150
								},{
									display : '外包供应商',
									name : 'suppName',
									width:150
								},{
									display : '销售负责人',
									name : 'saleManangerName',
									width:150
								}],
						// 快速搜索
						searchitems : [{
									display : '单据编号',
									name : 'formCode'
								}],
						// 默认搜索字段名
						sortname : "formCode",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);