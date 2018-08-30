$(function(){
	//设置日期类型
	setSelect("dateType");
	
	//初始化公司
	var responseText = $.ajax({
		url:'index1.php?model=deptuser_branch_branch&action=listJson&sort=ComCard&dir=ASC',
		type : "POST",
		async : false
	}).responseText;
	var branchArr = eval("(" + responseText + ")");

	for (var i = 0, l = branchArr.length; i < l; i++) {
		$("#company").append("<option title='" + branchArr[i].NameCN
			+ "' value='" + branchArr[i].NamePT + "'>" + branchArr[i].NameCN
			+ "</option>");
	}

	//设置公司
	setSelect("company");
	
	//初始化行政区域
	var responseText = $.ajax({
		url:'index1.php?model=asset_assetcard_assetcard&action=getAgency',
		type : "POST",
		async : false
	}).responseText;
	var agencyArr = eval("(" + responseText + ")");
	if(agencyArr != null){//存在区域权限
		for (var i = 0, l = agencyArr.length; i < l; i++) {
			$("#agencyCode").append("<option title='" + agencyArr[i].agencyName
				+ "' value='" + agencyArr[i].agencyCode + "'>" + agencyArr[i].agencyName
				+ "</option>");
		}
		//设置行政区域
		setSelect("agencyCode");
	}else{//不存在区域权限
		$("#agencyCode").addClass("readOnlyTxtItem").attr("disabled","disabled");
	}
	
	//处理部门权限
	var deptIdStr = $("#deptIdStr").val();
	if(deptIdStr != ""){
		if(deptIdStr.indexOf(';;') != -1){//所有部门权限
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        mode: 'no' // 选择模式 :single 单选 check 多选
			});
		}else{//部分部门权限
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        deptFilter : deptIdStr, // 部门限制，传入部门Id串，以逗号隔开
		        mode: 'no' // 选择模式 :single 单选 check 多选
			});
		}
	}else{
		$("#deptName").removeClass("txt").addClass("readOnlyTxtItem");
	}
});

//查询
function search(){
	location.href = "?model=asset_report_assetreport&action=toMonthDetail"
	+ "&dateType=" + $("#dateType").val()
	+ "&year=" + $("#year").val()
	+ "&month=" + $("#month").val()
	+ "&company=" + $("#company").val()
	+ "&deptId=" + $("#deptId").val()
	+ "&deptName=" + $("#deptName").val()
	+ "&agencyCode=" + $("#agencyCode").val();
}