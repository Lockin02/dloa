var show_page = function() {
	$("#esmcloseGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseGrid").yxgrid({
		model: 'engineering_close_esmclose',
		title: '��Ŀ�ر�����',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true
		}, {
			name: 'applyId',
			display: '������id',
			sortable: true
		}, {
			name: 'applyName',
			display: '��������',
			sortable: true
		}, {
			name: 'content',
			display: '����',
			sortable: true
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true
		}, {
			name: 'ExaDT',
			display: '��������',
			sortable: true
		}, {
			name: 'status',
			display: '״̬',
			sortable: true
		}, {
			name: 'createId',
			display: '������Id',
			sortable: true
		}, {
			name: 'createName',
			display: '����������',
			sortable: true
		}, {
			name: 'createTime',
			display: '����ʱ��',
			sortable: true
		}, {
			name: 'updateId',
			display: '�޸���Id',
			sortable: true
		}, {
			name: 'updateName',
			display: '�޸�������',
			sortable: true
		}, {
			name: 'updateTime',
			display: '�޸�ʱ��',
			sortable: true
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		searchitems: [{
			display: "�����ֶ�",
			name: 'XXX'
		}]
	});
});