var show_page = function(page) {
	$("#pickingoutGrid").yxgrid("reload");
};

$(function() {
	$("#pickingoutGrid").yxgrid({
		model : 'produce_plan_pickingout',
		title : '生产仓出库记录',
		param : {
			pickingId : $('#pickId').val()
		},
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'pickingCode',
			display : '领料单据编号',
			sortable : true,
			width : 120
		},{
			name : 'productCode',
			display : '物料编码',
			sortable : true
		},{
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width : 250
		},{
			name : 'pattern',
			display : '规格型号',
			sortable : true
		},{
			name : 'unitName',
			display : '单位',
			sortable : true
		},{
			name : 'applyMan',
			display : '申请人',
			sortable : true
		},{
			name : 'applyDate',
			display : '申请日期',
			sortable : true
		},{
			name : 'applyNum',
			display : '申请数量',
			sortable : true
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 250
		}],

		searchitems : [{
			display : "领料单据编号",
			name : 'pickingCode'
		},{
			display : "物料编码",
			name : 'productCode'
		},{
			display : "物料名称",
			name : 'productName'
		},{
			display : "申请人",
			name : 'applyMan'
		},{
			display : "申请日期",
			name : 'applyDate'
		}]
	});
});