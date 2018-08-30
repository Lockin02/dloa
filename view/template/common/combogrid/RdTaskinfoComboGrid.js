Ext.namespace('Ext.business.combogrid');
Ext.business.combogrid.TaskinfoComboGrid = Ext.extend(Ext.ux.grid.MyGrid, {
	// ��ʼ�����
	initComponent : function() {
		this.init();
		Ext.business.combogrid.TaskinfoComboGrid.superclass.initComponent
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
					name : 'planName',
					header : '�����ƻ�'
				}, {
					name : 'projectName',
					header : '������Ŀ'
				}, {
					name : 'taskType',
					header : '��������'
				}, {
					name : 'chargeName',
					header : '������',
					hidden : true
				}, {
					name : 'createName',
					header : '������',
					hidden : true
				}, {
					name : 'updateTime',
					header : '����ʱ��',
					hidden : true
				}, {
					name : 'createTime',
					header : '����ʱ��',
					hidden : true
				}, {
					name : 'priority',
					header : '���ȼ�',
					hidden : true
				}, {
					name : 'projectCode',
					header : '��Ŀ���',
					hidden : true
				}, {
					name : 'projectId',
					header : '��ĿID',
					hidden : true
				}, {
					name : 'status',
					header : '����״̬',
					hidden : true
				}, {
					name : 'planEndDate',
					header : '�ƻ��������',
					hidden : true
				}, {
					name : 'planBeginDate',
					header : '�ƻ���ʼ����',
					hidden : true
				}, {
					name : 'appraiseWorkload',
					header : '���ƹ�����',
					hidden : true
				}, {
					name : 'effortRate',
					header : '�����',
					hidden : true
				}, {
					name : 'putWorkload',
					header : '��Ͷ�빤����',
					hidden : true
				}];
	},
	urlAction : 'index1.php?model=rdproject_task_rdtask&action=',// �ύ��actionǰ׺
	boName : 'ǰ������'

	});
Ext.reg('taskinfocombogrid', Ext.business.combogrid.TaskinfoComboGrid);