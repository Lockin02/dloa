$(function() {
	var hasTimeTask = $("#hasTimeTask").val();
	if (hasTimeTask == 1) {
		$("#startButton").hide();
		$("#stopButton").show();
	} else {
		$("#stopButton").hide();
		$("#startButton").show();
	}
	$("#startButton").bind('click', function() {
		var text = $.ajax({
			url : '?model=common_timeTask&action=setHasTimeTask',
			async : false
		}).responseText;
		if (text == 1) {
			$("#startButton").hide();
			$("#stopButton").show();
		}
		$.ajax({
			url : '?model=common_timeTask&action=startTimeTask'
		});
		alert("启动成功！");
		window.open('','_top');
		window.top.close();
	});

	$("#stopButton").bind('click', function() {
		var text = $.ajax({
			url : '?model=common_timeTask&action=stopTimeTask',
			async : false
		}).responseText;
		if (text == 1) {
			$("#stopButton").hide();
			$("#startButton").show();
		}
		alert("暂停成功！");
	});

});