/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmactivity', {
		options : {
			hiddenId : 'id',
			nameCol : 'activityName',
			gridOptions : {
				model : 'engineering_activity_esmactivity',
				action : 'pageJsonOrg',
				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '任务名称',
							name : 'activityName',
							sortable : true
						}, {
							display : '预计开始',
							name : 'planBeginDate',
							sortable : true,
							width : 80
						}, {
							display : '预计结束',
							name : 'planEndDate',
							sortable : true,
							width : 80
						}, {
							display : '预计工期',
							name : 'days',
							sortable : true,
							width : 70
						}, {
							display : '工作量',
							name : 'workload',
							sortable : true,
							process : function(v,row){
								return v + " " + row.workloadUnitName;
							},
							width : 80
						}, {
							display : '任务进度',
							name : 'process',
							sortable : true,
							process : function(v,row){
								return v + " %";
							},
							width : 80
						}, {
							display : '实际开始',
							name : 'actBeginDate',
							sortable : true,
							width : 80,
							hide : true
						}, {
							display : '实际结束',
							name : 'actEndDate',
							sortable : true,
							width : 80,
							hide : true
						}, {
				            name : 'remark',
				            display : '备注',
				            sortable : true,
							width : 150,
							hide : true
				        }],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '任务名称',
					name : 'activityName'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '项目任务'
			}
		}
	});
})(jQuery);
