$(document).ready(function() {

	//��ͬ���ʸı��¼�
	$("#contractNatureCode").change(function (){
		if($(this).val() == 'ZCHTXZ-01') {
			$("#fundCondition").addClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"blue");
		} else {
			$("#fundCondition").removeClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"");
		}
	});

	//��ͬ������
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isGetDept : [true, "deptId", "deptName"]
	});

	//������˾
	$("#ownCompany").yxcombogrid_branch({
		hiddenId : 'ownCompany',
		height : 200,
		width : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#ownCompanyId").val(data.id);
					$("#ownCompanyCode").val(data.NamePT);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#ownCompanyId").val("");
				$("#ownCompanyCode").val("");
			}
		}
	});

	//��ͬ���ʸı��¼�
	$("#contractTypeCode").change(function (){
		if ($(this).val() == 'ZCHTLX-01') {
			$("#fuelChargeTd1").hide();
			$("#fuelChargeTd2").hide();
			$("#fuelCharge_v").removeClass("validate[required]").val("");
			$("#fuelCharge").val("");
			$("#oilPriceTd1").show();
			$("#oilPriceTd2").show();
			$("#oilPrice_v").addClass("validate[required]");
		}else if ($(this).val() == 'ZCHTLX-02') {
			$("#oilPriceTd1").hide();
			$("#oilPriceTd2").hide();
			$("#oilPrice_v").removeClass("validate[required]").val("");
			$("#oilPrice").val("");
			$("#fuelChargeTd1").show();
			$("#fuelChargeTd2").show();
			$("#fuelCharge_v").addClass("validate[required]");
		}else {
			$("#oilPriceTd1").hide();
			$("#oilPriceTd2").hide();
			$("#oilPrice_v").removeClass("validate[required]").val("");
			$("#oilPrice").val("");
			$("#fuelChargeTd1").hide();
			$("#fuelChargeTd2").hide();
			$("#fuelCharge_v").removeClass("validate[required]").val("");
			$("#fuelCharge").val("");
		}
	});

	//�Ƿ����
	$("input[name=rentcar[isNeedStamp]]:radio").change(function () {
		if ($(this).val() == 1) {
			//����������֤
			if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
				alert('�������ǰ��Ҫ�ϴ���ͬ����!');
				$(this).next(":radio").attr("checked" ,true);
				return false;
			}
			setStampType();
		} else {
			$("#stampType").yxcombogrid_stampconfig("remove").removeClass("validate[required]").val("");
			$("#stampTypeSpan").css("color" ,"");
		}
	});

	//�����ͬ����
	$("#contractStartDate").blur(function () {
		if ($(this).val()) {
			getUseDay();
		} else{
			$("#contractUseDay").val(0);
		}
	});
	$("#contractEndDate").blur(function () {
		if ($(this).val()) {
			getUseDay();
		} else{
			$("#contractUseDay").val(0);
		}
	});

	//�Ƿ�ʹ���Ϳ�
	$("input[name=rentcar[isUseOilcard]]:radio").change(function () {
		if ($(this).val() == 1) {
			$("#oilcardMoneyTd1").show();
			$("#oilcardMoneyTd2").show();
			$("#oilcardMoney_v").addClass("validate[required]");
		} else {
			$("#oilcardMoneyTd1").hide();
			$("#oilcardMoneyTd2").hide();
			$("#oilcardMoney_v").removeClass("validate[required]").val("");
		}
	});

	//�����Ƿ������Ϳ���ʼ���Ƿ�ʹ���Ϳ�
	if ($("#isUseOilcard").val() == $("#isApplyOilCard").val()) {
		$("#isUseOilcard").attr("checked" ,"checked").trigger("change");
	} else {
		$("#isUseOilcard").next().attr("checked" ,"checked").trigger("change");
	}

	validate({
		"contractNatureCode" : {
			required : true
		},
		"contractTypeCode" : {
			required : true
		},
		"orderName" : {
			required : true
		},
		"principalName" : {
			required : true
		},
		"signCompany" : {
			required : true
		},
		"companyProvinceCode" : {
			required : true
		},
		"orderMoney_v" : {
			required : true
		},
		"companyCityCode" : {
			required : true
		},
		"linkman" : {
			required : true
		},
		"isNeedStamp" : {
			required : true
		},
		"phone" : {
			required : true
		},
		"ownCompany" : {
			required : true
		},
		"rentalcarCode" : {
			required : true
		},
		"rentUnitPrice_v" : {
			required : true
		},
		"signDate" : {
			required : true
		}
	});
});

//����companyProvince��ֵ��������ѡ��
function setProSelectByName() {
	$("#companyProvinceCode option").each(function () {
		if ($(this).text() == $("#companyProvince").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//����companyCity��ֵ��������ѡ��
function setCitySelectByName() {
	$("#companyCityCode option").each(function () {
		if ($(this).text() == $("#companyCity").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//ǩԼ��˾������Ⱦ
function setSignCompany() {
	var suppIds = $("#suppIds").val();
	$("#signCompany").yxcombogrid_vehiclesupp({
		hiddenId : 'signCompanyId',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			param : {'idArr' : suppIds},
			isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#companyProvince").val(data.province);
					setProSelectByName();
					$("#companyProvinceCode").trigger("change");
					$("#companyCity").val(data.city);
					setCitySelectByName();
					$("#linkman").val(data.linkmanName);
					$("#phone").val(data.linkmanPhone);
					$("#address").val(data.address);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#companyProvinceCode").val("").trigger("change");
				$("#linkman").val("");
				$("#phone").val("");
				$("#address").val("");
			}
		}
	});
}

//��������������Ⱦ
function setStampType() {
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampType',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true
		}
	}).addClass("validate[required]");
	$("#stampTypeSpan").css("color" ,"blue");
}

//�����ͬ����
function getUseDay() {
	var startDateVal = $("#contractStartDate").val();
	var endDateVal = $("#contractEndDate").val();
	var startDate = new Date(Date.parse(startDateVal.replace(/-/g ,"/")));
	var endDate = new Date(Date.parse(endDateVal.replace(/-/g ,"/")));
	var useDay = (endDate.getTime() - startDate.getTime())/3600000/24;
	if (useDay > 0) {
		$("#contractUseDay").val(useDay);
	} else{
		$("#contractUseDay").val(0);
	}
}