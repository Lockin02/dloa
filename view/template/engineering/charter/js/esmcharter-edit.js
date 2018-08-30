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
 * 当省份改变时对，将esmproject[proCode]的title的值赋值给esmproject[proName]
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


$(document).ready(function(){
	//获取省份数组并赋值给provinceArr
	provinceArr = getProvince();

	//把省份数组provinceArr赋值给proCode
	addDataToProvince(provinceArr,'proCode');

	//单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	// 单选客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//单选部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//单选项目经理
	$("#managerName").yxselect_user({
			hiddenId : 'managerId'
	});

	// 验证信息
	validate({
		"projectCode" : {
			required : true,
			length : [0,100]
		},
		"projectName" : {
			required : true,
			length : [0,100]
		},
		"officeName" : {
			required : true,
			length : [0,20]
		},
		"deptName" : {
			required : true
		},
		"managerName" : {
			required : true,
			length : [0,20]
		},
		"planBeginDate" : {
			custom : ['date']
		},
		"planEndDate" : {
			custom : ['date']
		},
		"workRate" : {//工作占比
			//required : false,
			custom : ['percentage']
		},
		"description" : {
			required : false,
			length : [0,2000]
		},
		"remark" : {
			required : false,
			length : [0,2000]
		}
	});

	/**
	 * 编号唯一性验证
	 */
	var url = "?model=engineering_charter_esmcharter&action=checkRepeat";
	$("#projectCode").ajaxCheck({
		url : url,
		alertText : "* 该编号已存在",
		alertTextOk : "* 该编号可用"
	});
});