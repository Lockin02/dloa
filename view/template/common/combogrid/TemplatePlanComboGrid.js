Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TemplatePlanComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// ��ʼ�����
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TemplatePlanComboGrid.superclass.initComponent
				.call(this);
	},
	init : function() {
		// �������ݽṹ
		this.structure = [{
					name : 'id',
					header : 'id',
					hidden : true
				}, {
					name : 'templateName',
					header : 'ģ������'
				}, {
					name : 'description',
					header : '����'
				}];

	},
	urlAction : 'index1.php?model=rdproject_template_rdtplan&action=',// �ύ��actionǰ׺
	boName : '���ȼƻ�ģ��'
		// defaultSortField : 'saleDate',
		// defaultSortdirection : 'DESC'
	});
Ext.reg('templatePlanComboGrid', Ext.business.combogrid.TemplatePlanComboGrid);