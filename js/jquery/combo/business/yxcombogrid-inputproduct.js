/**
 * 下拉评估方案表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inputproduct', {
				options : {
					hiddenId : 'id',
					nameCol : 'inputProductName',
					gridOptions : {
						showcheckbox : true,
						model : 'asset_purchase_apply_applyItem',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'productCategoryName',
			          					display : '物料类别',
			          					sortable : true
		                          },{
		            					name : 'inputProductName',
		              					display : '采购物料名称',
		              					width:130,
		              					sortable : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '采购物料名称',
									name : 'inputProductName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);