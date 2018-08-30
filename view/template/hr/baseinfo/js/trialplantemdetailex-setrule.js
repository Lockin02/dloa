$(document).ready(function() {
	$("#trialplantemdetailex").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetailex&action=listJson',
		objName : 'trialplantemdetailex',
		tableClass : 'form_in_table',
		param : {
			'ids' : $("#id").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '<span class="blue">评分上限(小于等于)</span>',
			name : 'upperLimit',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">评分下限(大于等于)</span>',
			name : 'lowerLimit',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">对应积分</span>',
			name : 'score',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}]
	})

	/**
	 * 验证信息
	 */
	validate();
})