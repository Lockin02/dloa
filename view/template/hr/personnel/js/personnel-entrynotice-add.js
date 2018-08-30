$(document).ready(function() {
	//���֤��Ч����
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
				//ְλѡ��
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
						//ֱ������
						$("#deptId").val(obj.deptId);
						$("#deptName").val(obj.deptName);
						$("#deptCode").val(obj.deptCode);
						//��������
						$("#deptIdS").val(obj.deptIdS);
						$("#deptNameS").val(obj.deptNameS);
						$("#deptCodeS").val(obj.deptCodeS);
						//��������
						$("#deptIdT").val(obj.deptIdT);
						$("#deptNameT").val(obj.deptNameT);
						$("#deptCodeT").val(obj.deptCodeT);
                        //�ļ�����
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
							alert("��Ա�����������ɣ�")
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
									//����
									$('select[name="personnel[regionId]"] option').each(function() {
										if( $(this).val() ==uobj.AREA){
											$(this).attr("selected" ,"selected");
										}
									});
									//�Ա�
									if(uobj.SEX==1){
										var userSex='Ů';
									}else{
										var userSex='��';
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
									//��˾����
									$('select[name="personnel[companyTypeCode]"] option').each(function() {
										if( $(this).val() ==obj1.type){
											$(this).attr("selected" ,"selected");
										}
									});

									//��˾ѡ����¼�
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
												//��˾��
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
												//ֱ������
												$("#deptId").val(obj.deptId);
												$("#deptName").val(obj.deptName);
												$("#deptCode").val(obj.deptCode);
												//��������
												$("#deptIdS").val(obj.deptIdS);
												$("#deptNameS").val(obj.deptNameS);
												$("#deptCodeS").val(obj.deptCodeS);
												//��������
												$("#deptIdT").val(obj.deptIdT);
												$("#deptNameT").val(obj.deptNameT);
												$("#deptCodeT").val(obj.deptCodeT);
                                                //�ļ�����
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

	//ְλѡ��
	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width:350
	});

	//��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId : 'personLevelId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//�Ա�
	$('select[name="personnel[sex]"] option').each(function() {
		if( $(this).val() == $("#sex").val() ){
			$(this).attr("selected" ,"selected");
		}
	});

	//��˾
	$('select[name="personnel[companyTypeCode]"] option').each(function() {
		if ($("#companyType").val() == '����') {
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

	//����״��
	$('select[name="personnel[maritalStatusName]"] option').each(function() {
		if( $(this).val() == $("#maritalStatusName").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//����״��
	$('select[name="personnel[birthStatus]"] option').each(function() {
		if( $(this).val() == $("#birthStatus").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//��������
	$('select[name="personnel[householdType]"] option').each(function() {
		if( $(this).val() == $("#householdType").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//���廧��
	$('select[name="personnel[collectResidence]"] option').each(function() {
		if( $(this).val() == $("#collectResidence").val() ){
			$(this).attr("selected" ,"selected");
		}
	});
	//����
	$('select[name="personnel[regionId]"] option').each(function() {
		if( $(this).val() == $("#regionIdSelect").val() ){
			$(this).attr("selected" ,"selected");
		}
	});

	//�Ƿ��й�����ʷ
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	} else {
		$("#medicalHistory").hide();
	}

	//Ա��״̬ѡ����¼�
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

	//��˾ѡ����¼�
	$("#companyTypeCode").bind('change', function() {
		var companyType = $(this).val();
		if($(this).val() !== "") {
			//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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
	if ($("#companyType").val() == '����') {
		companyTypeCode = 1;
	} else {
		companyTypeCode = 0;
	}

	//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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
		//��˾����
		$('select[name="personnel[companyTypeCode]"] option').each(function() {
			if( $(this).val() ==companyType){
				$(this).attr("selected" ,"selected");
			}
		});
		//��˾��
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

//�Ƿ��й�����ʷ
function changeRadio(){
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	}else{
		$("#medicalHistory").hide();
	}
}

//�Ƿ���������ת������
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

//��������
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
		//��ȡ����������Ϣ
		var birthDay=str.substring(6, 10) + "-" + str.substring(10, 12) + "-" + str.substring(12, 14);
		$("#birthdate").val(birthDay);
		//��������
		getAge();
	} else {
		$(obj).val('');
	}
}

/** ������֤ * */
function checkmail(obj) {
	mail= $(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail)) {
		return true;
	} else {
		alert('��������ȷ������!');
		$(obj).val("");
		return false;
	}
}

//���֤��Ч����
function dealCardDate() {
	var startDate = $("#identityCardDate0").val();
	var stopDate = $("#identityCardDate1").val();
	if (startDate && stopDate) {
		$("#identityCardDate").val(startDate + '-' + stopDate);
	}
}