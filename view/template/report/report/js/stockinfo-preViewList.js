var show_page = function(page) {
	$("#presentGrid").yxsubgrid("reload");
};
$(function() {
	$("#presentGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		title : '赠送申请',
		param : {'ids' : $("#pids").val()},
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '编号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'salesName',
					display : '申请人',
					sortable : true
				}, {
					name : 'reason',
					display : '申请理由',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 120
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_present_presentequ&action=listpageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'presentId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productNo',
						width : 200,
						display : '产品编号'
					},{
						name : 'productName',
						width : 200,
						display : '产品名称'
					}, {
					    name : 'number',
					    display : '需求数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}]
		},
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '源单编号',
					name : 'orderCode'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '业务编号',
					name : 'objCode'
				}]
	});
});