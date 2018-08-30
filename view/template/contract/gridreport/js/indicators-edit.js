$(document).ready(function() {

	$("#itemInfo").yxeditgrid({
		objName : 'indicators[item]',
		url : '?model=contract_gridreport_indicators&action=listJson',
		param : {
			dir : 'ASC',
			setCode : $("#setCode").val()
		},
		isAdd : false,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'indicatorsName',
			display : 'ָ������',
			width : '10%',
			type : 'statictext'
		},{
			name : 'indicatorsName',
			display : 'ָ������',
			type : 'hidden'
		},{
			name : 'indicatorsCode',
			display : 'ָ�����',
			type : 'hidden'
		},{
			name : 'monthJan',
			display : 'һ��',
			width : '7%',
			type : 'money'
		},{
			name : 'monthFeb',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthMar',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthApr',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthMay',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthJun',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthJul',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthAug',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthSep',
			display : '����',
			width : '7%',
			type : 'money'
		},{
			name : 'monthOct',
			display : 'ʮ��',
			width : '7%',
			type : 'money'
		},{
			name : 'monthNov',
			display : 'ʮһ��',
			width : '7%',
			type : 'money'
		},{
			name : 'monthDec',
			display : 'ʮ����',
			width : '7%',
			type : 'money'
		}]
	});

	validate({
		"setCode" : {
			required : true
		},
		"setName" : {
			required : true
		}
	});
});