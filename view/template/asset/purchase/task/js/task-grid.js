var show_page = function(page) {
	$("#taskGrid").yxsubgrid("reload");
};
$(function() {
	$("#taskGrid").yxsubgrid({
		model : 'asset_purchase_task_task',
		title : '资产采购任务',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
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
			width : 150
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
			sortable : true
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
		// 扩展右键菜单

		menusEx : [{
			text : '重新分配',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == "未接收") {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=asset_purchase_task_task&action=init&id='
						+ row.id +

						'&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
			}
		}],
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
			display : '采购员',
			name : 'purcherName'
		}, {
			display : '下达日期',
			name : 'sendTime'
		}, {
			display : '物料名称',
			name : 'productName'
		}],
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