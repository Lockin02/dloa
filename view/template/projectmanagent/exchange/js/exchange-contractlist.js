var show_page = function(page) {
	$("#contractlistGrid").yxsubgrid("reload");
};
$(function() {
	$("#contractlistGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		param : {'contractId' : $("#contractId").val()},
		title : '销售换货',
		isDelAction : false,
		isToolBar : false, //是否显示工具栏
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
			name : 'exchangeCode',
			display : '换货单编号',
			sortable : true,
			width : '180'
		}, {
			name : 'contractCode',
			display : '源单号',
			sortable : true,
			width : '180'
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : '180'
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
			display : '换货原因',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_exchange_exchangebackequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'exchangeId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '产品编号'
					},{
						name : 'productName',
						width : 200,
						display : '产品名称'
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
				showOpenWin('?model=projectmanagent_exchange_exchange&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&perm=view' );
			}
		},{
				text : '审批情况',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='未审批'){
	                   return false;
	               }
	                   return true;
	            },
				action : function(row) {

					showThickboxWin('controller/projectmanagent/return/readview.php?itemtype=oa_sale_return	&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '换货单编号',
			name : 'exchangeCode'
		}]
	});
});