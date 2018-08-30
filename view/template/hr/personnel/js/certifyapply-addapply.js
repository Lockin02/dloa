var month = [];
var year = [];
//���ʼ��
function initYear(thisYear){
	for(i = thisYear ;i >= 1990 ; i--){
		var newArr = [];
		newArr.name = newArr.value = i;
		year.push(newArr);
	}
}

//�³�ʼ��
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
	// $("#userName").yxselect_user({
	// hiddenId : 'userAccount',
	// userNo : 'userNo',
	// isGetDept : [true, "deptId", "deptName"],
	// formCode : 'userName'
	// });

	// $("#deptName").yxselect_dept({
	// hiddenId : 'deptId'
	// });
	$("#certifyapplyexp").yxeditgrid({
		objName : 'certifyapply[certifyapplyexp]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��ʼ��',
			name : 'beginYear',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'select',
			options : year
		}, {
			display : '��ʼ��',
			name : 'beginMonth',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'select',
			options : month
		}, {
			display : '��ֹ��',
			name : 'endYear',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'select',
			options : year
		}, {
			display : '��ֹ��',
			name : 'endMonth',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'select',
			options : month
		}, {
			display : '��λ����',
			name : 'unitName',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'deptName',
			tclass : 'txtmiddle'
		}, {
			display : '��Ҫרҵ�ɹ����е��Ľ�ɫ',
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
		"certifyDirectionCode" : {
			required : true
		},
		"workExperience" : {
			required : true
		}
	});
})
// ֱ���ύ����
function toApp() {
	document.getElementById('form1').action = "index1.php?model=hr_personnel_certifyapply&action=addApply&act=app";
}