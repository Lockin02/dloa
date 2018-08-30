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
			// 快速搜索
			searchitems : [{
				display : '应聘者姓名',
				name : 'name'
			}]
		}
	});

	// 工作经历
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
			display : '开始时间',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '结束时间',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '公司名称',
			name : 'company',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		},{
			display : '部门',
			name : 'dept',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		},{
			display : '职位',
			name : 'position',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		},{
			display : '待遇',
			name : 'treatment',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '离职原因',
			name : 'leaveReason',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '证明人/职务电话',
			name : 'prove',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});

	// 教育经历
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
			display : '开始时间',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '结束时间',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		},{
			display : '学校名称/培训机构',
			name : 'organization',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '专业或培训内容',
			name : 'content',
			tclass : 'txt',
			validation : {
				required : true
			}
		},{
			display : '证书',
			name : 'certificate',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});

	//家庭成员
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
			display : '姓名',
			name : 'relationName',
			tclass : 'txtshort'
		},{
			display : '年龄',
			name : 'age',
			tclass : 'txtshort'
		},{
			display : '与本人关系',
			name : 'isRelation',
			tclass : 'txt'
		},{
			display : '工作单位',
			name : 'workUnit',
			tclass : 'txtlong'
		},{
			display : '职位',
			name : 'job',
			tclass : 'txtshort'
		},{
			display : '联系方式',
			name : 'information',
			tclass : 'txt'
		}]
	});
});

function setValueByEmployment(data) {
	var inputObj = {
		'nation' : "nation", //民族
		'staffName' : "name", //姓名
		'birthdate' : "birthdate", //出生日期
		'identityCard' : "identityCard", //身份证号
		'identityCardDate' : "identityCardDate", //身份证有效日期
		'identityCardAddress' : "identityCardAddress", //身份证上地址
		'highSchool' : "highSchool", //毕业学校
		'graduateDate' : "graduateDate", //毕业时间
		'professionalName' : "professionalName", //专业
		'archivesLocation' : "archivesLocation", //档案所在地
		'InfectDiseases' : "InfectDiseases", //传染疾病
		'height' : "height", //身高
		'weight' : "weight", //体重
		'blood' : "blood", //血型
		'oftenCardNum' : "bankCardNum", //卡号
		'oftenAccount' : "accountNumb", //账号
		'oftenBank' : "openingBank", //开户行
		'telephone' : "telephone", //固定电话
		'mobile' : "mobile", //移动电话
		'QQ' : "QQ", //QQ
		'personEmail' : "personEmail", //个人电子邮箱
		'homePhone' : "homePhone", //家庭电话
		'information' : "information", //其他联系方式
		'emergencyName' : "emergencyName", //紧急联系人
		'emergencyTel' : "emergencyTel", //紧急联系人电话
		'emergencyRelation' : "emergencyRelation", //紧急联系人关系
		'nowAddress' : "nowAddress", //现住地址
		'nowPost' : "nowPost", //现住地址邮编
		'homeAddress' : "homeAddress", //家庭详细地址
		'homePost' : "homePost", //家庭详细地址邮编
		'appointAddress' : "appointAddress", //本人指定邮寄通信地址
		'appointPost' : "appointPost", //本人指定邮寄通信地址邮编

		//数据字典
		'politicsStatusCode' : "politicsStatusCode", //政治面貌
		'highEducation' : "highEducation", //最高学历
		'englishSkill' : "englishSkill", //英语等级
		'healthStateCode' : "healthStateCode" //健康情况
	};

	var selectObj = {
		'sex' : "sex", //性别
		'birthStatus' : "birthStatus", //生育情况
		'maritalStatusName' : "maritalStatusName", //婚姻情况
		'householdType' : "householdType", //户籍类型
		'collectResidence' : "collectResidence" //集体户口
	};

	var addressObj = {
		'province' : "nativePlacePro", //籍贯
		'city' : "nativePlaceCity", //籍贯
		'province2' : "residencePro", //户籍地
		'city2' : "residenceCity", //户籍地
		'nowPlaceProId' : "nowPlacePro", //现住地址
		'nowPlaceCityId' : "nowPlaceCity", //现住地址
		'homeAddressProId' : "homeAddressPro", //家庭详细地址
		'homeAddressCityId' : "homeAddressCity", //家庭详细地址
		'appointPro' : "appointPro", //本人指定邮寄通信地址
		'appointCity' : "appointCity" //本人指定邮寄通信地址
	};

	//输入框
	$.each(inputObj ,function (key ,val) {
		$("#" + key).next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //实现continue功能
		}

		if ($.trim($("#" + key).val()) == '') {
			$("#" + key).val(data[val]);
			$("#" + key).after("<span style='color:green' title='" + data[val] + "'>补充内容</span>");
		} else if ($("#" + key).val() != data[val]) {
			$("#" + key).after("<span style='color:red' title='" + data[val] + "'>有新内容</span>");
		}
	});

	//固定的下拉选择
	$.each(selectObj ,function (key ,val) {
		$("select[name='personnel[" + key + "]']").next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //实现continue功能
		}

		if ($.trim($("select[name='personnel[" + key + "]']").val()) == '') {
			$("select[name='personnel[" + key + "]']").val(data[val]);
			$("select[name='personnel[" + key + "]']").after("<span style='color:green' title='" + data[val] + "'>补充内容</span>");
		} else if ($("select[name='personnel[" + key + "]']").val() != data[val]) {
			$("select[name='personnel[" + key + "]']").after("<span style='color:red' title='" + data[val] + "'>有新内容</span>");
		}
	});

	//省份与城市的下拉选择
	$.each(addressObj ,function (key ,val) {
		$("#" + key).next("span").remove();
		if ($.trim(data[val]) == '') {
			return ; //实现continue功能
		}

		if ($.trim($("#" + key).val()) == '') {
			$("#" + key + " option[title='" + data[val] + "']").attr("selected", true);
			$("#" + key).trigger('change').after("<span style='color:green' title='" + data[val] + "'>补充内容</span>");
		} else if ($("#" + key).find("option:selected").text() != data[val]) {
			$("#" + key).after("<span style='color:red' title='" + data[val] + "'>有新内容</span>");
		}
	});

	//其他处理
	//是否有既往病史
	if ($.trim(data['isMedicalHistory']) != '') {
		if (data['isMedicalHistory'] != $("input[name='personnel[isMedicalHistory]']:checked").val()) {
			$("#medicalHistory").before("<span style='color:red' title='" + data['isMedicalHistory'] + "'>有新内容</span>");
		} else if ($("input[name='personnel[isMedicalHistory]']:checked").val() == '是') {
			if ($.trim(data['medicalHistory']) != '') {
				if ($("#medicalHistory").val() == '') {
					$("#medicalHistory").val(data['medicalHistory']);
					$("#medicalHistory").after("<span style='color:green' title='" + data['medicalHistory'] + "'>补充内容</span>");
				} else if ($("#medicalHistory").val() != $.trim(data['medicalHistory'])) {
					$("#medicalHistory").after("<span style='color:red' title='" + data['medicalHistory'] + "'>有新内容</span>");
				}
			}
		}
	}

	//数据字典有新内容title提示修改
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