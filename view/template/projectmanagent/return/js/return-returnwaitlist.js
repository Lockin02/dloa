var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		title : '销售退货',
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
			name : 'qualityState',
			display : '状态',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return "待处理";
					case '1' : return "质检中";
					case '2' : return "已处理";
                    case '3' : return "质检完成";
				}
			},
			width : 50
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
				},{
					text : '提交质检',
					icon : 'add',
					showMenuFn : function(row) {
						if (row.qualityState == "0" && row.ExaStatus == "完成") {
							return true;
						}
						return false;
					},
					action : function(row) {
						if (row) {
							showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
								+ row.id
								+ "&relDocType=ZJSQYDTH"
								+ "&relDocCode=" + row.returnCode
								,1,500,1000,row.id
							);
						}
					}
				},],
		comboEx : [{
			text : '处理状态',
			key : 'qualityState',
			value : '0',
			data : [{
				text : '待处理',
				value : '0'
			}, {
				text : '质检中',
				value : '1'
			}, {
                text : '质检完成',
                value : '3'
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