$(function() {
	// ��ѡ�ͻ�
	$("#objectName").yxcombogrid_supplier({
		hiddenId : 'objectId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

function thisChange(thisVal){
	$("#thisMonth").val(thisVal.substr(5,2) * 1);
	$("#thisYear").val(thisVal.substr(0,4));
}