$(document).ready(function() {

	validate({
		"year" : {
			required : true
		}
	});

});

function checkDate() {
	//�жϸ�����Ƿ��Ѿ��м�¼
	var isSubmit = $.ajax({
			type : 'POST',
			url : '?model=hr_worktime_set&action=checkYear',
			data : {
				year : $("#year").val()
			},
			async: false
		}).responseText;
	if (isSubmit != 'yes') {
		alert("�Ѿ����ڸ���ݵļ�¼");
		return false;
	}
}