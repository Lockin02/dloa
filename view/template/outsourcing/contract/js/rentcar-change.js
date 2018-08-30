$(document).ready(function() {

	$("#contractNatureCode").trigger("change"); //合同性质
	$("#contractTypeCode").trigger("change"); //合同类型

	//省份城市处理
	$("#companyProvinceCode option").each(function () {
		if ($(this).text() == $("#companyProvince").attr("val")) {
			$(this).attr("selected" ,"selected");
			$("#companyProvinceCode").trigger("change");
		}
	});
	$("#companyCityCode option").each(function () {
		if ($(this).text() == $("#companyCity").attr("val")) {
			$(this).attr("selected" ,"selected");
			$("#companyCity").val($(this).text());
		}
	});

	//是否需要盖章
	$("input[name=rentcar[isNeedStamp]][value=" + $("#isNeedStampVal").val() +"]").attr("checked" ,true).trigger("change");

	//是否使用油卡
	$("input[name=rentcar[isUseOilcard]][value=" + $("#isUseOilcardVal").val() +"]").attr("checked",true).trigger("change");

	//付款申请人
	$('#payApplyMan option').each(function () {
		if ($(this).val() == $("#payApplyMan").attr("val")) {
			$(this).attr("selected" ,"selected");
			return false; //退出循环以提高效率
		}
	});

	//初始化签约公司
	var suppIds = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_rentalcarequ&action=getSuppByParent",
		data : {
			parentId : $("#rentalcarId").val()
		},
		async : false
	}).responseText;
	$("#suppIds").val();
	setSignCompany();

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_vehicle&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#id").val()
		},
		isFristRowDenyDel : true,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'carModelCode',
			display : '车型',
			width : '15%',
			type : 'select',
			datacode : 'WBZCCX' // 数据字典编码
		},{
			name : 'carNumber',
			display : '车牌号',
			width : '10%',
			validation : {
				required : true
			}
		},{
			name : 'driver',
			display : '驾驶员',
			width : '10%',
			validation : {
				required : true
			}
		},{
			name : 'idNumber',
			display : '驾驶员身份证号',
			width : '25%',
			validation : {
				required : true
			}
		},{
			name : 'displacement',
			display : '排量、使用何种汽油',
			width : '15%'
		},{
			name : 'oilCarUse',
			display : '油卡抵充',
			width : '10%',
			type : 'select',
			options : [{
				name : "是",
				value : "是"
			},{
				name : "否",
				value : "否"
			}]
		},{
			name : 'oilCarAmount',
			display : '油卡金额',
			width : '15%',
			type : 'money'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_rentcarfee&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#id").val()
		},
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
			rows : 2
		}]
	});

	validate({
		"changeReason" : {
			required : true
		}
	});
});