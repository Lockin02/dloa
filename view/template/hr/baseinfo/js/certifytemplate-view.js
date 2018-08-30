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
			display : '行为模块id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '行为模块',
			name : 'moduleName',
			tclass : 'txtmiddle',
			width : 150
		}, {
			display : '行为要项id',
			name : 'detailid',
			type : 'hidden'
		}, {
			display : '行为要项',
			name : 'detailName',
			width : 150
		}, {
			display : '权重',
			name : 'weights',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '任职标准',
			name : 'standard',
			width : 300
		}, {
			display : '需要提供的评价材料',
			name : 'needMaterial'
		}]
	})
})