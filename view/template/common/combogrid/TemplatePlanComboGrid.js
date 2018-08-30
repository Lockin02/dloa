Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TemplatePlanComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// 初始化组件
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TemplatePlanComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// 定义数据结构
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'templateName',
					header : '模板名称'
				}, {
					name : 'description',
					header : '描述'
				}];

	},
	urlAction : 'index1.php?model=rdproject_template_rdtplan&action=',// 提交的action前缀
	boName : '进度计划模板'
		// defaultSortField : 'saleDate',
		// defaultSortdirection : 'DESC'
	});
Ext.reg('templatePlanComboGrid', Ext.business.combogrid.TemplatePlanComboGrid);