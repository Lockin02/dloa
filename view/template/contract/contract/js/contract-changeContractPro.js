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
		title : '合同信息',
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

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
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
			text : '变更合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.state == '2' )
						&& row.ExaStatus == '完成') {
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
//        lockCol:['flag'],//锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}
		, {
			name : 'createTime',
			display : '建立时间',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'contractType',
			display : "合同类型",
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'signSubject',
			display : '签约主体',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '合同属性',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == '') {
					return v;
//					return "金额合计";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
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
			display : '省份',
			sortable : true,
			width : 60
		}, {
			name : 'contractCity',
			display : '城市',
			sortable : true,
			width : 60
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 100
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'customerType',
			display : '客户类型',
			sortable : true,
			datacode : 'KHLX',
			width : 70
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'signStatus',
			display : '签收状态',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '0') {
					return "未签收";
				} else if (v == '1') {
					return "已签收";
				} else if (v == '2') {
					return "变更未签收";
				}
			}
		}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'winRate',
					display : '合同赢率',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 60
				}, {
					name : 'areaPrincipal',
					display : '区域负责人',
					sortable : true
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '合同状态',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "未提交";
						} else if (v == '1') {
							return "审批中";
						} else if (v == '2') {
							return "执行中";
						} else if (v == '3') {
							return "已关闭";
						} else if (v == '4') {
							return "已完成";
						} else if (v == '5') {
							return "已合并";
						} else if (v == '6') {
							return "已拆分";
						} else if (v == '7') {
							return "异常关闭";
						}
					},
					width : 60
				}, {
					name : 'objCode',
					display : '业务编号',
					sortable : true,
					width : 120
				}, {
					name : 'prinvipalDept',
					display : '负责人部门',
					sortable : true,
					hide : true
				}, {
					name : 'prinvipalDeptId',
					display : '负责人部门Id',
					sortable : true,
					hide : true
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : "合同编号",
					name : 'contractCodeAll'
				}, {
					display : "合同名称",
					name : 'contractNameAll'
				}, {
					display : "项目编号",
					name : 'projectCodeAll'
				}],
		sortname : "createTime",
		buttonsEx : buttonsArr

	});
	// 重写清空事件
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