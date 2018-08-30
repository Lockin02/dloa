$(document).ready(function() {
	//Ψһ����֤
	$("#objCode").ajaxCheck({
		url : "?model=contract_gridreport_gridindicators&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* ��ҵ������Ѵ���",
		alertTextOk : "* OK"
	});

	$("#itemInfo").yxeditgrid({
		objName : 'gridindicators[item]',
		url : '?model=contract_gridreport_gridindicatorsitem&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'indicatorsName',
			display : 'ָ������',
			width : '40%',
			validation : {
				required : true
			}
		},{
			name : 'indicatorsCode',
			display : 'ָ�����',
			width : '40%',
			validation : {
				required : true
			}
		},{
			name : 'isEnable',
			display : 'Ĭ�ϼ���',
			width : '10%',
			type : 'select',
			options : [{
				name : "��",
				value : "0"
			},{
				name : "��",
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