Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.ProjectinfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// 初始化组件
	initComponent : function() {
		this.init();
		Ext.business.combogrid.ProjectinfoComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// 定义数据结构
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'projectCode',
					header : '项目编号'
				},{
					name : 'projectName',
					header : '项目名称'
				}, {
					name : 'simpleName',
					header : '简称'
				}, {
					name : 'groupId',
					header : '项目组Id',
					hidden : true
				}, {
					name : 'groupSName',
					header : '项目组简称'
				}, {
					name : 'managerId',
					header : '负责人Id',
					hidden : true
				}, {
					name : 'managerName',
					header : '负责人名称'
				}, {
					name : 'projectType',
					header : '项目类别'
				}, {
					name : 'projectLevel',
					header : '优先级'
				}];
	},
	urlAction : 'index1.php?model=rdproject_project_rdproject&action=',// 提交的action前缀
	boName : '所属项目'

	});
Ext.reg('projectinfocombogrid', Ext.business.combogrid.ProjectinfoComboGrid);