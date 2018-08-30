var show_page = function (page) {
	$("#backGrid").yxsubgrid("reload");
};

$(function () {
	//���ݹ�������
//	var param = {};
//	var comboEx = [];
//	if ($("#finish").val() == 'yes') {
//		param = {
//			docStatusArr: '1,2'
//		};
//		var comboExArr = {
//				text: '����״̬',
//				key: 'docStatus',
//				data: [{
//					text: '��ȷ��',
//					value: '1'
//				}, {
//					text: '���',
//					value: '2'
//				}]
//			};
//		comboEx.push(comboExArr);
//	} else {
//		param = {
//			docStatusArr: '0'
//		};
//	}

	$("#backGrid").yxsubgrid({
		model: 'produce_plan_back',
//		param: param,
		title: '�����������뵥',
//		isAddAction: false,
//		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'pickingCode',
			display: '���ϵ����',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.pickingId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_back&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "δ�ύ";
					break;
				case '1':
					return "�������";
					break;
				case '2':
					return "�������";
					break;
				case '3':
					return "�����";
					break;
				}
			}
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 250
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_plan_backitem&action=pageJson',
			param: [{
				paramId: 'backId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '���ϱ���',
				sortable: true,
				width: 150
			}, {
				name: 'productName',
				display: '��������',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '����ͺ�',
				sortable: true
			}, {
				name: 'unitName',
				display: '��λ',
				sortable: true
			}, {
				name: 'applyNum',
				display: '��������',
				sortable: true
			}, {
				name: 'backNum',
				display: '�������',
				sortable: true
			}]
		},

		//�����Ҽ��˵�
		menusEx: [
//		{
//			text: "ȷ��",
//			icon: 'edit',
//			showMenuFn: function (row) {
//				if (row.docStatus == '0') {
//					return true;
//				}
//				return false;
//			},
//			action: function (row) {
//				if (row) {
//					showModalWin("?model=produce_plan_back&action=toConfirm&id=" + row.id, '1');
//				}
//			}
//		}, 
//		{
//			text : "���",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.docStatus == '0') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (window.confirm(("ȷ��Ҫ���?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=produce_plan_back&action=applyBack",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('��سɹ���');
//								show_page(1);
//							} else {
//								alert("���ʧ��! ");
//							}
//						}
//					});
//				}
//			}
//		},
		{
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=produce_plan_back&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		comboEx: [{
			text: '����״̬',
			key: 'docStatus',
			value : '0',
			data: [{
				text: 'δ�ύ',
				value: '0'
			}, {
				text: '�������',
				value: '1'
			}, {
				text: '�������',
				value: '2'
			}, {
				text: '�����',
				value: '3'
			}]
		}],
		toAddConfig: {
			toAddFn: function () {
				showModalWin("?model=produce_plan_back&action=toAdd", '1');
			}
		},
		toEditConfig: {
			showMenuFn: function(row) {
				return row.docStatus == "0";
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toEdit&id=" + get[p.keyField], '1');
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		}, {
			name: 'docDate',
			display: '��������'
		}, {
			name: 'pickingCode',
			display: '���ϵ����'
		}, {
			name: 'createName',
			display: '������'
		}]
	});
});