$(document).ready(function() {


	validate({
				"company" : {
					required : true,
					length : [0,200]
				},
				"position" : {
					required : true,
					length : [0,200]
				}
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