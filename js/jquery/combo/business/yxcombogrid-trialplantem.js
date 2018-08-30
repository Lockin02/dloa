/**
 * 培训计划表格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_trialplantem', {
		options : {
			hiddenId : 'id',
			// param : { 'customerId' :$('customerId').val() },
			nameCol : 'planName',
			title : '员工培训计划模板',
			gridOptions : {
				model : 'hr_baseinfo_trialplantem&action=page',

				// 表单
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'planName',
					display : '计划名称',
					sortable : true,
					width:250
				},{
					name : 'description',
					display : '描述',
					sortable : true,
					width:250
				}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : "计划名称",
					name : 'planNameSearch'
				}],
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);