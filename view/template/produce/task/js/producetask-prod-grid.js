var show_page = function(page) {
	$("#prodtaskGrid").yxgrid("reload");
};
$(function() {
	$("#prodtaskGrid").yxgrid({
		model : 'produce_task_producetask',
		action :��'getProdTaskPj',
		title : '��������',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docType',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'documentCode',
			display : '���ݺ�',
			sortable : true
		}, {
			name : 'taskReqCode',
			display : '����������',
			sortable : true
		}, {
			name : 'taskReqId',
			display : '��������id',
			sortable : true,
			hide : true
		}, {
			name : 'taskReqName',
			display : '������������',
			sortable : true
		}, {
			name : 'materialCode',
			display : '���ϱ���',
			sortable : true
		}, {
			name : 'materialName',
			display : '��������',
			sortable : true
		}, {
			name : 'materialId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'costObject',
			display : '�ɱ�����',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '��λ����',
			sortable : true
		}, {
			name : 'orgId',
			display : '��λid',
			sortable : true,
			hide : true
		}, {
			name : 'pProduceNum',
			display : '�ƻ���������',
			sortable : true
		}, {
			name : 'workShop',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'planStartDate',
			display : '�ƻ���������',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '�ƻ��깤����',
			sortable : true
		}, {
			name : 'docSource',
			display : '������Դ',
			sortable : true,
			hide : true
		}, {
			name : 'storageInPer',
			display : '�����ⳬ�ձ���',
			sortable : true,
			hide : true
		}, {
			name : 'storageNotPer',
			display : '������Ƿ�ձ���',
			sortable : true,
			hide : true
		}, {
			name : 'storageUp',
			display : '����������',
			sortable : true,
			hide : true
		}, {
			name : 'storageDown',
			display : '����������',
			sortable : true,
			hide : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'docReleaseDate',
			display : '�����´�����',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		}],
		isEditAction : false,
		isViewAction : true,
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 1000,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		toAddConfig : {
			action : 'toProdTaskAdd',
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 1000,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		}
	});
});