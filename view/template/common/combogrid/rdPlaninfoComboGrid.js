Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.PlaninfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// 初始化组件
	initComponent : function() {
		this.init();
		Ext.business.combogrid.PlaninfoComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// 定义数据结构
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'planCode',
					header : '计划编号',
					hidden : true
				},{
					name : 'planName',
					header : '计划名称'
				},{
					name : 'projectCode',
					header : '项目编号'
				}, {
					name : 'projectName',
					header : '项目名称'
				}, {
					name : 'managerName',
					header : '负责人名称',
					hidden : true
				}, {
					name : 'managerId',
					header : '负责人Id',
					hidden : true
				}, {
					name : 'createId',
					header : '创建人Id',
					hidden : true
				}, {
					name : 'createName',
					header : '创建人名称',
					hidden : true
				}, {
					name : 'projectId',
					header : '项目Id',
					hidden : true
				}];
	},
	urlAction : 'index1.php?model=rdproject_plan_rdplan&action=',// 提交的action前缀
	boName : '所属计划'

	});
Ext.reg('planinfocombogrid', Ext.business.combogrid.PlaninfoComboGrid);