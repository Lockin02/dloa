var show_page = function(page) {
	$("#encryptionGrid").yxgrid("reload");
};

$(function() {
	$("#encryptionGrid").yxgrid({
		model : 'stock_delivery_encryption',
		param : {
			'state' : '3'
		},
		title : '����ɼ���������',
		bodyAlign : 'center',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'sourceDocCode',
			display : 'Դ�����',
			sortable : true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=stock_delivery_encryption&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		},{
			name : 'sourceDocType',
			display : 'Դ������',
			sortable : true,
			width : 70
		},{
			name : 'headMan',
			display : '��ͬ������',
			sortable : true,
			width : 70
		},{
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 200
		},{
			name : 'issueName',
			display : '�´�������',
			sortable : true,
			width : 70
		},{
			name : 'issueDate',
			display : '�´�����',
			sortable : true,
			width : 80
		},{
			name : 'state',
			display : '״̬',
			sortable : true,
			width : 60,
			process : function (v) {
				if (v == 0) {
					return 'δ�´�';
				} else if (v == 1) {
					return '���´�';
				} else if (v == 2) {
					return '�ѽ���';
				} else {
					return '���';
				}
			}
		},{
			name : 'receiveDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'finshDate',
			display : '�������',
			sortable : true,
			width : 80
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250,
			align : 'left'
		}],

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=stock_delivery_encryption&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems : [{
			display : "Դ�����",
			name : 'sourceDocCode'
		},{
			display : "Դ������",
			name : 'sourceDocType'
		},{
			display : "������",
			name : 'headMan'
		},{
			display : "�ͻ�����",
			name : 'customerName'
		},{
			display : "�´�������",
			name : 'issueName'
		},{
			display : "�´�����",
			name : 'issueDate'
		},{
			display : "��������",
			name : 'receiveDate'
		},{
			display : "�������",
			name : 'finshDate'
		}]
	});
});