// ��ͬ�����ӱ�
$(function() {
	 var isIT = $("#isIT").val();
	 if(isIT=="1"){
	    document.getElementById("project").style.display = "";
	 }else{
	    document.getElementById("project").style.display = "none";
	 }
	 // ��Ŀ����
	$("#projectList").yxeditgrid({
		objName : 'employment[project]',
		url:'?model=hr_recruitment_project&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			tclass : 'txt'
		}, {
			display : '��Ҫ���ú��ּ���',
			name : 'projectSkill',
			tclass : 'txt'
		}, {
			display : '����Ŀ�еĽ�ɫ',
			name : 'projectRole',
			tclass : 'txt'
		}]
	});
	// ��������
	$("#work").yxeditgrid({
		objName : 'employment[work]',
		url:'?model=hr_recruitment_work&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '��˾����',
			name : 'company',
			tclass : 'txtmiddle'
		}, {
			display : '����',
			name : 'dept',
			tclass : 'txtshort'
		}, {
			display : 'ְλ',
			name : 'position',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'treatment',
			tclass : 'txt'
		}, {
			display : '��ְԭ��',
			name : 'leaveReason',
			tclass : 'txt'
		}, {
			display : '֤����/ְ��绰',
			name : 'prove',
			tclass : 'txt'
		}]
	});
	// ��������
	$("#education").yxeditgrid({
		objName : 'employment[education]',
		url:'?model=hr_recruitment_education&action=listJson',
    	type:'view',
    	param:{
        	'employmentId' : $("#employmentId").val()
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : 'ѧУ����/��ѵ����',
			name : 'organization',
			tclass : 'txt'
		}, {
			display : 'רҵ����ѵ����',
			name : 'content',
			tclass : 'txt'
		}, {
			display : '֤��',
			name : 'certificate',
			tclass : 'txt'
		}]
	});

	// ��ͥ��Ա
	$("#family").yxeditgrid({
		objName : 'employment[family]',
		url:'?model=hr_recruitment_family&action=listJson',
		param:{
        	'employmentId' : $("#employmentId").val()
        },
    	type:'view',
		tableClass : 'form_in_table',
		colModel : [{
			display : '����',
			name : 'name',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'age',
			tclass : 'txtshort'
		}, {
			display : '�뱾�˹�ϵ',
			name : 'relation',
			tclass : 'txtshort'
		}, {
			display : '������λ',
			name : 'work',
			tclass : 'txtlong'
		}, {
			display : 'ְλ',
			name : 'post',
			tclass : 'txtshort'
		}, {
			display : '��ϵ��ʽ',
			name : 'information',
			tclass : 'txt'
		}]
	});
});
