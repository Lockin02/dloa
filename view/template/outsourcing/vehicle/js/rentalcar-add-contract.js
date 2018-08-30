$(document).ready(function() {

	//合同性质改变事件
	$("#contractNatureCode").change(function (){
		if($(this).val() == 'ZCHTXZ-01') {
			$("#fundCondition").addClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"blue");
		} else {
			$("#fundCondition").removeClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"");
		}
	});

	//合同负责人
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isGetDept : [true, "deptId", "deptName"]
	});

	//归属公司
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

	//合同性质改变事件
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

	//是否盖章
	$("input[name=rentcar[isNeedStamp]]:radio").change(function () {
		if ($(this).val() == 1) {
			//附件盖章验证
			if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件"){
				alert('申请盖章前需要上传合同附件!');
				$(this).next(":radio").attr("checked" ,true);
				return false;
			}
			setStampType();
		} else {
			$("#stampType").yxcombogrid_stampconfig("remove").removeClass("validate[required]").val("");
			$("#stampTypeSpan").css("color" ,"");
		}
	});

	//计算合同天数
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

	//是否使用油卡
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

	//根据是否申请油卡初始化是否使用油卡
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

//根据companyProvince的值设置下拉选中
function setProSelectByName() {
	$("#companyProvinceCode option").each(function () {
		if ($(this).text() == $("#companyProvince").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//根据companyCity的值设置下拉选中
function setCitySelectByName() {
	$("#companyCityCode option").each(function () {
		if ($(this).text() == $("#companyCity").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//签约公司下拉渲染
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

//盖章类型下拉渲染
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

//计算合同天数
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