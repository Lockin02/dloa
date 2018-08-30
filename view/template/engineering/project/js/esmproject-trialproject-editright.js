/**
 * 获取省份数组
 */
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToProvince(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].value + "'>" + data[i].text
				+ "</option>");
	}
}

/**
 * 当省份改变时对，对esmproject[proCode]的title的值赋值给esmproject[proName]
 */
function setProName(){
	$('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//启动与结束关闭差验证
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s < 0) {
		alert("启动时间不能比关闭时间晚！");
		$t.value = "";
		return false;
	}
}

//计算项目总预算
function countBudgetAll(){
	var budgetAll = accAdd($("#budgetField").val(),$("#budgetOther").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetOutsourcing").val(),2);

	setMoney('budgetAll',budgetAll);

	//计算费用进度
	countProcess();
}

//计算项目总费用
function countFeeAll(){
	var feeAll = accAdd($("#feeField").val(),$("#feeOther").val(),2);
	feeAll = accAdd(feeAll,$("#feeOutsourcing").val(),2);

	setMoney('feeAll',feeAll);
	//计算费用进度
	countProcess();
}

//计算费用进度
function countProcess(){
	//获取预算
	var budgetAll = $("#budgetAll").val();
	if(budgetAll == 0){
		$("#feeAllProcess").val(0);
	}else{
		var feeAllProcess = accMul(accDiv($("#feeField").val(),budgetAll,4),100,2);
		$("#feeAllProcess").val(feeAllProcess);
	}

	//获取预算
	var budgetField = $("#budgetField").val();
	if(budgetField == 0){
		$("#feeFieldProcess").val(0);
	}else{
		var feeFieldProcess = accMul(accDiv($("#feeField").val(),budgetField,4),100,2);
		$("#feeFieldProcess").val(feeFieldProcess);
	}
}



$(document).ready(function(){

	//获取省份数组并赋值给provinceArr
	provinceArr = getProvince();

	//把省份数组provinceArr赋值给proCode
	addDataToProvince(provinceArr,'proCode');

	//单选所属部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//单选项目经理
	$("#managerName").yxselect_user({
			hiddenId : 'managerId',
			isGetDept : [true, "deptId", "deptName"]
		});

	/**
	 * 验证信息
	 */
	validate({
		"projectName" : {
			required : true,
			length : [0,100]
		}
//		,
//		"customerName" : {
//			required : true,
//			length : [0,100]
//		},
//		"officeName" : {
//			required : true,
//			length : [0,20]
//		},
//		"managerName" : {
//			required : true
//		},
//		"deptName" : {
//			required : true
//		},
//		"planBeginDate" : {
//			custom : ['date']
//		},
//		"planEndDate" : {
//			custom : ['date']
//		},
//		"peopleNumber" : {
//			required : false,
//			custom : ['onlyNumber']
//		},
//		"budgetAll_v" : {
//			required : true
//		},
//		"budgetField_v" : {
//			required : true
//		},
//		"budgetOther_v" : {
//			required : true
//		},
//		"workRate" : {
//			required : true,
//			custom : ['percentage']
//		}
	});

	$.each($("input:text"),function(){
		if($(this).val() == '0000-00-00'){
			$(this).val('');
		}
	});
});