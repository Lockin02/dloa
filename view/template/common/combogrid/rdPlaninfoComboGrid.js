Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.PlaninfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// ��ʼ�����
	initComponent : function() {
		this.init();
		Ext.business.combogrid.PlaninfoComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// �������ݽṹ
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'planCode',
					header : '�ƻ����',
					hidden : true
				},{
					name : 'planName',
					header : '�ƻ�����'
				},{
					name : 'projectCode',
					header : '��Ŀ���'
				}, {
					name : 'projectName',
					header : '��Ŀ����'
				}, {
					name : 'managerName',
					header : '����������',
					hidden : true
				}, {
					name : 'managerId',
					header : '������Id',
					hidden : true
				}, {
					name : 'createId',
					header : '������Id',
					hidden : true
				}, {
					name : 'createName',
					header : '����������',
					hidden : true
				}, {
					name : 'projectId',
					header : '��ĿId',
					hidden : true
				}];
	},
	urlAction : 'index1.php?model=rdproject_plan_rdplan&action=',// �ύ��actionǰ׺
	boName : '�����ƻ�'

	});
Ext.reg('planinfocombogrid', Ext.business.combogrid.PlaninfoComboGrid);