$(document).ready(function() {

	$("#belongDeptName").yxselect_dept({
		hiddenId : 'belongDeptId',
		event : {
			selectReturn : function(e,row){
				$("#jobName").val("");
				$("#jobId").val("");
				$("#jobName").yxcombogrid_position("remove");
				//职位选择
				$("#jobName").yxcombogrid_position({
					hiddenId : 'jobId',
					isShowButton : false,
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id}
					}
				});
				$("#jobName").yxcombogrid_position("show");
				$("#belongDeptCode").val(row.dept.Depart_x);
				$.ajax({
					type: "POST",
					url: "?model=deptuser_dept_dept&action=getDeptInfo",
					data: {"deptId" : row.dept.id,"levelflag":row.dept.levelflag},
					success: function(data){
					var obj = eval("(" + data + ")");
						//直属部门
						$("#deptId").val(obj.deptId);
						$("#deptName").val(obj.deptName);
						$("#deptCode").val(obj.deptCode);
						//二级部门
						$("#deptIdS").val(obj.deptIdS);
						$("#deptNameS").val(obj.deptNameS);
						$("#deptCodeS").val(obj.deptCodeS);
						//三级部门
						$("#deptIdT").val(obj.deptIdT);
						$("#deptNameT").val(obj.deptNameT);
						$("#deptCodeT").val(obj.deptCodeT);
                        //四级部门
                        $("#deptIdF").val(obj.deptIdF);
                        $("#deptNameF").val(obj.deptNameF);
                        $("#deptCodeF").val(obj.deptCodeF);
					}
				});
			}
		}
	});

/***** 工程部扩展信息 ******/
	//单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//单选人员等级
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId : 'personLevelId',
		width:400,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350
	});

	//公司选择绑定事件
	$("#companyTypeCode").bind('change', function() {
		var companyType = $(this).val();
		if($(this).val()!=="") {
			//根据公司类型获取公司数据：1集团，0子公司
			$.ajax({
				type : "POST",
				url : "?model=deptuser_branch_branch&action=getBranchStr",
				data : {
					"type" :companyType
				},
				async : true,
				success : function(data){
					if(data != "") {
						$("#companyName").html("");
						$("#companyName").html(data);
					}
				}
			});
		}
	});

	//员工状态选择绑定事件
	$("#employeesState").bind('change', function() {
		$("#staffState").empty();
		if($(this).val() == "YGZTZZ"){
			GongArr = getData('YGZTZZ');
			addDataToSelect(GongArr, 'staffState');
		}else if($(this).val() == "YGZTLZ"){
			GongArr = getData('YGZTLZ');
			addDataToSelect(GongArr, 'staffState');
		}
	});

	validate({
		"staffName" : {
			required : true
		},
		"birthdate" : {
			required : true
		},
		"identityCardDate0" : {
			required : true
		},
		"identityCardDate1" : {
			required : true
		},
		"identityCardAddress" : {
			required : true
		},
		"companyName" : {
			required : true
		},
		"belongDeptName" : {
			required : true
		},
		"jobName" : {
			required : true
		},
		"postType" : {
			required : true
		},
		"entryDate" : {
			required : true
		},
		"becomeDate" : {
			required : true
		}
	});

});

//是否有过往病史
function changeRadio(){
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	}else{
		$("#medicalHistory").hide();
	}
}

//是否配置试用转正方案
function changeSchem(){
	if($("#isAddYes").attr("checked")){
		$("#schemeName").yxcombogrid_hrscheme("remove");
		$("#schemeName").show();
		$("#schemeName").yxcombogrid_hrscheme({
			hiddenId : 'schemeId',
            isFocusoutCheck: false,
			width:450
		});
		$("#schemeName").yxcombogrid_hrscheme("show");
	}else{
		$("#schemeName").yxcombogrid_hrscheme("remove");
		$("#schemeName").val("");
		$("#schemeId").val("");
		$("#schemeName").hide();
	}
}

//计算年龄
function getAge() {
	var str = $("#birthdate").val();
	var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if(r == null) {
		return false;
	}
	var d = new Date(r[1] ,r[3] - 1 ,r[4]);
	if (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]) {
		var Y = new Date().getFullYear();
		$("#age").val(Y - r[1]);
	}
}

function checkIDCard (obj) {
	str = $(obj).val();
	if(isIdCardNo(str)) {
		//截取出生年月信息
		var birthDay = str.substring(6, 10) + "-" + str.substring(10, 12) + "-" + str.substring(12, 14);
		$("#birthdate").val(birthDay);
		//计算年龄
		getAge();
	} else {
		$(obj).val('');
	}
}

//身份证有效日期
function dealCardDate() {
	var startDate = $("#identityCardDate0").val();
	var stopDate = $("#identityCardDate1").val();
	if (startDate && stopDate) {
		$("#identityCardDate").val(startDate + '-' + stopDate);
	}
}