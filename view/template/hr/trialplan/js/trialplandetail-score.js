$(document).ready(function() {
	validate({
		"score" : {
			required : true
		}
	});

	//���ֹ����ʼ��
	var isRule = $("#isRule").val();
	if(isRule != '0' &&isRule!= ""){
		$("#ruleInfo").after(" <a href='javascript:void(0)' onclick='showRule(\""+isRule+"\")'>���ֹ���</a>");
	}
})

//���ּ���
function countTaskScore(){
	var baseScore;//�÷�
	var score = $("#score").val();//��������
	var isRule = $("#isRule").val();//���ֹ���

	//���Ʒ���
	if(score == ""){
		return false;
	}

	//������ڹ������ȡ���֣�����ֱ�ӷ��ػ���
	if(isRule != '0' &&isRule!= ""){
		$.ajax({
		    type: "POST",
		    url: "?model=hr_trialplan_trialplandetailex&action=calScore",
		    data: {"ids" : isRule , 'score' : score},
		    async: false,
		    success: function(data){
	    		baseScore = data;
			}
		});
	}else{
		if($("#isRule").val() == 0){
			baseScore = score;
		}
	}
	$("#baseScore").val(baseScore);
}

//
function showRule(isRule){
	// ����
	var url = "?model=hr_trialplan_trialplandetailex&action=toViewRule&id=" + isRule;
	showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
}