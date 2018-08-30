/**
 * 质检检验方式下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitychecktype', {
		options : {
			hiddenId : 'id',
			nameCol : 'checkType',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_checktype',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'checkType',
					display : '检验方式',
					sortable : true

				} ],
				// 快速搜索
				searchitems : [ {
					display : '检验方式',
					name : 'checkType'
				} ],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);