var show_page = function(page) {
	$("#contractChangeGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [];
	var param = {
		'states' : '0,1,2,3,4,5,6,7',
		'isTemp' : '0'
	}
	$("#contractChangeGrid").yxgrid({
		model : 'contract_contract_contract',
		title : '��ͬ��Ϣ',
		param : param,
		leftLayout : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		autoload : false,
		isOpButton : false,
		isEquSearch : false,

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�����ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.state == '2' )
						&& row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toChangePro&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
			}
		}],
//        lockCol:['flag'],//����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}
		, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'contractType',
			display : "��ͬ����",
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'signSubject',
			display : 'ǩԼ����',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == '') {
					return v;
//					return "���ϼ�";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'contractProvince',
			display : 'ʡ��',
			sortable : true,
			width : 60
		}, {
			name : 'contractCity',
			display : '����',
			sortable : true,
			width : 60
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 100
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			sortable : true,
			datacode : 'KHLX',
			width : 70
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'signStatus',
			display : 'ǩ��״̬',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '0') {
					return "δǩ��";
				} else if (v == '1') {
					return "��ǩ��";
				} else if (v == '2') {
					return "���δǩ��";
				}
			}
		}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 60
				}, {
					name : 'winRate',
					display : '��ͬӮ��',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'areaPrincipal',
					display : '��������',
					sortable : true
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '��ͬ״̬',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "δ�ύ";
						} else if (v == '1') {
							return "������";
						} else if (v == '2') {
							return "ִ����";
						} else if (v == '3') {
							return "�ѹر�";
						} else if (v == '4') {
							return "�����";
						} else if (v == '5') {
							return "�Ѻϲ�";
						} else if (v == '6') {
							return "�Ѳ��";
						} else if (v == '7') {
							return "�쳣�ر�";
						}
					},
					width : 60
				}, {
					name : 'objCode',
					display : 'ҵ����',
					sortable : true,
					width : 120
				}, {
					name : 'prinvipalDept',
					display : '�����˲���',
					sortable : true,
					hide : true
				}, {
					name : 'prinvipalDeptId',
					display : '�����˲���Id',
					sortable : true,
					hide : true
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : "��ͬ���",
					name : 'contractCodeAll'
				}, {
					display : "��ͬ����",
					name : 'contractNameAll'
				}, {
					display : "��Ŀ���",
					name : 'projectCodeAll'
				}],
		sortname : "createTime",
		buttonsEx : buttonsArr

	});
	// ��д����¼�
	var g = $("#contractChangeGrid").data("yxgrid");
	g.$clearBn.unbind();
	g.$clearBn.bind("click", function() {
				g.$inputText.val("");
				$(g.el).empty();
			});
	g.$searchBn.unbind();
	g.$searchBn.click(function() {
//			$.showDump(g);
				if (g.$inputText.val() == "") {
					$(g.el).empty();
				} else {
					g.doSearch();
				}
			});
});