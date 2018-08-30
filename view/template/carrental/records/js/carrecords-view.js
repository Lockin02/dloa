$(document).ready(function() {

	$("#importTable").yxeditgrid({
		objName : 'carrecords[carrecordsdetail]',
		url : '?model=carrental_records_carrecordsdetail&action=listJson',
		param : {
			recordsId : $("#recordsId").val()
		},
		type : 'view',
		title : '用车明细',
		colModel : [{
			display : '使用日期',
			name : 'useDate'
		}, {
			display : '起始公里数',
			name : 'beginNum'
		}, {
			display : '结束公里数',
			name : 'endNum'
		}, {
			display : '里程数',
			name : 'mileage'
		}, {
			display : '使用时长',
			name : 'useHours'
		}, {
			display : '用途',
			name : 'useReson'
		}, {
			display : '乘车费',
			name : 'travelFee',
			type : 'money'
		}, {
			display : '油费',
			name : 'fuelFee',
			type : 'money'
		}, {
			display : '路桥费',
			name : 'roadFee',
			type : 'money'
		}, {
			display : '有效LOG',
			name : 'effectiveLog'
		}]
	})
 })