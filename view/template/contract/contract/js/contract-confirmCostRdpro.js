var show_page = function(page) {
	$("#RdprocostGrid").yxgrid("reload");
};
$(function() {
	$("#RdprocostGrid").yxgrid({
		model : 'contract_contract_contract',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
			'isTemp' : '0',
			'isRdproConfirm' : '1',
			'isSubApp' : '1'
//			,'engConfirm' : '0'
		},
		title : '合同信息',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
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
		},{
			text : '确认成本概算',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.rdproConfirm == '0' || row.ExaStatus == '未审批' || row.ExaStatus == '打回'|| row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=confirmCostView&id='
						+ row.id
						+ "&type=Rd"
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=800');
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {

				if (row.rdproConfirm == '0' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要打回?"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_common_relcontract&action=ajaxBack",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('打回成功！');
								$("#RdprocostGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '建立时间',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'isNeedStamp',
			display : '是否盖章',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined) {
					return "";
				} else {
					if (v == '0') {
						return "否";
					} else {
						return "是";
					}
				}
			}
		}, {
			name : 'contractType',
			display : '合同类型',
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
					// return "金额合计";
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
			name : 'contractMoney',
			display : '合同金额',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceMoney',
			display : '开票金额',
			sortable : true,
			width : 80,
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
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
							+ row.id
							+ '&tablename='
							+ row.contractType
							+ '&moneyType=deductMoney'
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
							+ "<font color = '#4169E1'>"
							+ moneyFormat2(v)
							+ "</font>" + '</a>';
				}
			}
		}, {
			name : 'badMoney',
			display : '坏账',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined) {
					return moneyFormat2(v);
				}
				if (v == "") {
					return "0.00";
				}
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceApplyMoney',
			display : '开票申请总金额',
			sortable : true,
			width : 80,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'surplusInvoiceMoney',
			display : '剩余开票金额',
			sortable : true,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(v);
				+"</font>"
			}
		}, {
			name : 'incomeMoney',
			display : '已收金额',
			width : 60,
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
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

		comboEx : [ {
			text : '确认状态',
			key : 'rdproConfirm',
			value : '0',
			data : [{
				text : '未确认',
				value : '0'
			}, {
				text : '已确认',
				value : '1'
			}]
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '业务编号',
			name : 'objCode'
		}],
		sortname : "createTime"
			// // 高级搜索
			// advSearchOptions : {
			// modelName : 'orderInfo',
			// // 选择字段后进行重置值操作
			// selectFn : function($valInput) {
			// $valInput.yxcombogrid_area("remove");
			// },
			// searchConfig : [{
			// name : '创建日期',
			// value : 'c.createTime',
			// changeFn : function($t, $valInput) {
			// $valInput.click(function() {
			// WdatePicker({
			// dateFmt : 'yyyy-MM-dd'
			// });
			// });
			// }
			// }, {
			// name : '归属区域',
			// value : 'c.areaPrincipal',
			// changeFn : function($t, $valInput, rowNum) {
			// if (!$("#areaPrincipalId" + rowNum)[0]) {
			// $hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			// + rowNum + "' value=''>");
			// $valInput.after($hiddenCmp);
			// }
			// $valInput.yxcombogrid_area({
			// hiddenId : 'areaPrincipalId' + rowNum,
			// height : 200,
			// width : 550,
			// gridOptions : {
			// showcheckbox : true
			// }
			// });
			// }
			// }]
			//		}
	});
});