/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_datadict', {
		options : {
			hiddenId : 'dataCode',
			nameCol : 'dataName',
			valueCol : 'dataCode',
			isFocusoutCheck : false,
			gridOptions : {
				model : 'system_datadict_datadict',
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '名称',
					name : 'dataName',
					sortable : true,
					width : 300

				}, {
					display : '编码',
					name : 'dataCode',
					sortable : true,
					width : 200
				}],
				// 快速搜索
				searchitems : [{
					display : '名称',
					name : 'dataName'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);