$(document).ready(function() {
});

//���������Ч��
function checkData() {
	var $produceNumObj = $("#equInfo").yxeditgrid("getCmpByCol" ,"produceNum");
	if ($produceNumObj.length == 0) {
		alert("û���´������");
		return false;
	}
	for (var i = 0 ;i < $produceNumObj.length ;i++) {
		if ($produceNumObj[i].value <= 0) {
			alert("���������������������0");
			return false;
		}
	}
	return true;
}