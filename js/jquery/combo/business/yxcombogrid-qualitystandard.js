/**
 * 质检质量标准下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitystandard', {
		options : {
			hiddenId : 'id',
			nameCol : 'standardName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_standard',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'standardName',
					display : '指标标准名称',
					sortable : true

				} ],
				// 快速搜索
				searchitems : [ {
					display : '指标标准名称',
					name : 'standardName'
				} ],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);