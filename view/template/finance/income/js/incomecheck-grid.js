var show_page = function(page) {
	$("#incomecheckGrid").yxgrid("reload");
};
$(function() {
	$("#incomecheckGrid").yxgrid({
		model: 'finance_income_incomecheck',
		title: '������¼��',
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'contractId',
			display: '��ͬid',
			sortable: true,
			hide: true
		},
		{
			name: 'contractCode',
			display: '��ͬ���',
			sortable: true,
			width : 120
		},
		{
			name: 'contractName',
			display: '��ͬ����',
			sortable: true,
			width : 120
		},
		{
			name: 'payConId',
			display: '��������id',
			sortable: true,
			hide: true
		},
		{
			name: 'payConName',
			display: '������������',
			sortable: true
		},
		{
			name: 'incomeType',
			display: 'Դ������',
			sortable: true,
			width : 80,
            process : function(v){
                switch(v){
                    case "0" : return '���';
                    case "1" : return '�ۿ�����';
                    case "2" : return '��Ʊ��¼';
                }
            }
		},
		{
			name: 'incomeId',
			display: 'Դ��id',
			sortable: true,
			hide: true
		},
		{
			name: 'incomeNo',
			display: 'Դ����',
			sortable: true,
			width : 120
		},
		{
			name: 'checkDate',
			display: '��������',
			sortable: true,
			width : 80
		},
		{
			name: 'checkMoney',
			display: '���κ������',
			sortable: true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		},
		{
			name: 'remark',
			display: '��ע',
			sortable: true
		},
		{
			name: 'isRed',
			display: '�Ƿ����',
			sortable: true,
			hide: true
		},
		{
			name: 'auditStatus',
			display: '���״̬',
			sortable: true,
			hide: true
		},
		{
			name: 'auditorId',
			display: '�����id',
			sortable: true,
			hide: true
		},
		{
			name: 'auditorName',
			display: '�����',
			sortable: true,
			width : 80,
			hide: true
		},
		{
			name: 'auditDate',
			display: '���ʱ��',
			sortable: true,
			width : 120,
			hide: true
		},
		{
			name: 'createId',
			display: '������ID',
			sortable: true,
			hide: true
		},
		{
			name: 'createName',
			display: '������',
			sortable: true,
			width : 80
		},
		{
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			width : 120
		},
		{
			name: 'updateId',
			display: '�޸���ID',
			sortable: true,
			hide: true
		},
		{
			name: 'updateName',
			display: '�޸���',
			sortable: true,
			width : 80
		},
		{
			name: 'updateTime',
			display: '�޸�ʱ��',
			sortable: true,
			width : 120
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		//��������
		comboEx : [{
			text : 'Դ������',
			key : 'incomeType',
			data : [{
				text : '���',
				value : '0'
			}, {
				text : '�ۿ�����',
				value : '1'
			}, {
				text : '��Ʊ��¼',
				value : '2'
			}]
		}],
		searchitems: [{
			display: "��ͬ���",
			name: 'contractCodeSearch'
		},{
			display: "��ͬ����",
			name: 'contractNameSearch'
		},{
			display: "�����",
			name: 'incomeNoSearch'
		},{
			display: "��ע��Ϣ",
			name: 'remarkSearch'
		}],
		sortname : 'updateTime'
	});
});