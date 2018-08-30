/**
 * 验收设置下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_checkset', {
		isDown : true,
		options : {
			hiddenId : 'id',
			nameCol : 'clause',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_checksetting_checksetting',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
					name : 'clause',
					display : '验收条款',
					sortable : true,
					width : 100
				}, {
					name : 'dateName',
					display : '验收时间节点',
					sortable : true
				}, {
					name : 'dateCode',
					display : '验收时间节点编码',
					sortable : true,
					hide : true
				}, {
					name : 'days',
					display : '缓冲天数',
					sortable : true
				}, {
					name : 'description',
					display : '说明',
					sortable : true,
					width : 300
				} ]
			}
		}
	});
})(jQuery);