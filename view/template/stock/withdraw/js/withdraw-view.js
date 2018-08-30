$(document).ready(function() {
	$("#withdrawequ").yxeditgrid({
		objName : 'withdraw[items]',
		url : '?model=stock_withdraw_equ&action=listJson',
		param : {
	    	'mainId' : $("#id").val()
	    },
		title : '物料清单',
		type : 'view',
		tableClass : 'form_in_table',
		colModel : [{
			display : '源单清单id',
			name : 'contEquId',
			type : 'hidden'
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '物料编号'
		},{
			name : 'productName',
			display : '物料名称'
		},{
			name : 'productModel',
			display : '型号/版本'
		},{
			name : 'stockName',
			display : '收料仓库',
			width : 80
		},{
			name : 'number',
			display : '数量',
			width : 60
		},{
			name : 'qualityNum',
			display : '报检数量',
			width : 60
		},{
			name : 'qPassNum',
			display : '合格数量',
			width : 60
		},{
			name : 'qBackNum',
			display : '不合格数量',
			width : 60
		}, {
			name : 'executedNum',
			display : '已收货数量',
			width : 60
		},{
			name : 'compensateNum',
			display : '赔偿数量',
			width : 60
		},{
			name : 'remark',
			display : '备注'
		}]
	})
})