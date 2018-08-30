// 合同新增从表
$(function() {
	 var isIT = $("#isIT").val();
	 if(isIT=="1"){
	    document.getElementById("project").style.display = "";
	 }else{
	    document.getElementById("project").style.display = "none";
	 }
	 // 项目经历
	$("#projectList").yxeditgrid({
		objName : 'employment[project]',
		url:'?model=hr_recruitment_project&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '开始时间',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '结束时间',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '项目名称',
			name : 'projectName',
			tclass : 'txt'
		}, {
			display : '主要运用何种技术',
			name : 'projectSkill',
			tclass : 'txt'
		}, {
			display : '在项目中的角色',
			name : 'projectRole',
			tclass : 'txt'
		}]
	});
	// 工作经历
	$("#work").yxeditgrid({
		objName : 'employment[work]',
		url:'?model=hr_recruitment_work&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '开始时间',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '结束时间',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '公司名称',
			name : 'company',
			tclass : 'txtmiddle'
		}, {
			display : '部门',
			name : 'dept',
			tclass : 'txtshort'
		}, {
			display : '职位',
			name : 'position',
			tclass : 'txtshort'
		}, {
			display : '待遇',
			name : 'treatment',
			tclass : 'txt'
		}, {
			display : '离职原因',
			name : 'leaveReason',
			tclass : 'txt'
		}, {
			display : '证明人/职务电话',
			name : 'prove',
			tclass : 'txt'
		}]
	});
	// 教育经历
	$("#education").yxeditgrid({
		objName : 'employment[education]',
		url:'?model=hr_recruitment_education&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '开始时间',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '结束时间',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '学校名称/培训机构',
			name : 'organization',
			tclass : 'txt'
		}, {
			display : '专业或培训内容',
			name : 'content',
			tclass : 'txt'
		}, {
			display : '证书',
			name : 'certificate',
			tclass : 'txt'
		}]
	});

	// 家庭成员
	$("#family").yxeditgrid({
		objName : 'employment[family]',
		url:'?model=hr_recruitment_family&action=listJson',
		param:{
        	'employmentId' : $("#employmentId").val()
        },
    	type:'view',
		tableClass : 'form_in_table',
		colModel : [{
			display : '姓名',
			name : 'name',
			tclass : 'txt'
		}, {
			display : '年龄',
			name : 'age',
			tclass : 'txtshort'
		}, {
			display : '与本人关系',
			name : 'relation',
			tclass : 'txtshort'
		}, {
			display : '工作单位',
			name : 'work',
			tclass : 'txtlong'
		}, {
			display : '职位',
			name : 'post',
			tclass : 'txtshort'
		}, {
			display : '联系方式',
			name : 'information',
			tclass : 'txt'
		}]
	});
});
