$(function(){

	if($("#actEndDate").val() != ""){
		$("#actEndDate").attr('class','readOnlyTxtNormal').attr('readonly',true);
		document.getElementById('actEndDate').onfocus=function(){};
	}
	/**
	 * ��֤��Ϣ
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

	//����ʱֱ��ˢ���ֳ����ý���
	calBudgetParts();

	//���Ȳ��
	calProcessGap();

	//ʵʩ����
	calDays();
});

