$(document).ready(function() {

	$("#itemInfo").yxeditgrid({
		url : '?model=contract_gridreport_indicators&action=listJson',
		param : {
			dir : 'ASC',
			setCode : $("#setCode").val()
		},
		type : 'view',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'indicatorsName',
			display : '指标名称'
		},{
			name : 'indicatorsCode',
			display : '指标编码',
			type : 'hidden'
		},{
			name : 'monthJan',
			display : '一月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthFeb',
			display : '二月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthMar',
			display : '三月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthApr',
			display : '四月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthMay',
			display : '五月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthJun',
			display : '六月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthJul',
			display : '七月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthAug',
			display : '八月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthSep',
			display : '九月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthOct',
			display : '十月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthNov',
			display : '十一月',
			width : '7%',
			type : 'money'
		},{
			name : 'monthDec',
			display : '十二月',
			width : '7%',
			type : 'money'
		}]
	});
});