$(document).ready(function() {
	//���֤Ψһ����֤
	$("#identityCard").ajaxCheck({
		url : "?model=hr_personnel_personnel&action=checkRepeat",
		alertText : "* �����֤���Ѵ��ڵ���",
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
							alert("��Ա�����������ɣ�")
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
										if( $(this).val() == obj1.type){
											$(this).attr("selected","selected");
										}
									});
									//��˾ѡ����¼�
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
												//��˾��
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

/***** ���̲���չ��Ϣ ******/

	//ְλѡ��
	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width:350
	});

	$("#schemeName").yxcombogrid_hrscheme({
		hiddenId : 'schemeId',
		width:450
	});

	//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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