Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.ProjectinfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// ��ʼ�����
	initComponent : function() {
		this.init();
		Ext.business.combogrid.ProjectinfoComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// �������ݽṹ
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'projectCode',
					header : '��Ŀ���'
				},{
					name : 'projectName',
					header : '��Ŀ����'
				}, {
					name : 'simpleName',
					header : '���'
				}, {
					name : 'groupId',
					header : '��Ŀ��Id',
					hidden : true
				}, {
					name : 'groupSName',
					header : '��Ŀ����'
				}, {
					name : 'managerId',
					header : '������Id',
					hidden : true
				}, {
					name : 'managerName',
					header : '����������'
				}, {
					name : 'projectType',
					header : '��Ŀ���'
				}, {
					name : 'projectLevel',
					header : '���ȼ�'
				}];
	},
	urlAction : 'index1.php?model=rdproject_project_rdproject&action=',// �ύ��actionǰ׺
	boName : '������Ŀ'

	});
Ext.reg('projectinfocombogrid', Ext.business.combogrid.ProjectinfoComboGrid);