$(document).ready(function() {

	$("#certifytemplatedetail").yxeditgrid({
		tableClass : 'form_in_table',
		url : '?model=hr_baseinfo_certifytemplatedetail&action=listJson',
		param : {"modelId" : $("#id").val()},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName',
			tclass : 'txtmiddle',
			width : 150
		}, {
			display : '��ΪҪ��id',
			name : 'detailid',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName',
			width : 150
		}, {
			display : 'Ȩ��',
			name : 'weights',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '��ְ��׼',
			name : 'standard',
			width : 300
		}, {
			display : '��Ҫ�ṩ�����۲���',
			name : 'needMaterial'
		}]
	})
})