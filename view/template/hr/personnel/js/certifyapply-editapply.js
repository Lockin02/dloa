var month = [];
var year = [];

//年初始化
function initYear(thisYear){
	for(i = thisYear ;i >= 1990 ; i--){
		var newArr = [];
		newArr.name = newArr.value = i;
		year.push(newArr);
	}
}

//月初始化
function initMonth(){
	for(i = 1 ;i<= 12 ; i++){
		var newArr = [];
		newArr.name = newArr.value = i;
		month.push(newArr);
	}
}

$(document).ready(function() {
	var thisYear=$("#thisYear").val();
	initYear(thisYear);
	initMonth();


	$("#certifyapplyexp").yxeditgrid({
		url : '?model=hr_personnel_certifyapplyexp&action=listJson',
		param : {
			"applyId" : $("#id").val()
		},
		objName : 'certifyapply[certifyapplyexp]',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'applyId',
			name : 'applyId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : '起始年',
			name : 'beginYear',
			type : 'select',
			options : year
		}, {
			display : '起始月',
			name : 'beginMonth',
			type : 'select',
			options : month
		}, {
			display : '截止年',
			name : 'endYear',
			type : 'select',
			options : year
		}, {
			display : '截止月',
			name : 'endMonth',
			type : 'select',
			options : month
		}, {
			display : '单位名称',
			name : 'unitName',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '部门',
			name : 'deptName',
			tclass : 'txtmiddle'
		}, {
			display : '主要专业成果、承担的角色',
			name : 'mainWork',
			tclass : 'txt'
		}]
	});

	validate({
		"jobBeginDate" : {
			required : true
		},
		"professionalName" : {
			required : true
		},
		"highEducationName" : {
			required : true
		},
		"graduationDate" : {
			required : true
		},
		"workContent" : {
			required : true
		},
		"entryDate" : {
			required : true
		},
		"entryDate" : {
			required : true
		},
		"careerDirection" : {
			required : true
		},
		"baseLevel" : {
			required : true
		},
		"workExperience" : {
			required : true
		}
	});

})
// 直接提交审批
function toApp() {
	document.getElementById('form1').action = "index1.php?model=hr_personnel_certifyapply&action=editApply&act=app";
}