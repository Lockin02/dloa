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
			display : 'ָ������',
			width : '40%'
		},{
			name : 'indicatorsCode',
			display : 'ָ�����',
			width : '40%'
		},{
			name : 'isEnable',
			display : 'Ĭ�ϼ���',
			width : '10%',
			process : function (v) {
				if (v == 1) {
					return "��";
				} else {
					return "��";
				}
			}
		}]
	});

})