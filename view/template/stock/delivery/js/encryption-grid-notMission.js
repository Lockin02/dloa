var show_page = function(page) {
	$("#encryptionGrid").yxgrid("reload");
};

$(function() {
	$("#encryptionGrid").yxgrid({
		model : 'stock_delivery_encryption',
		param : {
			'stateArr' : '0,1,2'
		},
		title : 'δ��ɼ���������',
		bodyAlign : 'center',
		isAddAction : false,
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
				}
			}
		},{
			name : 'receiveDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
		// 	name : 'finshDate',
		// 	display : '�������',
		// 	sortable : true
		// },{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250,
			align : 'left'
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : "�´�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=stock_delivery_encryption&action=assignMission',
					data : {
						id : row.id
					},
					async: false,
					success : function(data) {
						if (data == 1) {
							alert("�´�ɹ�");
							show_page();
						} else {
							alert("�´�ʧ�ܣ����޷��´�ĵ��ݣ�");
						}
					}
				});
			}
		},{
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=stock_delivery_encryption&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page();
							}
						}
					});
				}
			}
		}],

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=stock_delivery_encryption&action=toEdit&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=stock_delivery_encryption&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},

		comboEx : [{
			text : '״̬',
			key : 'state',
			data : [{
				text : 'δ�´�',
				value : '0'
			},{
				text : '���´�',
				value : '1'
			},{
				text : '�ѽ���',
				value : '2'
			}]
		}],

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
		}]
	});
});