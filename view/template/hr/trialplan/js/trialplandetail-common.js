//设置任务名称
function setBeforeTask(taskId){
	var taskName = $("#beforeId").find("option:selected").text();
	$("#beforeName").val(taskName);
}

//设值任务积分
function setScore(thisScore){
	var isRule = $("#isRule").val();
	if(isRule){

	}else{
		//评分范围设值
		$("#ruleInfo").html( "0 ~ " + thisScore);

	}
	//计划积分部分计算
	var planScoreAllObj = $("#planScoreAll");
	//除本任务外其他任务几分
	var planScoreOther = $("#planScoreOther").val();

	//计算加入本任务后的总积分
	var allScore = accAdd(thisScore,planScoreOther,2);
	planScoreAllObj.val(allScore);
}


//积分规则设定方法
function setRule(){
	// 获取isRule
	var isRuleObj = $("#isRule");
	// 获取任务名称
	var taskName = $("#taskName").val();

	// 弹窗
	url = "?model=hr_trialplan_trialplandetailex&action=toSetRule&id=" + isRuleObj.val()
		+ "&taskName="
		+ taskName
	;

	//为了解决GOOGLE 浏览器的BUG，所以要使用以下代码
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue//赋值

	if(dlgReturnValue){
		isRuleVal = eval("(" + dlgReturnValue + ")");
		isRuleObj.val(isRuleVal.isRule);
		//评分范围设值
		$("#ruleInfo").html( isRuleVal.lowerLimit + " ~ " +  isRuleVal.upperLimit);
	}
}

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
//查看积分规则
function viewRule(){
	showRule($("#isRule").val());
}
//
function showRule(isRule){
	// 弹窗
	var url = "?model=hr_trialplan_trialplandetailex&action=toViewRule&id=" + isRule;
	showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
}
