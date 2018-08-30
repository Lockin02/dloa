var show_page = function() {
	$("#esmcloseruleGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseruleGrid").yxgrid({
		model: 'engineering_baseinfo_esmcloserule',
		title: '��Ŀ�رչ���',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'ruleName',
			display: '��Ŀ',
			sortable: true
		}, {
			name: 'content',
			display: '����',
			sortable: true,
			width: 300
		}, {
			name: 'confirmName',
			display: 'ȷ����',
			sortable: true
		}, {
			name: 'status',
			display: '״̬',
			sortable: true,
			process: function(v) {
				return v == "0" ? "�ر�" : "����";
			},
			width: 80
		}, {
			name: 'isCustom',
			display: '�Զ���',
			sortable: true,
			process: function(v) {
				return v == "1" ? "��" : "��";
			},
			width: 80
		}, {
			name: 'isNeed',
			display: '����',
			sortable: true,
			process: function(v) {
				return v == "1" ? "��" : "��";
			},
			width: 80
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		sortorder: 'ASC'
	});
});