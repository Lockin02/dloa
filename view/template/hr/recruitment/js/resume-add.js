$(function() {
	$("#sourceB").yxcombogrid_resumeSource({
		hiddenId : 'sourceB',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	// ӦƸְλ
	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	// ������Դ
	JLLYArr = getData('JLLY');
	addDataToSelect(JLLYArr, 'sourceA');
	// ����
	WYSPArr = getData('HRYZ');
	addDataToSelect(WYSPArr, 'language');
	// ����ˮƽ
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	// �����ˮƷ
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');
	//ѧ��
	HRJYXLArr = getData('HRJYXL');
	addDataToSelect(HRJYXLArr, 'education');

	//ajax��֤�Ƿ���ڼ���
	$("#applicantName").blur(function () {
		checkExist($(this).val() ,$("#email").val());
	});
	$("#email").blur(function () {
		checkExist($("#applicantName").val() ,$(this).val());
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"applicantName" : {
			required : true
		},
		"phone" : {
			required : true,
			custom : ['mobilephone']
		},
		"email" : {
			required : true,
			custom : ['email']
		},
		"workSeniority" : {
			required : true
		},
		"education" : {
			required : true
		},
		"post" : {
			required : true
		},
		"selfAssessment" : {
			required : true
		},
		"graduateDate" : {
			required : true
		},
		"reserveA" : {
			required : true
		}
	});
})

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
	if(s < 1){
		$("#workSeniority").val("0");
	} else {
		$("#workSeniority").val(s);
	}
}

function sub() {
	var sourceA = $("#sourceA").val();
	var sourceB = $("#sourceB").val();
	if (sourceA == '' && sourceB == '') {
		alert("����дӦƸ����!");
		return false;
	}
	var isExist = $.ajax({
					url : "?model=hr_recruitment_resume&action=checkRepeat",
					type : "POST",
					data : {
						applicantName : $("#applicantName").val(),
						email : $("#email").val()
					},
					async: false
				}).responseText;
	if (isExist == 'false') {
		alert('�ü����Ѵ��ڣ�');
		return false;
	}

	return true;
}

//���������������ж��Ƿ��Ѿ����ڼ���
function checkExist(name ,email) {
	if ($.trim(name) != '' && $.trim(email) != '') {
		$.ajax({
			url : "?model=hr_recruitment_resume&action=checkRepeat",
			type : "POST",
			data : {
				applicantName : name,
				email : email
			},
			success : function (data) {
				if (data == 'false') {
					alert('�ü����Ѵ��ڣ�');
				}
			}
		});
	}
}