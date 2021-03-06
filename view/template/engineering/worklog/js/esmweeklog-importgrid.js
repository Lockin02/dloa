$(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listUnitsJson',
		param : {
			weekId : $("#weekId").val()
		},
		type : 'view',
		title : '用车记录',
		colModel : [{
			display : '项目编号',
			name : 'projectCode'

		}, {
			display : '车牌号',
			name : 'carNo'
		}, {
			display : '司机姓名',
			name : 'driver'
		}, {
			display : '联系电话',
			name : 'linkPhone'
		}, {
			display : '使用日期',
			name : 'useDate'
		}, {
			display : '起始公里数',
			name : 'beginNum',
			tclass : 'txtshort'
		}, {
			display : '结束公里数',
			name : 'endNum',
			tclass : 'txtshort'
		}, {
			display : '里程数',
			name : 'mileage',
			tclass : 'txtshort'
		}, {
			display : '使用时长',
			name : 'useHours',
			tclass : 'txtshort'
		}, {
			display : '用途',
			name : 'useReson'
		}, {
			display : '乘车费',
			name : 'travelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '油费',
			name : 'fuelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '路桥费',
			name : 'roadFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '有效LOG',
			name : 'effectiveLog'
		}]
	})
});