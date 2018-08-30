Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TaskComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
			// 初始化组件
			initComponent : function() {
				this.init();
				Ext.business.combogrid.TaskComboGrid.superclass.initComponent
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
							name : 'chargeName',
							header : '责任人'
						}, {
							name : 'publishName',
							header : '发布人'
						},{
							name:'projectName',
							header:'项目名称'
						}];
			},
			urlAction : 'index1.php?model=rdproject_task_rdtask&action=',// 提交的action前缀
			boName : '任务'
		});
Ext.reg('taskcombogrid', Ext.business.combogrid.TaskComboGrid);
