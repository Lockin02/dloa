/**
 * 盘点任务组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_checktask', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_checktask_checktask',

					// 表单
				 colModel : [
					            {
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '任务编号',
					                name : 'billNo',
					                sortable : true
					            },
					            {
					                display : '盘点时间',
					                name : 'checkDate',
					                sortable : true
					            },
					            {
					                display : '预计盘点结束时间',
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
					                name : 'deptName',
					                sortable : true
					            },{
								   display:'发起人id',
								   name : 'initiatorId',
					               sortable : true,
					                hide:true

								},{
								   display:'发起人',
								   name : 'initiator',
					               sortable : true

								},{
								    display:'参与人id',
									name : 'participantId',
					                sortable : true,
					                hide:true

								},{
								    display:'参与人',
									name : 'participant',
					                sortable : true

								},
					            {
					                display : '任务说明',
					                name : 'remark',
					                sortable : true
					            }],

				// 快速搜索
							searchitems : [{
								display : '任务编号',
								name : 'billNo'
							}, {
								display : '盘点时间',
								name : 'checkDate'
							}, {
								display : '盘点部门',
								name : 'deptName'
							}],
							sortorder : "ASC"
			}
		}
	});
})(jQuery);