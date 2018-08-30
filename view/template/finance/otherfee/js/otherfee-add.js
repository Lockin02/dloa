$(document).ready(function() {
	//表单验证
	validate({
		"accountYear" : {
			required : true
		},
		"accountPeriod" : {
			required : true
		},
		"summary" : {
			required : false
		},
		"subjectName" : {
			required : true
		},
		"debit_v" : {
			required : true
		},
		"chanceCode" : {
			required : false
		},
		"trialProjectCode" : {
			required : false
		},
		"feeDeptName" : {
			required : true
		},
		"contractCode" : {
			required : false
		},
		"province" : {
			required : false
		}
	});
/* 	$("#accountYear").focus(function(){
		var sel = document.createElement('select');
		var date = new Date()();
		for(var i=0;i<5;i++)
		{
			var opt = document.createElement("option");
			opt.value = i;
			opt.innerHTML = (date.getYear()-i);
			sel.appendChild(opt);
		}	
		document.body.appendChild(sel);
	}); */
	//人员选择
/*	$("#managerName").yxselect_user({
*		hiddenId : 'managerId',
*		formCode : 'stockinfo'
	}); */
})