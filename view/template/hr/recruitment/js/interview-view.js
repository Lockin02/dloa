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

	//ʵϰ��
	var useHireType = $("#useHireType").val();
	//����ִ������ʵϰ��
	// var deptId = $("#deptId").val();
	if (useHireType == 'HRLYXX-03') { // ʵϰ��
		$("#wage").hide(); //����
		$("#subsidy").hide(); //����
		$("#useHireType").parent().show().prev().show().prev().hide().prev().hide(); //�Ƿ񱾵ػ�
		$("#hrInterviewResultTr").hide(); //��������
		$("#contract").hide(); //��ͬ
		$("#hrIsMatchTr :eq(0)").hide().next().hide(); //����������н�㼰н���Ƿ��Ӧ
		$("#phoneSubsidyTr :eq(0)").hide().next().hide(); //�绰�Ѳ���
		$('#mealCarSubsidyTr').hide(); // �ͳ���
		$('#internship').show();
		$("#phoneSubsidyTr").children().eq(2).show().next().show(); //ʵϰ��н���ܶ�
	} else if ($("#postType").val() == 'YPZW-WY') { //��ʵϰ�����ż���
		$('#subsidy7').show();
		$('#mealCarSubsidyTr').hide(); // �ͳ���
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