var show_page = function(page) {
	$("#deductGrid").yxgrid("reload");
};
$(function() {
	$("#deductGrid").yxgrid({
		model : 'contract_deduct_deduct',
		title : '扣款申请',
		isAddAction : false,
		isViewAction : true,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			href : '?model=contract_contract_contract&action=init&perm=view&id=',
			hrefCol : 'contractId'
		}, {
			name : 'contractMoney',
			display : '合同金额',
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
			display : '扣款金额',
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
			display : '扣款原因',
			sortable : true,
			width : 280
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'state',
			display : '状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				}
			},
			width : 60
		}, {
			name : 'dispose',
			display : '处理方式',
			sortable : true,
			process : function(v) {
				if (v == 'deductMoney') {
					return "扣款";
				} else if (v == 'badMoney') {
					return "坏账";
				} else {
					return "";
				}
			},
			width : 60
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true
		}],
		// 扩展右键菜单

		menusEx : [{
			text : '扣款处理',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
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
            text : '状态',
            key : 'state',
            value : '0',
            data : [{
                text : '未处理',
                value : '0'
            }, {
                text : '已处理',
                value : '1'
            }]
        }, {
            text : '审批状态',
            key : 'ExaStatus',
            value : '完成',
            data : [{
                text : '未审批',
                value : '未审批'
            }, {
                text : '部门审批',
                value : '部门审批'
            }, {
                text : '打回',
                value : '打回'
            }, {
                text : '完成',
                value : '完成'
            }]
        }
        ],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}]
	});
});