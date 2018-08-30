/**
 * 人资模板
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_formwork', {
		options : {
			isDown : true,
			hiddenId : 'id',
			gridOptions : {
				model : 'hr_formwork_formwork',
                param : {'isUse':"0"},
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'formworkName',
					display : '模板名称',
					sortable : true
				}, {
					name : 'isUse',
					display : '是否启用',
					sortable : true,
					process : function(v, row) {
						if (v == '0') {
							return "启用";
						} else if (v == '1') {
							return "停止";
						}
					}
				}],
				// // 快速搜索
				// searchitems : [{
				// display : '合同编号',
				// name : 'orderCodeOrTempSearch'
				// }, {
				// display : '合同名称',
				// name : 'orderName',
				// isdefault : true
				// }],

				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);