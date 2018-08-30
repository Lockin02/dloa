/**
 * 下拉技能領域表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsupplier', {
				options : {
					hiddenId : 'id',
					nameCol : 'suppName',
					width : 500,
					gridOptions : {
						showcheckbox : false,
						isFocusoutCheck : false,
						model : 'outsourcing_supplier_basicinfo',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'suppCode',
			          					display : '供应商编号',
			          					width:60,
			          					sortable : true
		                          },{
		            					name : 'suppName',
			          					display : '外包供应商',
			          					width:300,
			          					sortable : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '外包供应商',
									name : 'suppName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);