/**
 * 质检检验项目下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualityprogram', {
		options : {
			hiddenId : 'id',
			nameCol : 'programName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_quaprogram',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : 'id',
					name : 'id',
					hide : true
				}, {
					name : 'programName',
					display : '方案名称',
					sortable : true,
					width : 150
				}, {
					name : 'standardName',
					display : '质量标准',
					sortable : true,
					width : 150
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 200
				}],
				// 快速搜索
				searchitems : [ {
					display : '方案名称',
					name : 'programName'
				} ],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);