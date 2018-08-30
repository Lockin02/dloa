$(document).ready(function() {
	var investigationId = $("#investigationId").val();
	if(investigationId != 0) {
		$("#investigationLink").click(function(){
			showOpenWin("?model=hr_recruitment_investigation&action=toview&id=" +  investigationId,'1');
		});
	}else{
		document.getElementById("linkTr").style.display="none";
	}

	var resumeId = $("#resumeId").val();
	if(resumeId != 0) {
		$("#resumeLink").click(function(){
			showOpenWin("?model=hr_recruitment_resume&action=toview&id=" +  resumeId,'1');
		});
	}

	var applyId = $("#applyId").val();
	if(applyId != 0) {
		$("#applyLink").click(function(){
			showOpenWin("?model=hr_recruitment_employment&action=toview&id=" +  applyId,'1');
		});
	}

	//实习生
	var useHireType = $("#useHireType").val();
	//服务执行中心实习生
	// var deptId = $("#deptId").val();
	if (useHireType == 'HRLYXX-03') { // 实习生
		$("#wage").hide(); //工资
		$("#subsidy").hide(); //补助
		$("#useHireType").parent().show().prev().show().prev().hide().prev().hide(); //是否本地化
		$("#hrInterviewResultTr").hide(); //总体评价
		$("#contract").hide(); //合同
		$("#hrIsMatchTr :eq(0)").hide().next().hide(); //基本工资与薪点及薪资是否对应
		$("#phoneSubsidyTr :eq(0)").hide().next().hide(); //电话费补助
		$('#mealCarSubsidyTr').hide(); // 餐车补
		$('#internship').show();
		$("#phoneSubsidyTr").children().eq(2).show().next().show(); //实习期薪资总额
	} else if ($("#postType").val() == 'YPZW-WY') { //非实习生网优级别
		$('#subsidy7').show();
		$('#mealCarSubsidyTr').hide(); // 餐车补
		var positionLevel = $("#positionLevel").val();
		if (positionLevel.indexOf('A') == 0 || !isNaN(positionLevel.substring(0 ,1))) {
			$("#subsidy2").show();
			$("#subsidy3").show();
			$("#subsidy4").show();
			$("#subsidy6").show();
		} else if (positionLevel.indexOf('B') == 0) {
			$("#subsidy").show();
			$("#subsidy5").show();
			$("#subsidy6").show();
		}
	}

});