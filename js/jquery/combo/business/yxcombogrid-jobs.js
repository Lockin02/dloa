/**
 * 角色下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_jobs', {
				options : {
					hiddenId : 'jobId',
					nameCol : 'name',
					gridOptions : {
						showcheckbox : false,
						model : 'deptuser_jobs_jobs',
						action : 'pageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									width:200,
									display : '角色名称',
									sortable : true

								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);