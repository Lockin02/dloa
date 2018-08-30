$(document).ready(function() {

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

	//合同类型事件
	if ($("#contractTypeCode").val() == 'ZCHTLX-03') {
		$("#extTr").show();
		$("#reimbursedFuelTd1").show();
		$("#reimbursedFuelTd2").show();
	} else {
		$("#extTr").hide();
		$("#reimbursedFuelTd1").hide();
		$("#reimbursedFuelTd2").hide();
	}

	//租车性质事件
	if ($("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#shortRent").parent().show().prev().show();
		$("#shortRent_v").addClass("validate[required]");
		if ($("#contractTypeCode").val() == 'ZCHTLX-02') {
			$("#extTr").show();
			$("#gasolineKMPrice").parent().show().prev().show();
			$("#gasolineKMPrice_v").addClass("validate[required]");
		}
	}

	//是否使用油卡支付
	$("#isCardPay").val($("#isCardPayVal").val());

	$("#startMileage_v").blur(function (){
		countMileage();
	});
	$("#endMileage_v").blur(function (){
		countMileage();
	});

	//车牌
	$("#carNum").change(function () {
		getRentFree();
	});

	$("#feeInfo").yxeditgrid({
		objName : 'register[fee]',
		isAddAndDel : false,
		url : '?model=outsourcing_vehicle_registerfee&action=listJson',
		param : {
			dir : 'ASC',
			registerId : $("#id").val()
		},
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
			name : 'remark',
			display : '备  注',
			type : 'statictext',
			align : 'left',
			width : '50%'
		}]
	});

	validate({
		"driverName" : {
			required : true
		},
		"carNum" : {
			required : true
		},
		"changeReason" : {
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
	var isCanAddByChange = $.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=isCanAddByChange",
						data: {
							'projectId' : $("#projectId").val(),
							'useCarDate' : $("#useCarDate").val(),
							'carNum' : $("#carNum").val(),
							'id' : $("#id").val()
						},
						async: false
					}).responseText;
	if (isCanAddByChange == 0) {
		alert('项目上该车牌已存在用车日期为' + $("#useCarDate").val() + '的记录');
		return false;
	}else if(!chkExistRecords()){
		return false;
	}else{
		return 'pass';
	}
}

var toSubmit = function () {
	if(checkData() == 'pass'){
		$("#form1").submit();
	}
}

//获取合同附加费用
function getRentFree() {
	$.ajax({
		type : "POST",
		url : "?model=outsourcing_contract_rentcarfee&action=listJsonByCar",
		data : {
			'useCarDate' : $("#useCarDate").val(),
			'carNum' : $("#carNum").val()
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