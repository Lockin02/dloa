Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TaskComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
			// ��ʼ�����
			initComponent : function() {
				this.init();
				Ext.business.combogrid.TaskComboGrid.superclass.initComponent
						.call(this);
			},
			init : function() {
				// �������ݽṹ
				this.structure = [{
							name : 'id',
							header : 'id',
							hidden : true
						}, {
							name : 'name',
							header : '��������'
						}, {
							name : 'chargeName',
							header : '������'
						}, {
							name : 'publishName',
							header : '������'
						},{
							name:'projectName',
							header:'��Ŀ����'
						}];
			},
			urlAction : 'index1.php?model=rdproject_task_rdtask&action=',// �ύ��actionǰ׺
			boName : '����'
		});
Ext.reg('taskcombogrid', Ext.business.combogrid.TaskComboGrid);
