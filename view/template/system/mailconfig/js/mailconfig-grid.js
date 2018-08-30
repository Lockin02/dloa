var show_page = function(page) {
	$("#mailconfigGrid").yxgrid("reload");
};
$(function() {
	$("#mailconfigGrid").yxgrid({
		model: 'system_mailconfig_mailconfig',
		title: 'ͨ���ʼ�����',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'objCode',
			display: 'ҵ�����',
			sortable: true,
			width : 120
		},
		{
			name: 'objName',
			display: 'ҵ������',
			sortable: true,
			width : 120
		},
		{
			name: 'description',
			display: '������Ϣ',
			sortable: true,
			hide: true
		},
		{
			name: 'mailTitle',
			display: '�ʼ�����',
			sortable: true,
			width : 120
		},
		{
			name: 'mailContent',
			display: '�ʼ�����',
			sortable: true,
			hide: true
		},
		{
			name: 'defaultUserName',
			display: 'Ĭ�Ϸ�����',
			sortable: true,
			width : 120
		},
		{
			name: 'defaultUserId',
			display: 'Ĭ�Ϸ�����id',
			sortable: true,
			hide: true
		},
		{
			name: 'ccUserName',
			display: 'Ĭ�ϳ�����',
			sortable: true,
			width : 120
		},
		{
			name: 'ccUserId',
			display: 'Ĭ�ϳ�����id',
			sortable: true,
			hide: true
		},
		{
			name: 'bccUserName',
			display: 'Ĭ��������',
			sortable: true,
			width : 120
		},
		{
			name: 'bccUserId',
			display: 'Ĭ��������id',
			sortable: true,
			hide: true
		},
		{
			name: 'isMain',
			display: '��������',
			sortable: true,
			width : 70,
			process : function(v){
				if(v == "1"){
					return '��';
				}else{
					return '��';
				}
			}
		},
		{
			name: 'isItem',
			display: '���شӱ�',
			sortable: true,
			width : 70,
			process : function(v){
				if(v == "1"){
					return '��';
				}else{
					return '��';
				}
			}
		}],
		toAddConfig: {
			action: 'toAdd',
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig: {
			action: 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig: {
			action: 'toView',
			formHeight : 500,
			formWidth : 900
		},
		searchitems: [{
			display: "ҵ�����",
			name: 'objCodeSearch'
		},{
			display: "ҵ������",
			name: 'objNameSearch'
		},{
			display: "�ʼ�����",
			name: 'mailTitleSearch'
		},{
			display: "�ʼ�����",
			name: 'mailContentSearch'
		}]
	});
});