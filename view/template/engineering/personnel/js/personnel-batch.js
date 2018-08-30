
var userLevelArr = [];//技术等级数组

function addDataToSelectCus(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option value='" + data[i].levelName + "'>"
				+ data[i].levelName + "</option>");
	}
}

$(function() {
	$("#officeName").yxcombogrid_office();

	userLevelArr = getDataCus('','index1.php?model=engineering_assessment_assPeopleLevel&action=pageJson','levelName');
	addDataToSelectCus(userLevelArr, 'userLevel','id','levelName');
});

function checkform(){
	if($('#userName').val() == ""){
		alert('请选择人员');
		return false;
	}
	if($('#officeId').val() == ""){
		alert('请选择所属办事处');
		return false;
	}
	return true;
}