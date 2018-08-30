var show_page = function() {
	$("#stockbalanceGrid").yxgrid("reload");
};

$(function() {
	$("#stockbalanceGrid").yxgrid({
		model: 'finance_stockbalance_stockbalance',
		action: 'detailPageJson',
		title: '�����ϸ : ' + $('#stockName').val() + ' - �������� ��' + $('#productName').val(),
		param: {
			thisYear: $("#thisYear").val(),
			thisMonth: $("#thisMonth").val(),
			stockId: $("#stockId").val(),
			productId: $("#productId").val()
		},
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		isViewAction: false,
		showcheckbox: false,
		isRightMenu: false,
		isShowNum: false,
		usepager: false, // �Ƿ��ҳ
		//����Ϣ
		colModel: [{
			name: 'formDate',
			display: '����',
			sortable: true,
			width: 80
		}, {
			name: 'formType',
			display: '��������',
			sortable: true,
			process: function(v) {
				switch (v) {
					case 'balance' :
						return '�ڳ����';
						break;
					case 'costAdjust' :
						return '�ɱ�������';
						break;
					case 'adjustment' :
						return '���';
						break;
					case 'RKPURCHASE' :
						return '�⹺���';
						break;
					case 'RKPRODUCT' :
						return '��Ʒ���';
						break;
					case 'RKOTHER' :
						return '�������';
						break;
					case 'allo-in' :
						return '������-��';
						break;
					case 'CKSALES' :
						return '���۳���';
						break;
					case 'CKPICKING' :
						return '���ϳ���';
						break;
					case 'CKOTHER' :
						return '��������';
						break;
					case 'allo-out' :
						return '������-��';
						break;
					default :
						return v;
				}
			},
			width: 80
		}, {
			name: 'formNo',
			display: '���ݱ��',
			sortable: true,
			width: 130,
			process: function(v, row) {
				if (row.isRed == 0) {
					return v;
				} else {
					return "<span class='red'>" + v + "</span>";
				}
			}
		}, {
			name: 'inNumber',
			display: '�������',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v * 1 != 0) {
					return '<span style="font-weight:bold">' + v + '</span> ';
				}
			}
		}, {
			name: 'inAmount',
			display: '�����',
			sortable: true,
			process: function(v) {
				if (v * 1 != 0)
					return moneyFormat2(v);
			}
		}, {
			name: 'inPrice',
			display: '��ⵥ��',
			sortable: true,
			process: function(v, row) {
				if (row.inNumber * 1 != 0) {
					if (v * 1 == 0) {
						return "<span class='red'>" + v + "</span>";
					} else
						return moneyFormat2(v, 6);
				}
			}
		}, {
			name: 'outNumber',
			display: '��������',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v * 1 != 0) {
					return '<span style="font-weight:bold">' + v + '</span> ';
				}
			}
		}, {
			name: 'outAmount',
			display: '������',
			sortable: true,
			process: function(v) {
                if (v * 1 != 0)
                    return moneyFormat2(v);
			}
		}, {
			name: 'outPrice',
			display: '���ⵥ��',
			sortable: true,
			process: function(v, row) {
				if (row.outNumber * 1 != 0) {
					if (v * 1 == 0) {
						return "<span class='red'>" + v + "</span>";
					} else
						return moneyFormat2(v, 6);
				}
			}
		}, {
			name: 'balNumber',
			display: '�������',
			sortable: true,
			width: 80,
			process: function(v) {
				return v * 1 > 0 ? '<span style="font-weight:bold">' + v + '</span> ' : '<span style="font-weight:bold" class="red">' + v + '</span> ';
			}
		}, {
			name: 'balAmount',
			display: '�����',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			}
		}
		]
	});
});