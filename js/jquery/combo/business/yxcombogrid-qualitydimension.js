/**
 * 质检检验项目下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitydimension', {
		options : {
			hiddenId : 'id',
			nameCol : 'dimName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_dimension',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'dimName',
					display : '检验项目名称',
					sortable : true

				} ],
				// 快速搜索
				searchitems : [ {
					display : '检验项目名称',
					name : 'dimName'
				} ],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);