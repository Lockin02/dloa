var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		param : {'createId' : $("#userId").val()},
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
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true
		}, {
			name : 'reason',
			display : '退料原因',
			sortable : true,
			width : 300
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
						width : 100,
						display : '物料编号'
					},{
						name : 'productName',
						width : 150,
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
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_return_return&action=toAdd');

			}
		}],
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
			text : '编辑',
			icon : 'edit',
            showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_return_return&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']);
			}
		},{
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/projectmanagent/return/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}

		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_return_return&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#returnGrid").yxsubgrid("reload");
							}
						}
					});
				}
			}

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
		}]
	});
});