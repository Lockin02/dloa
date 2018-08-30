var show_page = function(page) {
	$("#deductGrid").yxgrid("reload");
};
$(function() {
	$("#deductGrid").yxgrid({
		model : 'contract_deduct_deduct',
		title : '�ۿ�����',
		isAddAction : false,
		isViewAction : true,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			href : '?model=contract_contract_contract&action=init&perm=view&id=',
			hrefCol : 'contractId'
		}, {
			name : 'contractMoney',
			display : '��ͬ���',
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'deductMoney',
			display : '�ۿ���',
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'deductReason',
			display : '�ۿ�ԭ��',
			sortable : true,
			width : 280
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'state',
			display : '״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				}
			},
			width : 60
		}, {
			name : 'dispose',
			display : '����ʽ',
			sortable : true,
			process : function(v) {
				if (v == 'deductMoney') {
					return "�ۿ�";
				} else if (v == 'badMoney') {
					return "����";
				} else {
					return "";
				}
			},
			width : 60
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '����������',
			sortable : true
		}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�ۿ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_deduct_deduct&action=deductDispose&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
        comboEx : [{
            text : '״̬',
            key : 'state',
            value : '0',
            data : [{
                text : 'δ����',
                value : '0'
            }, {
                text : '�Ѵ���',
                value : '1'
            }]
        }, {
            text : '����״̬',
            key : 'ExaStatus',
            value : '���',
            data : [{
                text : 'δ����',
                value : 'δ����'
            }, {
                text : '��������',
                value : '��������'
            }, {
                text : '���',
                value : '���'
            }, {
                text : '���',
                value : '���'
            }]
        }
        ],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}]
	});
});