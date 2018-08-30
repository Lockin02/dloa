$(document).ready(function() {
	$("#applyPosition").yxcombogrid_interview({
		isDown :false,
		hiddenId : 'applyPositionId',
		width : 500,
		nameCol : 'employmentCode',
		isFocusoutCheck : false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e ,row ,data) {
					setValueByEmployment(data);
					setWork(data.id);
					setEducation(data.id);
					setFamily(data.id);
				}
			},
			showcheckbox : false,
			// ��������
			searchitems : [{
				display : 'ӦƸ������',
				name : 'name'
			}]
		}
	});

	// ��������
	$("#work").yxeditgrid({
		objName : 'personnel[work]',
		url:'?model=hr_personnel_work&action=listJson',
		param:{
			'userNo' : $("#userNo").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
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
		objName : 'personnel[education]',
		url:'?model=hr_personnel_education&action=listJson',
		param:{
			'userNo' : $("#userNo").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
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

	//��ͥ��Ա
	$("#family").yxeditgrid({
		objName : 'personnel[family]',
		url:'?model=hr_personnel_society&action=listJson',
		param:{
			'userNo' : $("#userNo").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����',
			name : 'relationName',
			tclass : 'txtshort'
		},{
			display : '����',
			name : 'age',
			tclass : 'txtshort'
		},{
			display : '�뱾�˹�ϵ',
			name : 'isRelation',
			tclass : 'txt'
		},{
			display : '������λ',
			name : 'workUnit',
			tclass : 'txtlong'
		},{
			display : 'ְλ',
			name : 'job',
			tclass : 'txtshort'
		},{
			display : '��ϵ��ʽ',
			name : 'information',
			tclass : 'txt'
		}]
	});
});

function setValueByEmployment(data) {
	var inputObj = {
		'nation' : "nation", //����
		'staffName' : "name", //����
		'birthdate' : "birthdate", //��������
		'identityCard' : "identityCard", //���֤��
		'identityCardDate' : "identityCardDate", //���֤��Ч����
		'identityCardAddress' : "identityCardAddress", //���֤�ϵ�ַ
		'highSchool' : "highSchool", //��ҵѧУ
		'graduateDate' : "graduateDate", //��ҵʱ��
		'professionalName' : "professionalName", //רҵ
		'archivesLocation' : "archivesLocation", //�������ڵ�
		'InfectDiseases' : "InfectDiseases", //��Ⱦ����
		'height' : "height", //���
		'weight' : "weight", //����
		'blood' : "blood", //Ѫ��
		'oftenCardNum' : "bankCardNum", //����
		'oftenAccount' : "accountNumb", //�˺�
		'oftenBank' : "openingBank", //������
		'telephone' : "telephone", //�̶��绰
		'mobile' : "mobile", //�ƶ��绰
		'QQ' : "QQ", //QQ
		'personEmail' : "personEmail", //���˵�������
		'homePhone' : "homePhone", //��ͥ�绰
		'information' : "information", //������ϵ��ʽ
		'emergencyName' : "emergencyName", //������ϵ��
		'emergencyTel' : "emergencyTel", //������ϵ�˵绰
		'emergencyRelation' : "emergencyRelation", //������ϵ�˹�ϵ
		'nowAddress' : "nowAddress", //��ס��ַ
		'nowPost' : "nowPost", //��ס��ַ�ʱ�
		'homeAddress' : "homeAddress", //��ͥ��ϸ��ַ
		'homePost' : "homePost", //��ͥ��ϸ��ַ�ʱ�
		'appointAddress' : "appointAddress", //����ָ���ʼ�ͨ�ŵ�ַ
		'appointPost' : "appointPost", //����ָ���ʼ�ͨ�ŵ�ַ�ʱ�

		//�����ֵ�
		'politicsStatusCode' : "politicsStatusCode", //������ò
		'highEducation' : "highEducation", //���ѧ��
		'englishSkill' : "englishSkill", //Ӣ��ȼ�
		'healthStateCode' : "healthStateCode" //�������
	};

	var selectObj = {
		'sex' : "sex", //�Ա�
		'birthStatus' : "birthStatus", //�������
		'maritalStatusName' : "maritalStatusName", //�������
		'householdType' : "householdType", //��������
		'collectResidence' : "collectResidence" //���廧��
	};

	var addressObj = {
		'province' : "nativePlacePro", //����
		'city' : "nativePlaceCity", //����
		'province2' : "residencePro", //������
		'city2' : "residenceCity", //������
		'nowPlaceProId' : "nowPlacePro", //��ס��ַ
		'nowPlaceCityId' : "nowPlaceCity", //��ס��ַ
		'homeAddressProId' : "homeAddressPro", //��ͥ��ϸ��ַ
		'homeAddressCityId' : "homeAddressCity", //��ͥ��ϸ��ַ
		'appointPro' : "appointPro", //����ָ���ʼ�ͨ�ŵ�ַ
		'appointCity' : "appointCity" //����ָ���ʼ�ͨ�ŵ�ַ
	};

	//�����
	$.each(inputObj ,function (key ,val) {
		$("#" + key).next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //ʵ��continue����
		}

		if ($.trim($("#" + key).val()) == '') {
			$("#" + key).val(data[val]);
			$("#" + key).after("<span style='color:green' title='" + data[val] + "'>��������</span>");
		} else if ($("#" + key).val() != data[val]) {
			$("#" + key).after("<span style='color:red' title='" + data[val] + "'>��������</span>");
		}
	});

	//�̶�������ѡ��
	$.each(selectObj ,function (key ,val) {
		$("select[name='personnel[" + key + "]']").next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //ʵ��continue����
		}

		if ($.trim($("select[name='personnel[" + key + "]']").val()) == '') {
			$("select[name='personnel[" + key + "]']").val(data[val]);
			$("select[name='personnel[" + key + "]']").after("<span style='color:green' title='" + data[val] + "'>��������</span>");
		} else if ($("select[name='personnel[" + key + "]']").val() != data[val]) {
			$("select[name='personnel[" + key + "]']").after("<span style='color:red' title='" + data[val] + "'>��������</span>");
		}
	});

	//ʡ������е�����ѡ��
	$.each(addressObj ,function (key ,val) {
		$("#" + key).next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //ʵ��continue����
		}

		if ($.trim($("#" + key).val()) == '') {
			$("#" + key + " option[title='" + data[val] + "']").attr("selected", true);
			$("#" + key).trigger('change').after("<span style='color:green' title='" + data[val] + "'>��������</span>");
		} else if ($("#" + key).find("option:selected").text() != data[val]) {
			$("#" + key).after("<span style='color:red' title='" + data[val] + "'>��������</span>");
		}
	});

	//��������
	//�Ƿ��м�����ʷ
	if ($.trim(data['isMedicalHistory']) != '') {
		if (data['isMedicalHistory'] != $("input[name='personnel[isMedicalHistory]']:checked").val()) {
			$("#medicalHistory").before("<span style='color:red' title='" + data['isMedicalHistory'] + "'>��������</span>");
		} else if ($("input[name='personnel[isMedicalHistory]']:checked").val() == '��') {
			if ($.trim(data['medicalHistory']) != '') {
				if ($("#medicalHistory").val() == '') {
					$("#medicalHistory").val(data['medicalHistory']);
					$("#medicalHistory").after("<span style='color:green' title='" + data['medicalHistory'] + "'>��������</span>");
				} else if ($("#medicalHistory").val() != $.trim(data['medicalHistory'])) {
					$("#medicalHistory").after("<span style='color:red' title='" + data['medicalHistory'] + "'>��������</span>");
				}
			}
		}
	}

	//�����ֵ���������title��ʾ�޸�
	$("#politicsStatusCode").next("span").attr('title' ,data['politicsStatus']);
	$("#highEducation").next("span").attr('title' ,data['highEducationName']);
	$("#englishSkill").next("span").attr('title' ,data['englishSkillName']);
	$("#healthStateCode").next("span").attr('title' ,data['healthState']);
}

function setWork(employmentId) {
	var workData = $.ajax({
						type : 'POST',
						url : '?model=hr_recruitment_work&action=listJson',
						data : {
							'employmentId' : employmentId
						},
						async : false
					}).responseText;
	workData = eval("(" + workData + ")");

	if (workData) {
		var rowNum = 0;
		for (var i = 0; i < workData.length; i++) {
			delete workData[i]['id'];
			rowNum = $("#work").yxeditgrid('getCurRowNum');
			$("#work").yxeditgrid('addRow' ,rowNum ,workData[i]);
		}
	}
}

function setEducation(employmentId) {
	var educationData = $.ajax({
						type : 'POST',
						url : '?model=hr_recruitment_education&action=listJson',
						data : {
							'employmentId' : employmentId
						},
						async : false
					}).responseText;
	educationData = eval("(" + educationData + ")");

	if (educationData) {
		var rowNum = 0;
		for (var i = 0; i < educationData.length; i++) {
			delete educationData[i]['id'];
			rowNum = $("#education").yxeditgrid('getCurRowNum');
			$("#education").yxeditgrid('addRow' ,rowNum ,educationData[i]);
		}
	}
}

function setFamily(employmentId) {
	var familyData = $.ajax({
						type : 'POST',
						url : '?model=hr_recruitment_family&action=listJson',
						data : {
							'employmentId' : employmentId
						},
						async : false
					}).responseText;
	familyData = eval("(" + familyData + ")");

	if (familyData) {
		var rowNum = 0;
		for (var i = 0; i < familyData.length; i++) {
			delete familyData[i]['id'];
			familyData[i]['relationName'] = familyData[i]['name'];
			familyData[i]['isRelation'] = familyData[i]['relation'];
			familyData[i]['workUnit'] = familyData[i]['work'];
			familyData[i]['job'] = familyData[i]['post'];
			rowNum = $("#family").yxeditgrid('getCurRowNum');
			$("#family").yxeditgrid('addRow' ,rowNum ,familyData[i]);
		}
	}
}