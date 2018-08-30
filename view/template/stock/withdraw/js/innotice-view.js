$(document).ready(function() {
	var param = {
    	'mainId' : $("#id").val()
    };
	$("#noticeequ").yxeditgrid({
		objName : 'withdraw[items]',
		url : '?model=stock_withdraw_noticeequ&action=listJson',
		param : param,
		title : '物料清单',
		type : 'view',
		colModel : [{
			name : 'productCode',
			display : '物料编号'
		},{
			name : 'productName',
			display : '物料名称'
		},{
			name : 'productModel',
			display : '型号/版本'
		},{
			name : 'number',
			display : '数量'
		},{
			name : 'executedNum',
			display : '已入库数量'
		},{
			name : 'remark',
			display : '备注'
		}]
	})
})