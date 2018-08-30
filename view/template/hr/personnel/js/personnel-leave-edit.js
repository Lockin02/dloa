$(document).ready(function() {
	if ($('#isBack').attr('val') == 1) {
		$('#isBack > option:eq(0)').attr('selected' ,'selected');
	}

	validate({
		"entryDate" : {
			required : true
		}
	});
});