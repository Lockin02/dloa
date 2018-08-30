var show_page = function(page) {
	$("#equGrid").yxsubgrid("reload");
};
$(function() {
	$("#equGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'equJson',
		title : '合同设备',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
			name : 'id',
			display : 'id',
			hide : true
		}, {
			name : 'productId',
			display : '设备Id',
			sortable : true,
			hide : true
		}, {
			name : 'productName',
			display : '设备名称',
			width : 150,
			sortable : true
		}, {
			name : 'productNo',
			display : '设备编号',
			sortable : true
		}, {
			name : 'number',
			display : '合同数量',
			sortable : true
		}, {
			name : 'executedNum',
			display : '已执行数量',
			sortable : true
		}, {
			name : 'onWayNum',
			display : '在途数量',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=contJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'productId',// 传递给后台的参数名称
				colId : 'productId'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				name : 'tablename',
				display : '合同类型',
				sortable : true,
				process : function(v) {
					if (v == 'oa_sale_order') {
						return "销售合同";
					}else if (v == 'oa_sale_lease') {
						return "租赁合同";
					}else if (v == 'oa_sale_service'){
					    return "服务合同";
					}else if (v == 'oa_sale_rdproject'){
					    return "研发合同";
					}
				}
			}, {
				name : 'orderCode',
				width : 180,
				sortable : true,
				display : '正式合同号'
			}, {
				name : 'orderTempCode',
				width : 180,
				sortable : true,
				display : '临时合同号'
			}, {
				name : 'number',
				sortable : true,
				display : '合同数量'
			}, {
				name : 'onWayNum',
				sortable : true,
				display : '在途数量'
			}, {
				name : 'executedNum',
				sortable : true,
				display : '已执行数量'
					// },{
					// name : 'projArraDate',
					// display : '计划交货日期',
					// process : function(v){
					// if( v == null ){
					// return '无';
					// }else{
					// return v;
					// }
					// }
					// },{
					// name : 'exeNum',
					// display : '库存数量',
					// process : function(v){
					// if(v==''){
					// return 0;
					// }else
					// return v;
					// }
					}]
		},

		// menusEx : [{
		// }],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '设备名称',
			name : 'productName'
		}]
	});
});