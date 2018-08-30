$(document).ready(function() {

	$("#itemInfo").yxeditgrid({
		type : 'view',
		url : '?model=contract_gridreport_gridindicatorsitem&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		colModel : [{
			name : 'indicatorsName',
			display : '指标名称',
			width : '40%'
		},{
			name : 'indicatorsCode',
			display : '指标编码',
			width : '40%'
		},{
			name : 'isEnable',
			display : '默认加载',
			width : '10%',
			process : function (v) {
				if (v == 1) {
					return "是";
				} else {
					return "否";
				}
			}
		}]
	});

})