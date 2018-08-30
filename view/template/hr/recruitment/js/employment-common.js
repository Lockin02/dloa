$(document).ready(function() {
	//�Ա�
	$('select[name="employment[sex]"] option').each(function() {
		if( $(this).val() == $("#sex").val() ){
			$(this).attr("selected","selected'");
		}
	});
	//����״��
	$('select[name="employment[maritalStatusName]"] option').each(function() {
		if( $(this).val() == $("#maritalStatusName").val() ){
			$(this).attr("selected","selected'");
		}
	});
	//����״��
	$('select[name="employment[birthStatus]"] option').each(function() {
		if( $(this).val() == $("#birthStatus").val() ){
			$(this).attr("selected","selected'");
		}
	});
	//��������
	$('select[name="employment[householdType]"] option').each(function() {
		if( $(this).val() == $("#householdType").val() ){
			$(this).attr("selected","selected'");
		}
	});
	//���廧��
	$('select[name="employment[collectResidence]"] option').each(function() {
		if( $(this).val() == $("#collectResidence").val() ){
			$(this).attr("selected","selected'");
		}
	});
	//�Ƿ��й�����ʷ
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	}else{
		$("#medicalHistory").hide();
	}

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"name" : {
			required : true
		},
		"birthdate" : {
			required : true
		},
		"province5" : {
			required : true
		},
		"cityName5" : {
			required : true
		},
		"appointAddress" : {
			required : true
		},
		"appointPost" : {
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
		"mobile" : {
			required : true
		},
		"personEmail" : {
			required : true
		},
		"highEducation" : {
			required : true
		},
		"highSchool" : {
			required : true
		},
		"professionalName" : {
			required : true
		},
		"graduateDate" : {
			required : true
		},
		"workSeniority" : {
			required : true
		},
		"marital" : {
			required : true
		},
		"post" : {
			required : true
		},
		"reserveA" : {
			required : true
		},
		"wishSalary" : {
			required : true
		},
		"prevCompany" : {
			required : true
		},
		"hillockDate" : {
			required : true
		},
		"emergencyName" : {
			required : true
		},
		"emergencyTel" : {
			required : true
		},
		"nation" : {
			required : true
		},
		"birthStatus" : {
			required : true
		},
		"province2" : {
			required : true
		},
		"city2" : {
			required : true
		},
		"householdName" : {
			required : true
		}
	});
});

//�Ƿ��й�����ʷ
function changeRadio() {
	if ($("#isYes").attr("checked")) {
		$("#medicalHistory").show();
	} else {
		$("#medicalHistory").hide();
		$("#medicalHistory").val("");
	}
}

//��������
function getAge() {
	var str = $("#birthdate").val();
	var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if (r == null)
		return false;
	var d = new Date(r[1], r[3] - 1, r[4]);
	if (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3]
			&& d.getDate() == r[4]) {
		var Y = new Date().getFullYear();
		$("#age").val(Y - r[1]);
	}
}

//���㹤������
function Calculate(obj){
	$("#workSeniority").val("");
	var graduateDate = obj.value;
	var dates = graduateDate.split("-");
	var graduateYear = parseInt(dates[0]);
	var graduateMon = parseInt(dates[1]);
	var nowDate = new Date();
	var nowYear = nowDate.getFullYear();
	var nowMon = nowDate.getMonth() + 1;

	//����ʱ��õ���������
	var s = parseInt(((nowYear - graduateYear) * 12 + nowMon - graduateMon)/12);
	if(s < 1) {
		$("#workSeniority").val("0");
	}else {
		$("#workSeniority").val(s);
	}
}

// ������֤
function checkmail(obj) {
	mail = $(obj).val();
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(mail)) {
		return true;
	} else {
		alert('��������ȷ������!');
		$(obj).val("");
		return false;
	}
}

//�ر�˵��
function specialVersion() {
	showThickboxWin("?model=hr_recruitment_employment&action=specialVersion"
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900")
}

//���֤��Ч����
function dealCardDate() {
	var startDate = $("#identityCardDate0").val();
	var stopDate = $("#identityCardDate1").val();
	if (startDate && stopDate) {
		$("#identityCardDate").val(startDate + '-' + stopDate);
	}
}

//���ݼ���
function checkForm(){
	var workSeniority = $("#workSeniority").val();
	if(workSeniority > 0) {
		var cmps = $("#work").yxeditgrid("getCmpByCol", "company");
		var num = 0;
		cmps.each(function() {
			if($(this).val() != '') {
				num++;
				return false; //�˳�ѭ��
			}
		});

		if(num == 0) {
			alert('��������дһ��ʵϰ/����������');
			return false;
		}
	}

	if($("#uploadfileList2").html() == "" || $("#uploadfileList").html() == "�����κθ���") {
		alert('���ϴ�����������');
		return false;
	}

	return true;
}