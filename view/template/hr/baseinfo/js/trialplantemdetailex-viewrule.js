$(document).ready(function() {
	$("#trialplantemdetailex").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetailex&action=listJson',
		tableClass : 'form_in_table',
		param : {
			'ids' : $("#id").val()
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '评分上限(小于等于)',
			name : 'upperLimit'
		}, {
			display : '评分下限(大于等于)',
			name : 'lowerLimit'
		}, {
			display : '对应积分',
			name : 'score'
		}]
	})
})