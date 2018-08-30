/**
 * 模板信息下拉表格组件
 */
/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_protemplate', {
		options : {
			hiddenId : 'id',
			nameCol : 'templateName',
			valueCol : 'templateName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_template_protemplate',
				// 列信息
				colModel : [{
						display : 'ID',
						name : 'id',
					},{
						display : '模板名称',
						name : 'templateName',
					}, {
						display : '备注',
						name : 'remark',
					}
				],
				// 快速搜索
				searchitems : [{
						display : '模板名称',
						name : 'templateName'
					}
				],
				// 默认搜索字段名
				sortname : "templateName",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);