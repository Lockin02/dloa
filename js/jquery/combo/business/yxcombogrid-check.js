/**
 * 盘点数据格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_check', {
		options : {
			hiddenId : 'id',

			nameCol : 'taskNo',

			gridOptions : {
				model : 'asset_checktask_check',

					// 表单
				  colModel : [
					            {
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            }, {
					                display : '任务单id',
					                name : 'taskId',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '任务编号',
					                name : 'taskNo',
					                sortable : true
					            },
					            {
					                display : '开始时间',
					                name : 'beginDate',
					                sortable : true
					            },
					            {
					                display : '结束时间',
					                name : 'endDate',
					                sortable : true
					            },
					            {
					                display : '盘点部门id',
					                name : 'deptId',
					                sortable : true,
					                hide:true
					            },
					            {
					                display : '盘点部门',
					                name : 'dept',
					                sortable : true
					            },{
								   display:'盘点人id',
								   name : 'manId',
					               sortable : true,
					                hide:true

								},{
								   display:'盘点人',
								   name : 'man',
					               sortable : true

								},
					            {
					                display : '备注',
					                name : 'remark',
					                sortable : true
					            }],




							// 快速搜索
							searchitems : [{
								display : '任务编号',
								name : 'taskNo'
							}, {
								display : '开始时间',
								name : 'beginDate'
							}, {
								display : '盘点部门',
								name : 'dept'
							}],
							sortorder : "ASC"


			}
		}
	});
})(jQuery);