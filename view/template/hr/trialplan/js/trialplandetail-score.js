$(document).ready(function() {
	validate({
		"score" : {
			required : true
		}
	});

	//积分规则初始化
	var isRule = $("#isRule").val();
	if(isRule != '0' &&isRule!= ""){
		$("#ruleInfo").after(" <a href='javascript:void(0)' onclick='showRule(\""+isRule+"\")'>积分规则</a>");
	}
})

//积分计算
function countTaskScore(){
	var baseScore;//得分
	var score = $("#score").val();//本次评分
	var isRule = $("#isRule").val();//积分规则

	//控制返回
	if(score == ""){
		return false;
	}

	//如果存在规则，则读取积分，否则直接返回积分
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
	// 弹窗
	var url = "?model=hr_trialplan_trialplandetailex&action=toViewRule&id=" + isRule;
	showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
}