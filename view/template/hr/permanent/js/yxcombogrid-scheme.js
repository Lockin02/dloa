/**
 * 下拉评估方案表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_scheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					gridOptions : {
						showcheckbox : true,
						model : 'hr_permanent_scheme',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'schemeTypeName',
			          					display : '考核对象',
			          					sortable : true
		                          },{
		            					name : 'schemeName',
		              					display : '考核方案名称',
		              					width:130,
		              					sortable : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '方案名称',
									name : 'schemeName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);