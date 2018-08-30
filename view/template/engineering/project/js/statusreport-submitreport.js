$(function(){

	if($("#actEndDate").val() != ""){
		$("#actEndDate").attr('class','readOnlyTxtNormal').attr('readonly',true);
		document.getElementById('actEndDate').onfocus=function(){};
	}
	/**
	 * 验证信息
	 */
	validate({
		"planEndDate" : {
			required : true
		},
		"expectedDuration" : {
			required : true
		},
		"projectProcess" : {
			required : true
		},
		"workedDays" : {
			required : true
		},
		"needDays" : {
			required : true
		},
		"description" : {
			required : true
		},
		"workFocus" : {
			required : true
		},
		"difficult" : {
			required : true
		},
		"memberStatus" : {
			required : true
		},
		"saleChance" : {
			required : true
		},
		"competitorTrends" : {
			required : true
		},
		"nextPlan" : {
			required : true
		}
	});

	//进入时直接刷新现场费用进度
	calBudgetParts();

	//进度差距
	calProcessGap();

	//实施天数
	calDays();
});

