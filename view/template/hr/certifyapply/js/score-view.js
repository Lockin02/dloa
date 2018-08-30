$(document).ready(function() {
	//模板
	$("#scoredetail").yxeditgrid({
		url : '?model=hr_certifyapply_scoredetail&action=listJson',
		param : {"scoreId" : $("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		title : '评分明细',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '模版Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '行为模块id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '行为模块',
			name : 'moduleName'
		}, {
			display : '行为要项id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '行为要项',
			name : 'detailName'
		}, {
			display : '权重(%)',
			name : 'weights'
		}, {
			display : '评分',
			name : 'score'
		}]
	})
});