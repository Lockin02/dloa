$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"activityName" : {
			required : true
		},
		"workRate" : {
			required : true,
			custom : ['percentageNum']
		}
	});
});

//��֤���
function checkform(){
	var workRate = $("#workRate").val();
	var canUseWorkRate = $("#canUseWorkRate").val();
	if(workRate* 1 > canUseWorkRate *1){
		alert('�����ռ�ȴ��ڿ��ù���ռ�ȣ��������޸�');
		return false;
	}
	return true;
}