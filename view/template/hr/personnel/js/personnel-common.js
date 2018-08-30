$(document).ready(function() {

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

/***** ���̲���չ��Ϣ ******/
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
		width:400,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350
	});

	//��˾ѡ����¼�
	$("#companyTypeCode").bind('change', function() {
		var companyType = $(this).val();
		if($(this).val()!=="") {
			//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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

	//Ա��״̬ѡ����¼�
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

//��������
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
		//��ȡ����������Ϣ
		var birthDay = str.substring(6, 10) + "-" + str.substring(10, 12) + "-" + str.substring(12, 14);
		$("#birthdate").val(birthDay);
		//��������
		getAge();
	} else {
		$(obj).val('');
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