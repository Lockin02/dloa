var show_page = function(page) {
	$("#pickingoutGrid").yxgrid("reload");
};

$(function() {
	$("#pickingoutGrid").yxgrid({
		model : 'produce_plan_pickingout',
		title : '�����ֳ����¼',
		param : {
			pickingId : $('#pickId').val()
		},
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'pickingCode',
			display : '���ϵ��ݱ��',
			sortable : true,
			width : 120
		},{
			name : 'productCode',
			display : '���ϱ���',
			sortable : true
		},{
			name : 'productName',
			display : '��������',
			sortable : true,
			width : 250
		},{
			name : 'pattern',
			display : '����ͺ�',
			sortable : true
		},{
			name : 'unitName',
			display : '��λ',
			sortable : true
		},{
			name : 'applyMan',
			display : '������',
			sortable : true
		},{
			name : 'applyDate',
			display : '��������',
			sortable : true
		},{
			name : 'applyNum',
			display : '��������',
			sortable : true
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250
		}],

		searchitems : [{
			display : "���ϵ��ݱ��",
			name : 'pickingCode'
		},{
			display : "���ϱ���",
			name : 'productCode'
		},{
			display : "��������",
			name : 'productName'
		},{
			display : "������",
			name : 'applyMan'
		},{
			display : "��������",
			name : 'applyDate'
		}]
	});
});