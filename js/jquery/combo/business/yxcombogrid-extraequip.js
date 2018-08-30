/**
 * 常用设备下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_extraequip', {
		options : {
			hiddenId : 'id',
			nameCol : 'equipName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_extra_equipment',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : '设备名称',
					name : 'equipName',
					width : 180
				}, {
					display : '是否停产',
					name : 'isProduce',
					process : function(v, row) {
						if (v == "0") {
							return "在产";
						} else {
							return "停产";
						}
					},
					width : 80
				}, {
					display : '备注',
					name : 'remark'
				} ],
				// 快速搜索
				searchitems : [ {
					display : '设备名称',
					name : 'equipName'

				} ],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);