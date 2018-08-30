var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		title : '销售退货',
		param : {'qualityState' : '3','ExaStatusArr' : '完成'},
		isDelAction : false,
		isToolBar : true, //是否显示工具栏
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'returnCode',
			display : '退货单编号',
			sortable : true,
			width : 150
		}, {
			name : 'contractCode',
			display : '源单编号',
			sortable : true,
			width : 150,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>"
						+ '</a>';
			}
		}, {
			name : 'saleUserName',
			display : '销售负责人',
			sortable : true,
			width : 90
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			width : 80
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true
		}, {
			name : 'reason',
			display : '退料原因',
			sortable : true,
			width : 300
		}, {
			name : 'instockStatus',
			display : '入库状态',
			sortable : true,
			process : function(v) {
				if(v == 0){
					return "未入库";
				}else if(v == 1){
					return "部分入库";
				}else if(v == 2){
					return "已入库";
				}
			},
			width : 80
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_return_returnequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'returnId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '物料编号'
					},{
						name : 'productName',
						width : 200,
						display : '物料名称'
					}, {
						name : 'productModel',
						width : 200,
						display : '型号/版本'
					}, {
					    name : 'number',
					    display : '申请数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}]
		},
		menusEx : [
				{
					text : '查看',
					icon : 'view',
					action : function(row) {
						showModalWin('?model=projectmanagent_return_return&action=viewTab&id='
								+ row.id
								+ "&skey="+row['skey_']);
					}
				}, {
					name : 'addred',
					text : "销售出库(红字)",
					icon : 'business',
					showMenuFn : function(row) {
						if (row.ExaStatus == "完成" && (row.instockStatus == 0 || row.instockStatus == 1)) {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						showModalWin("?model=stock_outstock_stockout&action=toAddRedByAwait&id="
								+ row.id
								+ "&skey="
								+ row['skey_'])
					}
				}, {
					name : 'addorther',
					text : "其他入库",
					icon : 'business',
					showMenuFn : function(row) {
						if (row.ExaStatus == "完成" && (row.instockStatus == 0 || row.instockStatus == 1)) {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						showModalWin("?model=stock_instock_stockin&action=toBluePush&docType=RKOTHER" +
								"&relDocId="+row.id +
								"&relDocType=RXSTH"+
								"&relDocCode="+row.returnCode
								)
					}
				}],
		comboEx : [{
			text : '入库状态',
			key : 'instockStatusArr',
			value : '0,1',
			data : [{
				text : '未入库',
				value : '0'
			}, {
				text : '部分入库',
				value : '1'
			}, {
				text : '已入库',
				value : '2'
			}, {
				text : '未入库，部分入库',
				value : '0,1'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '退货单编号',
			name : 'returnCode'
		},{
			display : '源单编号',
			name : 'contractCode'
		},{
			display : '销售负责人',
			name : 'saleUserName'
		}]
	});
});