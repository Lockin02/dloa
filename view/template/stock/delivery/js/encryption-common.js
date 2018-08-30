$(document).ready(function() {
});

//检查数据有效性
function checkData() {
	var $produceNumObj = $("#equInfo").yxeditgrid("getCmpByCol" ,"produceNum");
	if ($produceNumObj.length == 0) {
		alert("没有下达的任务！");
		return false;
	}
	for (var i = 0 ;i < $produceNumObj.length ;i++) {
		if ($produceNumObj[i].value <= 0) {
			alert("加密锁任务数量必须大于0");
			return false;
		}
	}
	return true;
}