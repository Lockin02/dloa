$(document).ready(function() {
	//��ʼ������ѡ��
	getInitData($("#dateCodeHidden").val());

	//����֤
	validate({
		"configName" : {
			required : true
		},
		"dateCode" : {
			required : true
		},
		"days" : {
			required : true
		}
	});

	//��ʼ���������
	var isNeedDate = $("#isNeedDate").val();
	if(isNeedDate == "1"){
		$("#isNeedDateY").attr("checked",true);
	}else{
		$("#isNeedDateN").attr("checked",true);
	}

	//��ʼ��һ���������
	changeNeedDate($("#isNeedDate").val());

	//��ʼ���Ƿ��ѡ���Ȱٷֱ�
	var dateCode = $("#dateCodeHidden").val();
	if(dateCode == 'schePercentage'){
		$("#schePctShowTr").show();
	}
	var schePct = $("#schePct").val();
	if(schePct == "1"){
		$("#schePctY").attr("checked",true);
	}else{
		$("#schePctN").attr("checked",true);
	}

});