
var userLevelArr = [];//�����ȼ�����

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
		alert('��ѡ����Ա');
		return false;
	}
	if($('#officeId').val() == ""){
		alert('��ѡ���������´�');
		return false;
	}
	return true;
}