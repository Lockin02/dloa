$(document).ready(function() {
	$( 'textarea.editor' ).ckeditor();

	//抄送人员选择
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check',
		formCode : 'intUseManager'

	});

	//密送人员选择
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	});

	//协助人员选择
	$("#assistManName").yxselect_user({
		hiddenId : 'assistManId'
	});

	validate({
		"setdTitle" : {
			required : true
		}
	});

	if ($("#useHireType").val() == 'HRLYXX-03') { //实习生
		$("#internshipSalaryType").parent().show().prev().show(); //实习工资
		$('#internship').show();
		$("#useTrialWage").parent().hide().prev().hide(); //试用期基本工资
		$("#useFormalWage").parent().hide().prev().hide(); //转正基本工资
		$("#phoneSubsidy").parent().parent().hide(); //电话费补助
	} else if ($("#postType").val() == 'YPZW-WY') { //网优
		var personLevel = $.ajax({
			url : '?model=hr_basicinfo_level&action=ajaxGetName',
			type : 'post',
			data : {
				id : $("#positionLevel").val()
			},
			async : false
		}).responseText;
		$('#controlPost').parent().show().prev().show(); // 管理岗位
		if (personLevel.indexOf('A') == 0 || !isNaN(personLevel.substring(0 ,1))) {
			$("#tripSubsidy").parent().show().prev().show(); //出差补助上限值
			$("#subsidyTr2").show();
			$("#subsidyTr3").show();
			$("#otherSubsidy").parent().show().prev().show(); //其他补助
			$("#computerSubsidy").parent().show().prev().show(); //电脑补助
		} else if (personLevel.indexOf('B') == 0) {
			$("#subsidyTr").show();
			$("#workBonus").parent().show().prev().show(); //工作奖金
			$("#subsidyTr3").show();
			$("#computerSubsidy").parent().show().prev().show(); //电脑补助
		}
	} else { // 普通薪资模板
		$("#mealCarSubsidy").parent().show().prev().show(); // 餐车补
	}
});

function addmail(name) {
	if(name == 'ccmail') {
		var nameCol = "抄送";
	} else {
		var nameCol = "密送";
	}
	var temp = document.getElementById(name);
	if (temp.style.display == ''){
		temp.style.display = "none";
		$("#to" + name).val("");

		$("#btn" + name).val("添加" + nameCol);
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		$("#btn" + name).val("删除" + nameCol);
	}
}

function checkForm() {
	if($.trim($('#remark').val())==''){
		alert('邮件内容不能为空！');
		return false;
	}
	return true;
}

function save(){
	document.getElementById('form1').action="?model=hr_recruitment_entryNotice&action=add&isSave=1";
}