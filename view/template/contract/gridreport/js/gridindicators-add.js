$(document).ready(function() {
	//唯一性验证
	// $("#objCode").ajaxCheck({
	// 	url : "?model=contract_gridreport_gridindicators&action=checkRepeat",
	// 	alertText : "* 该业务编码已存在",
	// 	alertTextOk : "* OK"
	// });

	$("#itemInfo").yxeditgrid({
		objName : 'gridindicators[item]',
		colModel : [{
			name : 'indicatorsName',
			display : '指标名称',
			width : '40%',
			validation : {
				required : true
			}
		},{
			name : 'indicatorsCode',
			display : '指标编码',
			width : '40%',
			validation : {
				required : true
			}
		},{
			name : 'isEnable',
			display : '默认加载',
			width : '10%',
			type : 'select',
			options : [{
				name : "否",
				value : "0"
			},{
				name : "是",
				value : "1"
			}]
		}]
	});

	validate({
		"objName" : {
			required : true
		}
	});
});