$(document).ready(function() {
	$( 'textarea.editor' ).ckeditor();

	//������Աѡ��
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check',
		formCode : 'intUseManager'

	});

	//������Աѡ��
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	});

	//Э����Աѡ��
	$("#assistManName").yxselect_user({
		hiddenId : 'assistManId'
	});

	validate({
		"setdTitle" : {
			required : true
		}
	});

	if ($("#useHireType").val() == 'HRLYXX-03') { //ʵϰ��
		$("#internshipSalaryType").parent().show().prev().show(); //ʵϰ����
		$('#internship').show();
		$("#useTrialWage").parent().hide().prev().hide(); //�����ڻ�������
		$("#useFormalWage").parent().hide().prev().hide(); //ת����������
		$("#phoneSubsidy").parent().parent().hide(); //�绰�Ѳ���
	} else if ($("#postType").val() == 'YPZW-WY') { //����
		var personLevel = $.ajax({
			url : '?model=hr_basicinfo_level&action=ajaxGetName',
			type : 'post',
			data : {
				id : $("#positionLevel").val()
			},
			async : false
		}).responseText;
		$('#controlPost').parent().show().prev().show(); // �����λ
		if (personLevel.indexOf('A') == 0 || !isNaN(personLevel.substring(0 ,1))) {
			$("#tripSubsidy").parent().show().prev().show(); //���������ֵ
			$("#subsidyTr2").show();
			$("#subsidyTr3").show();
			$("#otherSubsidy").parent().show().prev().show(); //��������
			$("#computerSubsidy").parent().show().prev().show(); //���Բ���
		} else if (personLevel.indexOf('B') == 0) {
			$("#subsidyTr").show();
			$("#workBonus").parent().show().prev().show(); //��������
			$("#subsidyTr3").show();
			$("#computerSubsidy").parent().show().prev().show(); //���Բ���
		}
	} else { // ��ͨн��ģ��
		$("#mealCarSubsidy").parent().show().prev().show(); // �ͳ���
	}
});

function addmail(name) {
	if(name == 'ccmail') {
		var nameCol = "����";
	} else {
		var nameCol = "����";
	}
	var temp = document.getElementById(name);
	if (temp.style.display == ''){
		temp.style.display = "none";
		$("#to" + name).val("");

		$("#btn" + name).val("���" + nameCol);
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		$("#btn" + name).val("ɾ��" + nameCol);
	}
}

function checkForm() {
	if($.trim($('#remark').val())==''){
		alert('�ʼ����ݲ���Ϊ�գ�');
		return false;
	}
	return true;
}

function save(){
	document.getElementById('form1').action="?model=hr_recruitment_entryNotice&action=add&isSave=1";
}