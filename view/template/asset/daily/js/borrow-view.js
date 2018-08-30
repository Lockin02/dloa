$(document).ready(function() {
	$("#borrowTable").yxeditgrid({
		objName : 'borrow[borrowitem]',
		url : '?model=asset_daily_borrowitem&action=listJson',
		type : 'view',
		param : {
			borrowId : $("#borrowId").val(),
			assetId : $("#assetId").val()

		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '购入日期',
			name : 'buyDate',
			type : 'date'
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort'
		}, {
			display : '预计使用期间数',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '剩余使用期间数',
			name : 'residueYears',
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

});