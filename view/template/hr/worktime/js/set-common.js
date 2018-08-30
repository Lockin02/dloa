$(document).ready(function() {

	validate({
		"year" : {
			required : true
		}
	});

});

function checkDate() {
	//判断该年份是否已经有记录
	var isSubmit = $.ajax({
			type : 'POST',
			url : '?model=hr_worktime_set&action=checkYear',
			data : {
				year : $("#year").val()
			},
			async: false
		}).responseText;
	if (isSubmit != 'yes') {
		alert("已经存在该年份的记录");
		return false;
	}
}