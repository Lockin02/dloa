var show_page = function(page) {
	$("#costGrid").yxgrid("reload");
};
$(function() {
	$("#costGrid").yxgrid({
		model : 'contract_contract_cost',
		param : {
			'state' : '1'
		},
		title : '审核产品线成本概算',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单
		menusEx : [{
			text : '成本概算审核',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '1' && row.ExaState == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=confirmCostApp&id='
						+ row.id
						+ "&type=Ser"
						+ "&contractId="+row.contractId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=750&width=1000');
			}
		}
//		,{
//			text : '打回',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.state == '1' && row.ExaState == '0') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				if (window.confirm(("确定要打回?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_contract_cost&action=ajaxBack",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('打回成功！');
//								$("#costGrid").yxgrid("reload");
//							}
//						}
//					});
//				}
//			}
//		}
		],

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productLineName',
			display : '执行区域名称',
			sortable : true,
			width : 100
		}, {
			name : 'confirmName',
			display : '确认人',
			sortable : true,
			width : 80
		}, {
			name : 'confirmDate',
			display : '确认时间',
			sortable : true,
			width : 80
		}, {
			name : 'confirmMoney',
			display : '确认金额',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerType',
			display : '客户类型',
			sortable : true,
			process : function(v, row) {
                return getDataByCode(v);
			},
			width : 100
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}],

//		comboEx : [ {
//			text : '确认状态',
//			key : 'engConfirmStr',
//			value : '0',
//			data : [{
//				text : '未确认',
//				value : '0'
//			}, {
//				text : '已确认',
//				value : '1'
//			}]
//		}],
		comboEx : [{
					text : '审核状态',
					key : 'ExaState',
					value : '0',
					data : [{
								text : '未审核',
								value : '0'
							}, {
								text : '已审核',
								value : '1'
							}]
				}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		},{
			display : '合同名称',
			name : 'contractName'
		},{
			display : '客户名称',
			name : 'customerName'
		}],
        // 默认搜索顺序
        sortorder : "DSC",
        sortname : "confirmDate"
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