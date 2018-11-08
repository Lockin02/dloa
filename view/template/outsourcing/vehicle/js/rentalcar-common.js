$(document).ready(function() {
	initTestRange();

    $("#projectName").yxcombogrid_esmproject({
		isDown : false,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		height : 250,
		isFocusoutCheck : true,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {
				statusArr : 'GCXMZT01,GCXMZT02'
			},
			event : {
				row_dblclick : function(e,row,data) {
					$("#projectId").val(data.id);
					$("#projectCode").val(data.projectCode);
					$("#projectType").val(data.natureName);
					$("#projectTypeCode").val(data.nature);
					$("#projectManager").val(data.managerName);

//					$.ajax({
//						type: 'POST',
//						url: "?model=outsourcing_vehicle_rentalcar&action=getBudgetByProId",
//						data: {
//							projectId: data.id
//						},
//						success: function (result) {
//							if (result > 0) {
//								$("#projectBudget_v").val(result).trigger('blur');
//								$("#allFee_v").val(result).trigger('blur').bind('blur.checkVal' ,checkAllFee);
//							} else {
//								alert('项目没有租车预算！');
//								$("#projectBudget_v").val(0).trigger('blur');
//								$("#allFee_v").val(0).trigger('blur').bind('blur.checkVal' ,checkAllFee);
//							}
//						}
//					});

					$("#province").val(data.provinceId).trigger('change');
					$("#city").val(data.cityId).trigger('change');
					calBudgetVal();
				}
			}
		},
		event : {
			clear : function() {
				$("#projectId").val("");
				$("#projectCode").val("");
				$("#projectType").val("");
				$("#projectTypeCode").val("");
				$("#projectManager").val("");
				$("#projectBudget_v").val('').trigger('blur');
				$("#allFee_v").val('').unbind('blur.checkVal').trigger('blur');
				$("#province").val("").trigger('change');
				$("#city").val("").trigger('change');
				calBudgetVal();
			}
		}
	});

	validate({
		"applicantPhone" : {
			required : true,
			custom : ['onlyNumber']
		},
		"applyExplain" : {
			required : true
		},
		"projectName" : {
			required : true
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"expectStartDate" : {
			required : true
		},
		"useCycle" : {
			required : true
		},
		"rentalPropertyCode" : {
			required : true
		},
		"payGasolineCode" : {
			required : true
		},
		"payParkingCode" : {
			required : true
		},
        "estimateAmonut" : {
            required : true
        }
	});

	var hintArr = 'CDMA团队请手工输入省份、城市';
	$("#usePlace").focus(function(){
		if($.trim($(this).val()) == hintArr) {
			$(this).val("");
			$(this).css({"color":"#000"});
		}
	});
	$("#usePlace").blur(function(){
		if($.trim($(this).val()) == '') {
			$(this).val(hintArr);
			$(this).css({"color":"#999"});
		}
	});

});

//测试范围
function initTestRange() {
	var testRangeArr = $('#testRangeHidden').val().split(",");
	var str;
	var testRangeObj = $('#testRange');
	testRangeObj.combobox({
		url : 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=WBCSFW',
		multiple : true,
		editable : false,
		valueField : 'text',
        textField : 'text',
        formatter: function(obj) {
        	//判断 如果没有初始化数组中，则选中
        	if(testRangeArr.indexOf(obj.text) == -1) {
        		str = "<input type='checkbox' id='testRange_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
        	}else{
        		str = "<input type='checkbox' id='testRange_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
        	}
			return str;
        },
		onSelect : function(obj){
			//checkbox设值
			$("#testRange_" + obj.text).attr('checked',true);
			//设置对象下的选中项
			mulSelectSet(testRangeObj);
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#testRange_" + obj.text).attr('checked',false);
			//设置隐藏域
			mulSelectSet(testRangeObj);
		}
	});

	//初始化赋值
	mulSelectInit(testRangeObj);
}

//隐藏区域设置
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//设值多选值 -- 初始化赋值
function mulSelectInit(thisObj){
	//初始化对应内容
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//计算每月费用
function calMonthlyFee() {
	var $spotPrice = $("#suppInfo").yxeditgrid("getCmpByCol" ,"spotPrice");
	var monthlyFee = 0;
	$spotPrice.each(function () {
		monthlyFee = accAdd(monthlyFee ,this.value ,2);
	});
	$("#monthlyFee_v").val(monthlyFee).trigger('blur'); //触发保存非千分位金额
}

//计算用车数量
function calUseCarAmount() {
	var $useCarAmount = $("#suppInfo").yxeditgrid("getCmpByCol" ,"useCarAmount");
	var carNum = 0;
	$useCarAmount.each(function () {
		carNum = accAdd(carNum ,this.value ,2);
	});
	$("#useCarAmount").val(carNum);
}

//检验总费用的有效性
function checkAllFee() {
	if (accSub($("#projectBudget_v").val() ,$('#allFee_v').val()) < 0) {
		alert('总费用不能超过预决算！');
		$('#allFee_v').val($("#projectBudget_v").val()).focus();
		return false;
	}
	return true;
}

//检验租车时间
function checkExpectDate(obj){
    var startDate=$("#expectStartDate").val();
    var endDate=$("#expectEndDate").val();
    if(startDate!=""&&endDate!=""){
        if(startDate>endDate){
            alert("预计开始用车时间不能大于预计用车结束时间");
            $(obj).val("");
            $("#useCycle").val("");
            return false;
        }
        var useCycle = daysBetween(endDate, startDate)+1;
        $("#useCycle").val(useCycle+"天");
    }
}

//提交检验
function checkData(act) {
	if ($("#province").val() == 43) {
		if ($("#usePlace").val() == 'CDMA团队请手工输入省份、城市') {
			alert('CDMA团队请手工输入省份、城市');
			return false;
		}
	} else {
		$("#usePlace").val('');
	}

	// 检查预算 PMS 766
	if(act == "audit" && Number($("#estimateAmonut").val()) > Number($("#rentalcarRestbudget").val())){
		alert("本次申请已超出项目预算，不允许提交审批！请调低本次申请预算，或者执行项目预算变更，增加租车总预算。");
		return false;
	}

	// if(act == 'audit' && Number($("#estimateAmonut").val()) <= 0){
	// 	alert("预估租车合同总额必须大于0。");
	// 	return false;
	// }

    var $file = $("#suppInfo").yxeditgrid("getCmpByCol" ,"file");
    for(i=0;i<$file.size();i++){
       if($("#suppInfo_file_ufl_"+ i).html()==""||$("#suppInfo_file_ufl_"+ i).html()=="暂无任何附件"){
           alert("请上传附件：如辆最初里程数的照片");
           return false;
           break;
       }
    }

//	if (!checkAllFee()) {
//		return false;
//	}

	return true;
}


var calBudgetVal = function(){
	var projectId = ($("#projectId").val() != undefined)? $("#projectId").val() : '';
	if(projectId != ''){
		var responseText = $.ajax({
			url : 'index1.php?model=outsourcing_vehicle_rentalcar&action=calBudgetVal',
			data : {projectId:projectId},
			type : "POST",
			async : false
		}).responseText;
		var dataArr = eval("(" + responseText + ")");
		// console.log(dataArr);
		var rentalcarAllbudgetShow = moneyFormat2(dataArr.rentalcarAllbudget);
		$("#rentalcarAllbudgetShow").val(rentalcarAllbudgetShow);
		$("#rentalcarAllbudget").val(dataArr.rentalcarAllbudget);

		var rentalcarRestbudgetShow = moneyFormat2(dataArr.rentalcarRestbudget);
		$("#rentalcarRestbudgetShow").val(rentalcarRestbudgetShow);
		$("#rentalcarRestbudget").val(dataArr.rentalcarRestbudget);
	}else{
		$("#rentalcarAllbudgetShow").val(moneyFormat2(0));
		$("#rentalcarAllbudget").val(0);
		$("#rentalcarRestbudgetShow").val(moneyFormat2(0));
		$("#rentalcarRestbudget").val(0);
	}
}