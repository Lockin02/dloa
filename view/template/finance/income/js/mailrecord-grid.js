var show_page = function(page) {
	$("#mailrecordGrid").yxgrid("reload");
};

$(function() {
	if($("#hasTimeTask").val() == 1){
		thisTitle = '�����ʼ���¼ [��ʱ����������]';
	}else{
		thisTitle = '�����ʼ���¼ [��ʱ����δ����,����ϵ����Ա����]';
	}
	$("#mailrecordGrid").yxgrid({
		model: 'finance_income_mailrecord',
		title: thisTitle,
		isAddAction : false,
//		isEditAction : false,
//		isDelAction : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'incomeId',
			display: '��������id',
			sortable: true,
			hide :true
		},
		{
			name: 'incomeCode',
			display: '�������ݱ��',
			sortable: true,
			width : 120
		},
		{
			name: 'sendIds',
			display: '�ռ���',
			sortable: true,
			hide :true
		},
		{
			name: 'sendNames',
			display: '�ռ�������',
			sortable: true,
			width : 120
		},
		{
			name: 'copyIds',
			display: '������',
			sortable: true,
			hide :true
		},
		{
			name: 'copyNames',
			display: '����������',
			sortable: true,
			width : 120
		},
		{
			name: 'secretIds',
			display: '������',
			sortable: true,
			hide :true
		},
		{
			name: 'secretNames',
			display: '����������',
			sortable: true,
			hide :true
		},
		{
			name: 'title',
			display: '�ʼ�����',
			sortable: true
		},
		{
			name: 'content',
			display: '�ʼ�����',
			sortable: true,
			hide :true
		},
		{
			name: 'times',
			display: '���ʹ���',
			sortable: true,
			width : 80
		},
		{
			name: 'status',
			display: '״̬',
			sortable: true,
			width : 80,
			process : function(v){
				if(v == 0){
					return '����';
				}else{
					return '�ر�';
				}
			}
		},
		{
			name: 'createOn',
			display: '����ʱ��',
			sortable: true
		},
		{
			name: 'lastMailTime',
			display: '�������ʱ��',
			sortable: true
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�ر�',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.status == '0' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ�ر�?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_income_mailrecord&action=changeStatus",
						data : {
							id : row.id,
							status : 1
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�رճɹ���');
								$("#mailrecordGrid").yxgrid("reload");
							}else{
								alert("�ر�ʧ��! ");
							}
						}
					});
				}
			}
		},{
			text : '����',
			icon : 'add',
			showMenuFn : function(row){
				if(row.status == '1' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_income_mailrecord&action=changeStatus",
						data : {
							id : row.id,
							status : 0
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#mailrecordGrid").yxgrid("reload");
							}else{
								alert("����ʧ��! ");
							}
						}
					});
				}
			}
		}],// ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
				value : 0,
				text  : '����'
			},{
				value : 1,
				text  : '�ر�'
			}]
		}],
		searchitems : [{
			display : '�������ݺ�',
			name : 'incomeCodeSearch'
		},{
			display : '�ռ���',
			name : 'sendNames'
		}]
	});
});