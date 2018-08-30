$(document).ready(function() {
	//身份证有效日期
	var identityCardDate = $("#identityCardDate").val();
	var carDate = identityCardDate.split("-");
	if (carDate.length > 1) {
		$("#identityCardDate0").val(carDate[0]);
		$("#identityCardDate1").val(carDate[1]);
	}

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

	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350
	});

	$("#schemeName").yxcombogrid_hrscheme({
		hiddenId : 'schemeId',
		width:450
	});

	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetJob : [true, "jobId", "jobName"],
		isGetDept: [true, "belongDeptId", "belongDeptName"],
		event : {
			select : function(e,row){
				$("#staffName").val(row.name);
				$.ajax({
					type : "POST",
					url : "?model=hr_personnel_personnel&action=isAddPersonnel",
					data : {
						"userNo" : row.userNo
					},
					async : true,
					success: function(data){
						if(data == 0) {
							$("#userName").val("");
							$("#userAccount").val("");
							$("#userNo").val("");
							$("#staffName").val('');
							alert("该员工档案已生成！")
						} else {
							$("#staffName").val($("#userName").val());
							$.ajax({
								type : "POST",
								url : "?model=deptuser_user_user&action=ajaxGetUserInfo",
								data : {
									"userId" : $("#userAccount").val()
								},
								success: function(data){
									var uobj = eval("(" + data + ")");
									//区域
									$('select[name="personnel[regionId]"] option').each(function() {
										if( $(this).val() ==uobj.AREA){
											$(this).attr("selected" ,"selected");
										}
									});
									//性别
									if(uobj.SEX==1){
										var userSex='女';
									}else{
										var userSex='男';
									}
									$('select[name="personnel[sex]"] option').each(function() {
										if( $(this).val() ==userSex){
											$(this).attr("selected" ,"selected");
										}
									});
								}
							});

							$.ajax({
								type : "POST",
								url : "?model=deptuser_branch_branch&action=getBrachInfo",
								data : {
									"userId" : $("#userAccount").val()
								},
								success : function(data){
									var obj1 = eval("(" + data + ")");
									//公司类型
									$('select[name="personnel[companyTypeCode]"] option').each(function() {
										if( $(this).val() ==obj1.type){
											$(this).attr("selected" ,"selected");
										}
									});

									//公司选择绑定事件
									$.ajax({
										type : "POST",
										url : "?model=deptuser_branch_branch&action=getBranchStr",
										data : {
											"type" : obj1.type
										},
										async : true,
										success: function(data){
											if(data != "") {
												$("#companyName").html("");
												$("#companyName").html(data);
												//公司名
												$('select[name="personnel[companyName]"] option').each(function() {
													if( $(this).val() == obj1.NameCN) {
														$(this).attr("selected" ,"selected'");
													}
												});
											}
										}
									});
								}
							});

							var belongDeptId = $('#belongDeptId').val();
							if(belongDeptId > 0) {
								$.ajax({
									type : "POST",
									url : "?model=deptuser_dept_dept&action=getDeptLevel",
									data : {
										"deptId" : belongDeptId
									},
									success : function(dataLevel){
										$.ajax({
											type : "POST",
											url : "?model=deptuser_dept_dept&action=getDeptInfo",
											data : {
												"deptId" : belongDeptId,"levelflag":dataLevel
											},
											success: function(data) {
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
								});
							}
						}
					}
				});
			}
		}
	});

	//职位选择
	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width:350
	});

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
		gridOptions : {
			showcheckbox : false
		}
	});

	//性别
	$('select[name="personnel[sex]"] option').each(function() {
		if( $(this).val() == $("#sex").val() ){
			$(this).attr("selected" ,"selected");
		}
	});

	//公司
	$('select[name="personnel[companyTypeCode]"] option').each(function() {
		if ($("#companyType").val() == '集团') {
			var companyTypeCode = 1;
		} else {
			var companyTypeCode = 0;
		}
		if( $(this).val() == companyTypeCode ){
			$(this).attr("selected" ,"selected");
		}
		$.ajax({
			type: "POST",
			url: "?model=deptuser_branch_branch&action=getBranchStr",
			data: {
				"type" : companyTypeCode
			},
			async: false,
			success: function(data){
				if(data != "") {
					$("#companyName").html(data);
				}
			}
		});
	});

	//婚姻状况
	$('select[name="personnel[maritalStatusName]"] option').each(function() {
		if( $(this).val() == $("#maritalStatusName").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//生育状况
	$('select[name="personnel[birthStatus]"] option').each(function() {
		if( $(this).val() == $("#birthStatus").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//户籍类型
	$('select[name="personnel[householdType]"] option').each(function() {
		if( $(this).val() == $("#householdType").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//集体户口
	$('select[name="personnel[collectResidence]"] option').each(function() {
		if( $(this).val() == $("#collectResidence").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//区域
	$('select[name="personnel[regionId]"] option').each(function() {
		if( $(this).val() == $("#regionIdSelect").val() ){
			$(this).attr("selected" ,"selected");
		}
	});

	//是否有过往病史
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	} else {
		$("#medicalHistory").hide();
	}

	//员工状态选择绑定事件
	$("#employeesState").bind('change', function() {
		$("#staffState").empty();
		if($(this).val()=="YGZTZZ"){
			GongArr = getData('YGZTZZ');
			addDataToSelect(GongArr, 'staffState');
		} else if ($(this).val()=="YGZTLZ"){
			GongArr = getData('YGZTLZ');
			addDataToSelect(GongArr, 'staffState');
		}
	});

	//公司选择绑定事件
	$("#companyTypeCode").bind('change', function() {
		var companyType = $(this).val();
		if($(this).val() !== "") {
			//根据公司类型获取公司数据：1集团，0子公司
			$.ajax({
				type : "POST",
				url : "?model=deptuser_branch_branch&action=getBranchStr",
				data : {
					"type" :companyType
				},
				async : false,
				success: function(data){
					if(data != ""){
						$("#companyName").html(data);
					}
				}
			});
		}
	});

	var companyType=$("#companyType").val();
	if ($("#companyType").val() == '集团') {
		companyTypeCode = 1;
	} else {
		companyTypeCode = 0;
	}

	//根据公司类型获取公司数据：1集团，0子公司
	$.ajax({
		type: "POST",
		url: "?model=deptuser_branch_branch&action=getBranchStr",
		data: {"type" :companyTypeCode},
		async: false,
		success: function(data){
			if(data != ""){
				$("#companyName").html(data);
			}
		}
	});

	if($("#companyType").val()!==""){
		//公司类型
		$('select[name="personnel[companyTypeCode]"] option').each(function() {
			if( $(this).val() ==companyType){
				$(this).attr("selected" ,"selected");
			}
		});
		//公司名
		$('select[name="personnel[companyName]"] option').each(function() {
			if( $(this).val() ==$("#company").val()){
				$(this).attr("selected" ,"selected");
			}
		});
	}

	validate({
		"staffName" : {
			required : true
		},
		"birthdate" : {
			required : true
		},
		"companyName" : {
			required : true
		},
		"identityCard" : {
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
		},
		"socialPlace" : {
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
			width:450
		});
		$("#schemeName").yxcombogrid_hrscheme("show");
	} else {
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
	if(r == null) return false;
	var d = new Date(r[1] ,r[3] - 1 ,r[4]);
	if (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]) {
		var Y = new Date().getFullYear();
		$("#age").val(Y - r[1]);
	}
}

function checkIDCard (obj) {
	str = $(obj).val();
	if(isIdCardNo(str)){
		//截取出生年月信息
		var birthDay=str.substring(6, 10) + "-" + str.substring(10, 12) + "-" + str.substring(12, 14);
		$("#birthdate").val(birthDay);
		//计算年龄
		getAge();
	} else {
		$(obj).val('');
	}
}

/** 邮箱验证 * */
function checkmail(obj) {
	mail= $(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail)) {
		return true;
	} else {
		alert('请输入正确的邮箱!');
		$(obj).val("");
		return false;
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