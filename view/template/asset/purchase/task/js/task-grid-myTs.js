var show_page = function(page) {
	$("#taskGrid").yxsubgrid("reload");
};
$(function() {
	$("#taskGrid").yxsubgrid({
		model : 'asset_purchase_task_task',
		title : '我的资产采购任务',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		param : {
			"purcherId" : $("#purcherId").val()
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 120
		}, {
			name : 'state',
			display : '单据状态',
			sortable : true,
			width : 90
		}, {
			name : 'sendTime',
			display : '下达日期',
			sortable : true
		}, {
			name : 'sendName',
			display : '任务下达人',
			sortable : true
		}, {
			name : 'purcherName',
			display : '采购员',
			sortable : true,
			hide : true
		}, {
			name : 'acceptDate',
			display : '接收日期',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_task_taskItem&action=pageJson',
			param : [{
				paramId : 'parentId',
				colId : 'id'
			}],
			colModel : [{
				display : '物料名称',
				name : 'productName',
				tclass : 'txtshort'
			}, {
				display : '规格',
				name : 'pattem',
				tclass : 'txtshort'
			}, {
				display : '采购数量',
				name : 'purchAmount',
				tclass : 'txtshort',
				width : 50
			}, {
				display : '任务数量',
				name : 'taskAmount',
				tclass : 'txtshort',
				width : 50
			},{
				display : '下达订单数量',
				name : 'issuedAmount',
				tclass : 'txtshort'
			},  {
				display : '单价',
				name : 'price',
				tclass : 'txtshort',
				process : function(v, row) {
					return moneyFormat2(v);
				},
				width : 70
			}, {
				display : '金额',
				name : 'moneyAll',
				tclass : 'txtshort',
				process : function(v, row) {
					return moneyFormat2(v);
				},
				width : 70
			}, {
				display : '希望交货日期',
				name : 'dateHope',
				type : 'date',
				tclass : 'txtshort'
			}]
		},
		comboEx : [{
			text : '单据状态',
			key : 'state',
			data : [{
				text : '未接收',
				value : '未接收'
			}, {
				text : '已接收',
				value : '已接收'
			}
//			, {
//				text : '完成',
//				value : '完成'
//			}
			]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		}, {
			display : '任务下达人',
			name : 'sendName'
		}, {
			display : '下达日期',
			name : 'sendTime'
		}, {
			display : '物料名称',
			name : 'productName'
		}],
		// 扩展右键菜单

		menusEx : [{
			text : '接收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == "未接收") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定接收吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_task_task&action=submit&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('接收成功！');
								$("#taskGrid").yxsubgrid("reload");// 重新加载
							} else {
								alert('接收失败！');
							}
						}
					});
				}
			}
		}
//		,{
//			text : '下达采购订单',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.state == "已接收") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				location = "?model=purchase_contract_purchasecontract&action=toAddOrderByAsset&applyId="+ row.id + "&orderType=asset&skey="+row['skey_'];
//			}
//		}
		],
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 600
		}
	});
});
