/**
 * 角色下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_socialplace', {
				options : {
					hiddenId : 'id',
					nameCol : 'socialCity',
       				title : '社保购买地',
					gridOptions : {
						showcheckbox : false,
						model : 'hr_basicinfo_socialplace',
						action : 'pageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'socialCity',
									width:200,
									display : '社保城市',
									sortable : true

								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC",
					searchitems : [{
								display : "城市",
								name : 'socialCity'
							}]
					}
				}
			});
})(jQuery);