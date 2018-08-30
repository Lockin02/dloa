$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireoutitem&action=listByRequireJson',
		objName : 'requireout[items]',
		title : '卡片信息',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			display : '资产编号',
			name : 'assetCode',
			tclass : 'txt',
			width : 120
		}, {
			display : '资产名称',
			name : 'assetName',
			tclass : 'txt',
			width : 120
		}, {
			display : '资产残值',
			name : 'salvage',
			tclass : 'txt',
			width : 120,
			process : function(v) {
				return moneyFormat2(v);
			}
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
			display : '规格型号',
			name : 'spec',
			width : 120
		}, {
			display : '申请数量',
			name : 'number',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '已入库数量',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
});