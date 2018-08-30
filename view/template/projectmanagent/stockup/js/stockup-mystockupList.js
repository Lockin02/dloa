var show_page = function(page) {
	$("#mystockupGrid").yxsubgrid("reload");
};
$(function() {
	$("#mystockupGrid").yxsubgrid({
		model : 'projectmanagent_stockup_stockup',
		title : '�ҵ����۱�������',
		param : {'createId' : $("#userId").val()},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'stockupCode',
			display : '���������',
			sortable : true,
			width : 200
		}, {
			name : 'type',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'sourceType',
			display : 'Դ������',
			sortable : true,
			hide : true
		}, {
			name : 'sourceId',
			display : 'Դ��ID',
			sortable : true,
			hide : true
		}, {
			name : 'state',
			display : '״̬',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 300
		}],
        // ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_stockup_equ&action=PageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'stockupId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '��Ʒ���'
					},{
						name : 'productName',
						width : 200,
						display : '��Ʒ����'
					}, {
					    name : 'number',
					    display : '��������',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "�����ֶ�",
			name : 'XXX'
		}
	});
});