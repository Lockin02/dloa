$(document).ready(function() {

	//�⳵��ͬ����
	var $contractTypeCode = $("#contractTypeCode");
	if ($contractTypeCode.val() == 'ZCHTLX-01') {
		$("#oilPriceTd1").show();
		$("#oilPriceTd2").show();
	}else if ($contractTypeCode.val() == 'ZCHTLX-02') {
		$("#fuelChargeTd1").show();
		$("#fuelChargeTd2").show();
	}
	if($("#hideBtn").val() == 1){
		$("#closeBtn").hide();
	}

	//�Ƿ�ʹ���Ϳ�
	if ($("#isUseOilcardVal").val() == 1) {
		$("#oilcardMoneyTd1").show();
		$("#oilcardMoneyTd2").show();
	} else {
		$("#oilcardMoneyTd1").hide();
		$("#oilcardMoneyTd2").hide();
	}

	var contractId = 0;
	if ($("#originalId").val() != 0 && $("#originalId").val()) {
		contractId = $("#originalId").val();
	} else {
		contractId = $("#id").val();
	}

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_vehicle&action=listJson',
		param : {
			dir : 'ASC',
			contractId : contractId,
			isTemp : '0'
		},
		type : 'view',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'carModel',
			display : '����',
			width : '15%'
		},{
			name : 'carNumber',
			display : '���ƺ�',
			width : '15%'
		},{
			name : 'driver',
			display : '��ʻԱ',
			width : '20%'
		},{
			name : 'idNumber',
			display : '��ʻԱ���֤��',
			width : '25%'
		},{
			name : 'displacement',
			display : '������ʹ�ú�������',
			width : '25%'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_rentcarfee&action=listJson',
		param : {
			dir : 'ASC',
			contractId : contractId,
			isTemp : '0'
		},
		type : 'view',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'feeName',
			display : '��������',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '���ý��',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '��  ע',
			type : 'textarea',
			width : 220,
			rows : 2,
			align : 'left'
		}]
	});
});