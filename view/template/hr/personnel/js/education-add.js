$(document).ready(function() {
	validate({
				"organization" : {
					required : true,
					length : [0,100]
				},
				"content" : {
					required : true,
					length : [0,200]
				},
				"userNo" : {
					required : true,
					length : [0,200]
				}
			});
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo'
	});
});


    //��ʼʱ�������ʱ�����֤
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
}