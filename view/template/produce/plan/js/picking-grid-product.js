var show_page = function(page) {
	$("#pickingGrid").yxgrid("reload");
};

$(function() {
	$("#pickingGrid").yxgrid({
		model : 'produce_plan_pickingitem',
		action : 'pageJsonProduct',
		param : {
			planId : $("#planId").val(),
			groupBy : 'productId'
		},
		title : '��������������Ϣ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'productCode',
			display: '���ϱ���',
			sortable: true,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_pickingitem&action=toViewProduct&id="
					+ row.id + "&applyNum=" + row.applyNum + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'productName',
			display: '��������',
			width : 300,
			sortable: true
		},{
			name: 'productId',
			display: '����ID',
			sortable: true,
			hide: true
		},{
			name: 'pattern',
			display: '����ͺ�',
			sortable: true
		},{
			name: 'unitName',
			display: '��λ',
			sortable: true
		},{
			name: 'applyNum',
			display: '��������',
			sortable: true
		}],

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_pickingitem&action=toViewProduct&id=" + get[p.keyField] + "&applyNum=" + get['applyNum'] ,'1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		},{
			name: 'docDate',
			display: '��������'
		},{
			name: 'relDocCode',
			display: 'Դ�����'
		},{
			name: 'relDocName',
			display: 'Դ������'
		},{
			name: 'relDocType',
			display: 'Դ������'
		},{
			name: 'createName',
			display: '������'
		}]
	});
});