$(document).ready(function() {
	uploadfile = createSWFUpload({
				"serviceType": "oa_outsourcesupp_certify"
	});

	validate({
				"certifyName" : {
					required : true
				},
				"certifyCode" : {
					required : true
				},
				"certifyCompany" : {
					required : true
				}
			});
    })

        //��ʼʱ�������ʱ�����֤
function timeCheck($t){
	var s = plusDateInfo('beginDate','endDate');
	if(s < 0) {
		alert("��ʼ�ղ��ܴ�����ֹ�գ�");
		$t.value = "";
		return false;
	}
}