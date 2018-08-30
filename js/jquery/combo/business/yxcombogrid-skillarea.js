/**
 * 下拉技能領域表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_skillarea', {
				options : {
					hiddenId : 'id',
					nameCol : 'skillarea',
					width : 450,
					gridOptions : {
						showcheckbox : false,
						isFocusoutCheck : false,
						model : 'outsourcing_basic_skillArea',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'skillarea',
			          					display : '技能领域',
			          					width:300,
			          					sortable : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '技能领域',
									name : 'skillarea'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);