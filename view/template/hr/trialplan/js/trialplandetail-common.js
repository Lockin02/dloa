//������������
function setBeforeTask(taskId){
	var taskName = $("#beforeId").find("option:selected").text();
	$("#beforeName").val(taskName);
}

//��ֵ�������
function setScore(thisScore){
	var isRule = $("#isRule").val();
	if(isRule){

	}else{
		//���ַ�Χ��ֵ
		$("#ruleInfo").html( "0 ~ " + thisScore);

	}
	//�ƻ����ֲ��ּ���
	var planScoreAllObj = $("#planScoreAll");
	//�����������������񼸷�
	var planScoreOther = $("#planScoreOther").val();

	//������뱾�������ܻ���
	var allScore = accAdd(thisScore,planScoreOther,2);
	planScoreAllObj.val(allScore);
}


//���ֹ����趨����
function setRule(){
	// ��ȡisRule
	var isRuleObj = $("#isRule");
	// ��ȡ��������
	var taskName = $("#taskName").val();

	// ����
	url = "?model=hr_trialplan_trialplandetailex&action=toSetRule&id=" + isRuleObj.val()
		+ "&taskName="
		+ taskName
	;

	//Ϊ�˽��GOOGLE �������BUG������Ҫʹ�����´���
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue//��ֵ

	if(dlgReturnValue){
		isRuleVal = eval("(" + dlgReturnValue + ")");
		isRuleObj.val(isRuleVal.isRule);
		//���ַ�Χ��ֵ
		$("#ruleInfo").html( isRuleVal.lowerLimit + " ~ " +  isRuleVal.upperLimit);
	}
}

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
//�鿴���ֹ���
function viewRule(){
	showRule($("#isRule").val());
}
//
function showRule(isRule){
	// ����
	var url = "?model=hr_trialplan_trialplandetailex&action=toViewRule&id=" + isRule;
	showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
}
