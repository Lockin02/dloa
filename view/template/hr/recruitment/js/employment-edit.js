$(document).ready(function() {
	var isIT = $("#isIT").val();
	if(isIT == "1") {
		document.getElementById("project").style.display = "";
	} else {
		document.getElementById("project").style.display = "none";
	}

	//���֤��Ч����
	var identityCardDate = $("#identityCardDate").val();
	var carDate = identityCardDate.split("-");
	if (carDate.length > 1) {
		$("#identityCardDate0").val(carDate[0]);
		$("#identityCardDate1").val(carDate[1]);
	}

	 // ��Ŀ����
	 $("#projectList").yxeditgrid({
	 	objName : 'employment[project]',
	 	url :'?model=hr_recruitment_project&action=listJson',
	 	param : {
	 		employmentId : $("#employmentId").val()
	 	},
	 	tableClass : 'form_in_table',
	 	colModel : [{
	 		display : '��ʼʱ��',
	 		name : 'beginDate',
	 		tclass : 'txtshort',
			readonly : true,
	 		type : 'date'
	 	},{
	 		display : '����ʱ��',
	 		name : 'closeDate',
	 		tclass : 'txtshort',
			readonly : true,
	 		type : 'date'
	 	},{
	 		display : '��Ŀ����',
	 		name : 'projectName',
	 		tclass : 'txt'
	 	},{
	 		display : '��Ҫ���ú��ּ���',
	 		name : 'projectSkill',
	 		tclass : 'txt'
	 	},{
	 		display : '����Ŀ�еĽ�ɫ',
	 		name : 'projectRole',
	 		tclass : 'txt'
	 	}]
	 });

	// ��������
	$("#work").yxeditgrid({
		objName : 'employment[work]',
		url : '?model=hr_recruitment_work&action=listJson',
		param : {
			employmentId : $("#employmentId").val()
		},
		tableClass : 'form_in_table',
		isFristRowDenyDel : true,
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			readonly : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			readonly : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '��˾����',
			name : 'company',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		},{
			display : '����',
			name : 'dept',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		},{
			display : 'ְλ',
			name : 'position',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		},{
			display : '����',
			name : 'treatment',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '��ְԭ��',
			name : 'leaveReason',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '֤����/ְ��绰',
			name : 'prove',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});

	// ��������
	$("#education").yxeditgrid({
		objName : 'employment[education]',
		url :'?model=hr_recruitment_education&action=listJson',
		param : {
			employmentId : $("#employmentId").val()
		},
		tableClass : 'form_in_table',
		isFristRowDenyDel : true,
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			readonly : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			readonly : true,
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : 'ѧУ����/��ѵ����',
			name : 'organization',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : 'רҵ����ѵ����',
			name : 'content',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '֤��',
			name : 'certificate',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});

	// ��ͥ��Ա
	$("#family").yxeditgrid({
		objName : 'employment[family]',
		url :'?model=hr_recruitment_family&action=listJson',
		param : {
			employmentId : $("#employmentId").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : '����',
			name : 'name',
			tclass : 'txtshort'
		},{
			display : '����',
			name : 'age',
			tclass : 'txtshort'
		},{
			display : '�뱾�˹�ϵ',
			name : 'relation',
			tclass : 'txt'
		},{
			display : '������λ',
			name : 'work',
			tclass : 'txtlong'
		},{
			display : 'ְλ',
			name : 'post',
			tclass : 'txtshort'
		},{
			display : '��ϵ��ʽ',
			name : 'information',
			tclass : 'txt'
		}]
	});
});

function checkIDCard (obj) {
	str = $(obj).val();
	if(isIdCardNo(str)) {
	} else {
		$(obj).val('');
	}
}