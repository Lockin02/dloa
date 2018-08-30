$(document).ready(function() {
	validate({
		"taskName" : {
			required : true
		},
		"taskScore" : {
			required : true
		},
		"planScoreAll" : {
			required : true
		},
		"planBaseScore" : {
			required : true
		}
	});

	//负责人
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'trialplanManager'
	});

	//设置默认值
	setSelect('isNeed');
	setSelect('closeType');
})

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
		isRuleObj.val(dlgReturnValue);
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

//
function showRule(isRule){
	// 弹窗
	var url = "?model=hr_trialplan_trialplandetailex&action=toViewRule&id=" + isRule;
	showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
}