$(document).ready(function() {

	//项目下拉
	$("#projectName").yxcombogrid_esmproject({
		isDown : false,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		height : 250,
		isFocusoutCheck : true,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr':'GCXMZT01,GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectId").val(data.id);
					$("#projectCode").val(data.projectCode);
					$("#officeId").val(data.officeId);
					$("#officeName").val(data.officeName);
					$("#projectType").val(data.natureName);
					$("#projectTypeCode").val(data.nature);
					$("#projectManager").val(data.managerName);
					$("#projectManagerId").val(data.managerId);
					$("#province").val(data.provinceId).trigger('change');
					$("#city").val(data.cityId).trigger('change');
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectId").val("");
				$("#projectCode").val("");
				$("#officeId").val("");
				$("#officeName").val("");
				$("#projectType").val("");
				$("#projectTypeCode").val("");
				$("#projectManager").val("");
				$("#province").val("").trigger('change');
				$("#city").val("").trigger('change');
			}
		}
	});

	//车辆供应商下拉
	$("#suppName").yxcombogrid_outsuppvehicle({
		hiddenId : 'suppId',
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#suppCode").val(data.suppCode);
					$("#suppName").val(data.suppName);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#suppCode").val("");
				$("#suppName").val("");
				$("#suppId").val("");
			}
		}
	});

	$("#startMileage_v").blur(function (){
		countMileage();
	});
	$("#endMileage_v").blur(function (){
		countMileage();
	});

	//合同类型改变事件
	$("#contractTypeCode").change(function (){
		if ($(this).val() == 'ZCHTLX-03') {
			$("#extTr").show();
			$("#reimbursedFuelTd1").show();
			$("#reimbursedFuelTd2").show();
			$("#reimbursedFuel_v").addClass("validate[required]");
		} else {
			$("#extTr").hide();
			$("#reimbursedFuelTd1").hide();
			$("#reimbursedFuelTd2").hide();
			$("#reimbursedFuel_v").removeClass("validate[required]").val("");
			$("#reimbursedFuel").val("");
		}
		contractAndRental();
	});
	$("#contractTypeCode").trigger("change");

	//租车性质改变事件
	$("#rentalPropertyCode").change(function () {
		if ($(this).val() == 'ZCXZ-02') {
			$("#shortRent").parent().show().prev().show();
			$("#shortRent_v").addClass("validate[required]");
		} else {
			$("#shortRent").val("").parent().hide().prev().hide();
			$("#shortRent_v").removeClass("validate[required]").val("");
		}
		contractAndRental();
	});

	//车牌
	$("#carNum").change(function () {
		if ($.trim($(this).val())) {
			getRentFree();
		} else {
			$("#feeInfo").empty();
		}
	});

	validate({
		"projectName" : {
			required : true
		},
		"useCarDate" : {
			required : true
		},
		"driverName" : {
			required : true
		},
		"rentalPropertyCode" : {
			required : true
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"isCardPay" : {
			required : true
		},
		"carNum" : {
			required : true
		},
		"carModelCode" : {
			required : true
		},
		"startMileage_v" : {
			required : true
		},
		"endMileage_v" : {
			required : true
		},
		"effectLogTime_v" : {
			required : true
		},
		"suppName" : {
			required : true
		},
		"contractTypeCode" : {
			required : true
		}
	});

});

//计算有效里程数
function countMileage() {
	var endMileage = $("#endMileage").val().trim();
	var startMileage = $("#startMileage").val().trim();
	if (endMileage && startMileage) {
		var effectMileage = (endMileage - startMileage).toFixed(2);
		if (effectMileage < 0) {
			effectMileage = 0;
			alert("有效里程无效！")
		}
		$("#effectMileage_v").val(moneyFormat2(effectMileage ,2));
		$("#effectMileage").val(effectMileage);
	}
}

//合同性质和租车性质事件（关于是否填写“按公里计价油费单价”）
function contractAndRental() {
	if ($("#contractTypeCode").val() == 'ZCHTLX-02' && $("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#extTr").show();
		$("#gasolineKMPrice").parent().show().prev().show();
		$("#gasolineKMPrice_v").addClass("validate[required]");
	} else {
		if($("#contractTypeCode").val() != 'ZCHTLX-03'){
			$("#extTr").hide();
		}
		$("#gasolineKMPrice").val("").parent().hide().prev().hide();
		$("#gasolineKMPrice_v").removeClass("validate[required]").val("");
	}
}

//获取合同附加费用
function getRentFree() {
	$.ajax({
		type : "POST",
		url : "?model=outsourcing_contract_rentcarfee&action=listJsonByCar",
		data : {
			useCarDate : $("#useCarDate").val(),
			carNum : $("#carNum").val()
		},
		// async : false,
		success : function (data) {
			if (data != 'false') {
				data = eval("(" + data + ")");

				$("#feeInfo").yxeditgrid({
					objName : 'register[fee]',
					isAddAndDel : false,
					data : data,
					colModel : [{
						name : 'contractId',
						display : '合同ID',
						type : 'hidden'
					},{
						name : 'orderCode',
						display : '合同编号',
						type : 'hidden'
					},{
						name : 'feeName',
						display : '费用名称',
						type : 'statictext',
						align : 'left',
						width : '25%'
					},{
						name : 'feeName',
						display : '费用名称后台用',
						type : 'hidden'
					},{
						name : 'feeAmount',
						display : '费用金额',
						type : 'statictext',
						width : '15%',
						process : function (v) {
							return moneyFormat2(v ,2);
						}
					},{
						name : 'yesOrNo',
						display : '是否选择',
						type : 'checkbox',
						checkVal : 1,
						width : '10%'
					},{
						name : 'feeAmount',
						display : '费用金额后台用',
						type : 'hidden'
					},{
						name : 'remark',
						display : '备  注',
						type : 'statictext',
						align : 'left',
						width : '50%'
					},{
						name : 'remark',
						display : '备注后台用',
						type : 'hidden'
					}]
				});
			} else {
				$("#feeInfo").empty();
			}
		}
	});
}

var chkExistRecords = function(){
	var useCarMonth = $("#useCarDate").val();
	useCarMonth = (useCarMonth != '')? useCarMonth.substr(0,7) : '';// 对应月份
	var projectCode = $("#projectCode").val();// 项目编号
	var suppCode = $("#suppCode").val();// 供应商编码
	var suppName = $("#suppName").val();
	var carNum = $("#carNum").val();// 车牌号码

	var chkResult = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_register&action=ajaxChkRentCarRecord",
		data : {
			useCarMonth : useCarMonth,
			projectCode : projectCode,
			suppCode : suppCode,
			carNum : carNum
		},
		async: false
	}).responseText;

	if(chkResult == 'false' || chkResult == ''){
		return true;
	}else{
		alert("此 "+useCarMonth+" 月份内, 已生成项目为【"+projectCode+"】,供应商为【"+suppName+"】且车牌号为 【"+carNum+"】的登记汇总信息（审批状态为审批中或完成）, 不允许再填报与此相关的记录, 请与项目经理沟通解决。");
		return false;
	}
};

//数据验证
function checkData() {
	var isCanAdd = $.ajax({
						type : "POST",
						url : "?model=outsourcing_vehicle_register&action=isCanAdd",
						data : {
							projectId : $("#projectId").val(),
							useCarDate : $("#useCarDate").val(),
							carNum : $("#carNum").val()
						},
						async: false
					}).responseText;
	if (isCanAdd == 0) {
		alert('项目上该车牌已存在用车日期为' + $("#useCarDate").val() + '的记录');
		return false;
	}else if(!chkExistRecords()){
		return false;
	}else{
		return 'pass';
	}
}