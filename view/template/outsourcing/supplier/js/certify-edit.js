$(document).ready(function() {

	uploadfile = createSWFUpload({
		"serviceType": "oa_outsourcesupp_certify",
		"serviceId":$("#id").val()
	});

	//�ȼ�
		$('select[name="certify[certifyLevel]"] option').each(function() {
			if( $(this).val() == $("#certifyLevelSelect").val() ){
				$(this).attr("selected","selected'");
			}
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