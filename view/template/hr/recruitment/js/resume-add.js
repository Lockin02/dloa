$(function() {
	$("#sourceB").yxcombogrid_resumeSource({
		hiddenId : 'sourceB',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	// 应聘职位
	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	// 简历来源
	JLLYArr = getData('JLLY');
	addDataToSelect(JLLYArr, 'sourceA');
	// 语种
	WYSPArr = getData('HRYZ');
	addDataToSelect(WYSPArr, 'language');
	// 外语水平
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	// 计算机水品
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');
	//学历
	HRJYXLArr = getData('HRJYXL');
	addDataToSelect(HRJYXLArr, 'education');

	//ajax验证是否存在简历
	$("#applicantName").blur(function () {
		checkExist($(this).val() ,$("#email").val());
	});
	$("#email").blur(function () {
		checkExist($("#applicantName").val() ,$(this).val());
	});

	/**
	 * 验证信息
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

	//计算时差得到工作年限
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
		alert("请填写应聘渠道!");
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
		alert('该简历已存在！');
		return false;
	}

	return true;
}

//根据姓名和邮箱判断是否已经存在简历
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
					alert('该简历已存在！');
				}
			}
		});
	}
}