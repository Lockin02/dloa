$(document).ready(function() {
	//若单据状态为打回，且存在打回原因，则显示
	if($('#status').val() == '打回' && $('#backReason').html() != ''){
		$('#backReason').parents('tr:first').show();
	}
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '物料信息',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			display : '设备名称',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '设备描述',
			name : 'description',
			tclass : 'txt',
			width : 120
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt',
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			width : 120
		}, {
			display : '物料金额',
			name : 'productPrice',
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '物料品牌',
			name : 'brand',
			tclass : 'txt',
			width : 80
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txt',
			width : 120
		}, {
			display : '申请数量',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '已出库数量',
			name : 'executedNum',
			tclass : 'txt',
			width : 60
		}, {
			display : '生成卡片数量',
			name : 'cardNum',
			tclass : 'txt',
			width : 80
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
});