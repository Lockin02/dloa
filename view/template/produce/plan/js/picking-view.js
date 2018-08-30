$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '生产计划单号',
			name : 'planCode',
			width : 120
		},{
			display : '合同编号',
			name : 'relDocCode',
			width : 120
		},{
			display: '生产批次号',
			name: 'productionBatch',
			width: 120
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode',
			width : 75
		},{
			display : '物料名称',
			name : 'productName',
			width : 250
		},{
			display : '规格型号',
			name : 'pattern',
			width : 90
		},{
			display : '单位',
			name : 'unitName',
			width : 80
		},{
			display : '旧设备仓',
			name : 'JSBC',
			width : 50
		},{
			display : '库存商品',
			name : 'KCSP',
			width : 50
		},{
			display : '生产仓',
			name : 'SCC',
			width : 50
		},{
			display : '申请数量',
			name : 'applyNum',
			width : 50
		},{
			display : '出库数量',
			name : 'realityNum',
			width : 50,
			process : function (v ,row) {
				if (row.proOutNum > 0) {
					return '<span class="blue">' + v + '</span>';
				} else {
					return v;
				}
			}
		},{
			display : '计划<br>领料日期',
			name : 'planDate',
			width : 80
		},{
			display : '实际<br>领料日期',
			name : 'realityDate',
			width : 80
		},{
			display : '备注',
			name : 'remark',
			width : 150,
			align : 'left'
		}]
	});
});