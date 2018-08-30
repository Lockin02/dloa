$(document).ready(function() {
	//实报实销费用
	if ($("#contractTypeCode").val() == 'ZCHTLX-03') {
		$("#extTr").show();
		$("#reimbursedFuelTd1").show();
		$("#reimbursedFuelTd2").show();
	} else {
		$("#extTr").hide();
		$("#reimbursedFuelTd1").hide();
		$("#reimbursedFuelTd2").hide();
	}

	//短租车费
	if ($("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#shortRent").parent().show().prev().show();
		if ($("#contractTypeCode").val() == 'ZCHTLX-02') { //按公里计价油费单价
			$("#extTr").show();
			$("#gasolineKMPrice").parent().show().prev().show();
		} else {
			$("#gasolineKMPrice").parent().hide().prev().hide();
		}
	} else {
		$("#shortRent").parent().hide().prev().hide();
	}

	$("#feeInfo").yxeditgrid({
		objName : 'register[fee]',
		isAddAndDel : false,
		url : '?model=outsourcing_vehicle_registerfee&action=listJson',
		param : {
			dir : 'ASC',
			registerId : $("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'feeName',
			display : '费用名称',
			align : 'left',
			width : '25%'
		},{
			name : 'feeAmount',
			display : '费用金额',
			width : '15%',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'yesOrNo',
			display : '是否选择',
			type : 'checkbox',
			checkVal : 1,
			width : '10%',
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}
		},{
			name : 'remark',
			display : '备  注',
			align : 'left',
			width : '50%'
		}]
	});
});