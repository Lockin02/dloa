var show_page = function(page) {
	$("#mystockupGrid").yxsubgrid("reload");
};
$(function() {
	$("#mystockupGrid").yxsubgrid({
		model : 'projectmanagent_stockup_stockup',
		title : '我的销售备货申请',
		param : {'createId' : $("#userId").val()},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'stockupCode',
			display : '备货单编号',
			sortable : true,
			width : 200
		}, {
			name : 'type',
			display : '备货类型',
			sortable : true,
			hide : true
		}, {
			name : 'sourceType',
			display : '源单类型',
			sortable : true,
			hide : true
		}, {
			name : 'sourceId',
			display : '源单ID',
			sortable : true,
			hide : true
		}, {
			name : 'state',
			display : '状态',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 300
		}],
        // 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_stockup_equ&action=PageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'stockupId',// 传递给后台的参数名称
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
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "搜索字段",
			name : 'XXX'
		}
	});
});