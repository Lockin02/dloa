$(document).ready(function() {
	//身份证唯一性验证
	$("#identityCard").ajaxCheck({
		url : "?model=hr_personnel_personnel&action=checkRepeat",
		alertText : "* 该身份证号已存在档案",
		alertTextOk : "* OK"
	});

	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetJob : [true, "jobId", "jobName"],
		isGetDept: [true, "belongDeptId", "belongDeptName"],
		event : {
			select : function(e,row) {
				$("#staffName").val(row.name);
				$.ajax({
					type : "POST",
					url : "?model=hr_personnel_personnel&action=isAddPersonnel",
					data : {
						"userNo" : row.userNo
					},
					async : true,
					success : function(data) {
						if(data == 0) {
							$("#userName").val("");
							$("#userAccount").val("");
							$("#userNo").val("");
							$("#staffName").val('');
							alert("该员工档案已生成！")
						} else {
							$("#staffName").val($("#userName").val());
							$.ajax({
								type: "POST",
								url : "?model=deptuser_user_user&action=ajaxGetUserInfo",
								data : {
									"userId" : $("#userAccount").val()
								},
								success : function(data){
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
										if( $(this).val() == obj1.type){
											$(this).attr("selected","selected");
										}
									});
									//公司选择绑定事件
									$.ajax({
										type: "POST",
										url: "?model=deptuser_branch_branch&action=getBranchStr",
										data: {
											"type" :obj1.type
										},
										async: true,
										success: function(data) {
											if(data != "") {
												$("#companyName").html("");
												$("#companyName").html(data);
												//公司名
												$('select[name="personnel[companyName]"] option').each(function() {
													if( $(this).val() ==obj1.NameCN){
														$(this).attr("selected","selected");
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
									data : {"deptId" : belongDeptId},
									success : function(dataLevel) {
										$.ajax({
											type: "POST",
											url: "?model=deptuser_dept_dept&action=getDeptInfo",
											data: {"deptId" : belongDeptId,"levelflag":dataLevel},
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
								});
							}
						}
					}
				});
			}
		}
	});

/***** 工程部扩展信息 ******/

	//职位选择
	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width:350
	});

	$("#schemeName").yxcombogrid_hrscheme({
		hiddenId : 'schemeId',
		width:450
	});

	//根据公司类型获取公司数据：1集团，0子公司
	$.ajax({
		type: "POST",
		url: "?model=deptuser_branch_branch&action=getBranchStr",
		data: {"type" :1},
		async: true,
		success: function(data){
			if(data != "") {
				$("#companyName").html(data);
			}
		}
	});

});