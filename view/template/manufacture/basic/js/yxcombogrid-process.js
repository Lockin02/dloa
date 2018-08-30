/**
 * 下拉工序-项目名称组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_process', {
		options : {
			hiddenId : 'processName',
			nameCol : 'processName',
			width : 400,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_processequ',
				param : {
					'groupBy' : "processName"
				},
				//列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'processName',
					display : '项目名称',
					width : 350,
					sortable : true
				}],

				// 快速搜索
				searchitems : [{
					display : '项目名称',
					name : 'processName'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);