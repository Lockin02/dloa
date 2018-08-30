$(document).ready(function() {
	$("#trialplandetailex").yxeditgrid({
		url : '?model=hr_trialplan_trialplandetailex&action=listJson',
		objName : 'trialplandetailex',
		tableClass : 'form_in_table',
		param : {
			'ids' : $("#id").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '<span class="blue">��������(С�ڵ���)</span>',
			name : 'upperLimit',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">��������(���ڵ���)</span>',
			name : 'lowerLimit',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">��Ӧ����</span>',
			name : 'score',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}]
	})

	/**
	 * ��֤��Ϣ
	 */
	validate();
})