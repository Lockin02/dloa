function getcols() {
	// 提取输入行数
	var colsnum = $("#cols")[0].value;
	if ($("#colsStr")[0].value != "") {
		var obj = $("#colsStr")[0].value.split(",");
	}
	$(".txtshort").remove();
	$("br").remove();
	// 重新设置输入框
	for (var i = 0; i < colsnum; i++) {
		var set = document.createElement("input");
		// $("#setcols").append(set);
		set.type = "text";
		set.id = "user" + i;
		if (obj&&obj[i])
			set.value = obj[i];
		set.className = "txtshort";
		$("#width").append(set);
		$("#width").append(document.createElement("br"));
		$("#width").append(document.createElement("br"));
	}
}

function combine() {
	// 合并输入数据
	var colsnum = $("#cols")[0].value;
	var set = '';
	for (var i = 0; i < colsnum; i++) {
		if (isNaN($("#user" + i)[0].value)) {
			alert('必须是数字！');
			$("#user" + i).focus();
			return false;
		}
		if (i + 1 != colsnum)
			set += $("#user" + i)[0].value + ",";
		else
			set += $("#user" + i)[0].value;
	}
	$("#colsStr")[0].value = set;
}

$(document).ready(function() {
			getcols();
		});