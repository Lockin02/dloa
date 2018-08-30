$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
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
			display : '入库数量',
			name : 'backNum',
			width : 50
		},{
			display : '备注',
			name : 'remark',
			width : 150,
			align : 'left'
		}]
	});
});