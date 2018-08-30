var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [], mergeArr = {
		text : "财务金额导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=contract_contract_contract&action=FinancialImportexcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=contract_contract_contract&action=getLimits',
		data : {
			'limitName' : '财务金额导入'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(mergeArr);
			}
		}
	});
	var param = {
		'states' : '0,1,2,3,4,5,6,7',
		'isTemp' : '0'
	}
	if ($("#lastAdd").val()) {
		param.lastAdd = $("#lastAdd").val();
	}
	if ($("#lastChange").val()) {
		param.lastChange = $("#lastChange").val();
	}
	$("#contractGrid").yxgrid({
		model : 'contract_contract_contract',
		//		action : 'conPageJson',
		title : '合同主表',
		param : param,
		leftLayout : true,
		title : '合同信息',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractIncomeTypeInfo',
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
			text : '确认收入核算方式',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=incomeAcc&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
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
			name : 'incomeAccounting',
			display : '收入核算方式',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "";
				} else if (v == 'AFPQRSR') {
					return "按发票确认收入";
				} else if (v == 'AJDQRSR') {
					return "按进度确认收入";
				} else if (v == 'HEFS') {
					return "混合方式";
				}
			}
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
			},
			hide : true
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
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=init&perm=view&id='
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
		comboEx : [{
			text : '类型',
			key : 'contractType',
			data : [{
				text : '销售合同',
				value : 'HTLX-XSHT'
			}, {
				text : '服务合同',
				value : 'HTLX-FWHT'
			}, {
				text : '租赁合同',
				value : 'HTLX-ZLHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '合同状态',
			key : 'state',
			data : [{
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
			}
					//			, {
					//				text : '已合并',
					//				value : '5'
					//			}, {
					//				text : '已拆分',
					//				value : '6'
					//			}
					, {
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
		}, {
			text : '签约主体',
			key : 'signSubject',
			datacode : 'QYZT'
		}, {
			text : '收入核算方式',
			key : 'incomeAccounting',
			data : [{
				text : '未确认',
				value : ''
			}, {
				text : '按发票确认收入',
				value : 'AFPQRSR'
			}, {
				text : '按进度确认收入',
				value : 'AJDQRSR'
			}, {
				text : '混合方式',
				value : 'HEFS'
			}]
		}],
		// 主从表格设置
		// subGridOptions : {
		// url : '?model=contract_contract_product&action=pageJson',// 获取从表数据url
		// // 传递到后台的参数设置数组
		// param : [{
		// paramId : 'contractId',// 传递给后台的参数名称
		// colId : 'id'// 获取主表行数据的列名称
		//
		// }],
		// // param:{
		// // 'contractId' : $("#contractId").val(),
		// // 'dir' : 'ASC',
		// // 'prinvipalId':$("#prinvipalId").val(),
		// // 'createId':$("#createId").val(),
		// // 'areaPrincipalId':$("#areaPrincipalId").val(),
		// // // 'isTemp' : '0',
		// // 'isDel' : '0'
		// // },
		// // 显示的列
		// colModel : [{
		// name : 'conProductName',
		// width : 200,
		// display : '产品名称'
		// }, {
		// name : 'conProductDes',
		// display : '产品描述',
		// width : 80
		// }, {
		// name : 'number',
		// display : '数量',
		// width : 80
		// }, {
		// name : 'price',
		// display : '单价',
		// width : 80
		// }, {
		// name : 'money',
		// display : '金额',
		// width : 80
		// }, {
		// name : 'licenseButton',
		// display : '加密配置',
		// process : function(v, row) {
		// if (row.license != "") {
		// return "<a href='#' onclick='showLicense(\'"
		// + row.license + "\')'>查看</a>";
		// } else {
		// return "";
		// }
		// }
		// }, {
		// name : 'deployButton',
		// display : '产品配置',
		// process : function(v, row) {
		// if (row.deploy != "") {
		// return "<a href='#' onclick='showGoods(\""
		// + row.deploy + "\",\""
		// + row.conProductName + "\")'>查看</a>";
		// } else {
		// return "";
		// }
		// }
		// }]
		// },
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
		sortname : "createTime",
		buttonsEx : buttonsArr,

		// 高级搜索
		advSearchOptions : {
			modelName : 'contractIncomeAcc',
			// 选择字段后进行重置值操作
			selectFn : function($valInput) {
				$valInput.yxcombogrid_area("remove");
			},
			searchConfig : [{
				name : '创建日期',
				value : 'c.createTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			}, {
				name : '合同类型',
				value : 'c.contractType',
				type : 'select',
				datacode : 'HTLX'
			}, {
				name : '客户类型',
				value : 'c.customerType',
				type : 'select',
				datacode : 'KHLX'
			}, {
				name : '剩余开票金额',
				value : 'c.surplusInvoiceMoney'
			}, {
				name : '签约合同应收账款余额',
				value : 'c.surOrderMoney'
			}, {
				name : '财务应收账款余额',
				value : 'c.surincomeMoney'
			}, {
				name : '区域负责人',
				value : 'c.areaPrincipal',
				changeFn : function($t, $valInput, rowNum) {
					$valInput.yxcombogrid_area({
						hiddenId : 'areaPrincipalId' + rowNum,
						nameCol : 'areaPrincipal' + rowNum,
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : '合同负责人',
				value : 'c.prinvipalName',
				changeFn : function($t, $valInput, rowNum) {

					$valInput.yxselect_user({
						hiddenId : 'prinvipalId' + rowNum,
						nameCol : 'prinvipalName' + rowNum,
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}]
		}
	});
});