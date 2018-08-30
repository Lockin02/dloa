var show_page = function(page) {
	$("#leaseGrid").yxgrid("reload");
};
$(function() {
	$("#leaseGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'conPageJson',
		title : '租赁合同主表',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
			'isTemp' : '0',
			'contractType' : 'HTLX-ZLHT'
		},

		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'leaselist',
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			showMenuFn : function(row) {
				if (row ) {
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
			text : '变更查看',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.becomeNum != '0' && row.becomeNum != '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=showViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '完成合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row && (row.state == 2) && row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要把合同状态改为 “完成” 状态吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=completeOrder&id="
								+ row.id,
						success : function(msg) {
							$("#leaseGrid").yxgrid("reload");
						}
					});
				}
			}
		}
//		, {
//			text : '执行合同',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row && (row.state == 4)) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (window.confirm(("确定要把合同状态改为 “执行中” 状态吗？"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_contract_contract&action=exeOrder&id="
//								+ row.id,
//						success : function(msg) {
//							$("#leaseGrid").yxgrid("reload");
//						}
//					});
//				}
//			}
//		}
		],
         lockCol:['flag','exeStatus','status2'],//锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '沟通板',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined || row.id == '') {
				 return "合计";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
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
			process : function (v,row){
			   if (row.id == "allMoney" || row.id == undefined) {
					return "";
			   }else{
                   if(v == '0'){
			         return "否";
				   }else{
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
					return "金额合计";
				} else {
					return v;
				}
			},
			hide : true
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
		}
				// , {
				// name : 'contractTempMoney',
				// display : '预计合同金额',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.contractMoney == ''
				// || row.contractMoney == 0.00
				// || row.id == 'allMoney') {
				// return moneyFormat2(v);
				// } else {
				// return "<font color = '#B2AB9B'>" + moneyFormat2(v)
				// + "</font>";
				// }
				//
				// }
				// }
				, {
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
									+ moneyFormat2(v) + "</font>" + '</a>';
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
				}, {
					name : 'exeStatus',
					display : '执行进度',
					sortable : true,
					width : 50,
					process : function(v, row) {
							return "<p onclick='exeStatusView("+row.id+");' style='cursor:pointer;color:blue;' >"+v+"</p>";
						}
				},{
					name : 'status2',
					display : '状态',
					sortable : false,
					width : '20',
					align : 'center',
					// hide : aaa,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined || row.id == '') {
							 return "";
						 }
						if (row.state == '3' || row.state == '7') {
							return "<img src='images/icon/icon073.gif' />";
						} else if(row.ExaStatus == '打回'){
							return "<img src='images/icon/icon070.gif' />";
						} else {
						    return "<img src='images/icon/icon072.gif' />";
						}
					}
				}],

		comboEx : [{
			text : '合同状态',
			key : 'state',
			data : [{
				text : '保存',
				value : '0'
			},{
				text : '审批中',
				value : '1'
			}, {
				text : '执行中',
				value : '2'
			}, {
				text : '已完成',
				value : '4'
			}, {
				text : '已关闭',
				value : '3'
			}, {
				text : '已合并',
				value : '5'
			}, {
				text : '已拆分',
				value : '6'
			}, {
				text : '异常关闭',
				value : '7'
			}]
		}, {
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '变更审批中',
				value : '变更审批中'
			}, {
				text : '打回',
				value : '打回'
			}, {
				text : '完成',
				value : '完成'
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

	});
});

// 执行进度显示
function exeStatusView(cid){
//	showModalDialog(url, '',"dialogWidth:900px;dialogHeight:500px;");
    showModalWin("?model=contract_contract_contract&action=exeStatusView&cid=" + cid);
}