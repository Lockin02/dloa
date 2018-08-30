/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_project', {
				options : {
					hiddenId : 'projectId',
					nameCol : 'projectName',
					gridOptions : {
						showcheckbox : true,
						model : 'engineering_project_esmproject',
						action : 'pageJsonMyPj',
						// 列信息
						colModel : [{
									display : '项目名称',
									name : 'projectName',
									width : 180
								}, {
									display : '项目编号',
									name : 'projectCode',
									width : 130
								}, {
									display : '所属办事处',
									name : 'officeName'
								}, {
									display : '项目经理',
									name : 'managerName'
								}, {
									display : '(预计结束日期)',
									name : 'planDateClose',
									hide: true
								}],
						// 快速搜索
						searchitems : [{
									display : '项目名称',
									name : 'projectName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);