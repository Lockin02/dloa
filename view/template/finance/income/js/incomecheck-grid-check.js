var show_page = function() {
	$("#incomecheckGrid").yxgrid("reload");
};
$(function() {
	$("#incomecheckGrid").yxgrid({
		model: 'finance_income_incomecheck',
		title: '������¼��',
		showcheckbox : false,
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		param : {'contractIdArr' : $("#contractId").val(),'payConIdArr' : $("#payConId").val(),'incomeIdArr' : $("#incomeId").val()},
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
			width : 80,
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
			width : 130
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
			process : function(v,row){
				return row.isRed == "1" ? '<span class="red">-'+ moneyFormat2(v) +'</span>' : moneyFormat2(v);
			}
		},
		{
			name: 'remark',
			display: '��ע',
			sortable: true,
            width : 150
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
			name: 'createName',
			display: '������',
			sortable: true,
			width : 80
		},
		{
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			width : 120,
			hide: true
		},
		{
			name: 'updateName',
			display: '�޸���',
			sortable: true,
			width : 80,
            hide: true
		},
		{
			name: 'updateTime',
			display: '�޸�ʱ��',
			sortable: true,
			width : 130
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
			value : $('#incomeType').val(),
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