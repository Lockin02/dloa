
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_milestone', {
		options : {
			hiddenId : 'id',
			nameCol : 'milestoneName',
			gridOptions : {
				showcheckbox : false,
				model : 'engineering_milestone_esmmilestone',
				// 列信息
				colModel : [{
						display : '里程碑名称',
						name : 'milestoneName'
					}, {
						display : '计划开始日期',
						name : 'planBeginDate'
					}, {
						display : '计划完成日期',
						name : 'planEndDate'
					}, {
						display : '是否使用',
						name : 'isUsing'
					}
				],
				// 快速搜索
				searchitems : [{
						display : '里程碑名称',
						name : 'milestoneName'
					}
				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);