$(document).ready(function() {
	//ʵ��ʵ������
	if ($("#contractTypeCode").val() == 'ZCHTLX-03') {
		$("#extTr").show();
		$("#reimbursedFuelTd1").show();
		$("#reimbursedFuelTd2").show();
	} else {
		$("#extTr").hide();
		$("#reimbursedFuelTd1").hide();
		$("#reimbursedFuelTd2").hide();
	}

	//���⳵��
	if ($("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#shortRent").parent().show().prev().show();
		if ($("#contractTypeCode").val() == 'ZCHTLX-02') { //������Ƽ��ͷѵ���
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
			display : '��������',
			align : 'left',
			width : '25%'
		},{
			name : 'feeAmount',
			display : '���ý��',
			width : '15%',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'yesOrNo',
			display : '�Ƿ�ѡ��',
			type : 'checkbox',
			checkVal : 1,
			width : '10%',
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}
		},{
			name : 'remark',
			display : '��  ע',
			align : 'left',
			width : '50%'
		}]
	});
});