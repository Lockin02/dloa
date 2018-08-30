$(document).ready(function() {

	//租车合同类型
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

	//是否使用油卡
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
			display : '车型',
			width : '15%'
		},{
			name : 'carNumber',
			display : '车牌号',
			width : '15%'
		},{
			name : 'driver',
			display : '驾驶员',
			width : '20%'
		},{
			name : 'idNumber',
			display : '驾驶员身份证号',
			width : '25%'
		},{
			name : 'displacement',
			display : '排量、使用何种汽油',
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
			display : '费用名称',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '费用金额',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '备  注',
			type : 'textarea',
			width : 220,
			rows : 2,
			align : 'left'
		}]
	});
});