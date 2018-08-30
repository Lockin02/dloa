Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TaskinfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// 初始化组件
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TaskinfoComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// 定义数据结构
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'name',
					header : '任务名称'
				}, {
					name : 'planName',
					header : '所属计划'
				}, {
					name : 'projectName',
					header : '所属项目'
				}, {
					name : 'taskType',
					header : '任务类型'
				}, {
					name : 'chargeName',
					header : '负责人',
					hidden : true
				}, {
					name : 'createName',
					header : '创建人',
					hidden : true
				}, {
					name : 'updateTime',
					header : '更新时间',
					hidden : true
				}, {
					name : 'createTime',
					header : '创建时间',
					hidden : true
				}, {
					name : 'priority',
					header : '优先级',
					hidden : true
				}, {
					name : 'projectCode',
					header : '项目编号',
					hidden : true
				}, {
					name : 'projectId',
					header : '项目ID',
					hidden : true
				}, {
					name : 'status',
					header : '任务状态',
					hidden : true
				}, {
					name : 'planEndDate',
					header : '计划完成日期',
					hidden : true
				}, {
					name : 'planBeginDate',
					header : '计划开始日期',
					hidden : true
				}, {
					name : 'appraiseWorkload',
					header : '估计工作量',
					hidden : true
				}, {
					name : 'effortRate',
					header : '完成率',
					hidden : true
				}, {
					name : 'putWorkload',
					header : '已投入工作量',
					hidden : true
				}];
	},
	urlAction : 'index1.php?model=rdproject_task_rdtask&action=',// 提交的action前缀
	boName : '前置任务'

	});
Ext.reg('taskinfocombogrid', Ext.business.combogrid.TaskinfoComboGrid);