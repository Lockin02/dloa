/**
 * 下拉生产任务表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceTask', {
		options : {
			hiddenId : 'id',
			nameCol : 'documentCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_task_producetask',
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docType',
					display : '单类型',
					process : function(v, row) {
							if(v=="product"){
								return "生产任务";
							}else{
								return "委外加工任务";
							}
					},
					sortable : true
				}, {
					name : 'documentCode',
					display : '单据号',
					sortable : true
				}, {
					name : 'taskReqCode',
					display : '任务需求编号',
					sortable : true
				}, {
					name : 'pProduceNum',
					display : '预计生产数量',
					sortable : true
				},{
					name : 'hasCheckNum',
					display : '已质检数量',
					sortable : true,
					hide : true
				}, {
					name : 'taskReqId',
					display : '任务需求id',
					sortable : true,
					hide : true
				}],
				// 快速搜索
				searchitems : [{
					display : '单据号',
					name : 'documentCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);