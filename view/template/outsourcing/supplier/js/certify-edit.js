$(document).ready(function() {

	uploadfile = createSWFUpload({
		"serviceType": "oa_outsourcesupp_certify",
		"serviceId":$("#id").val()
	});

	//等级
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

            //开始时间与结束时间差验证
function timeCheck($t){
	var s = plusDateInfo('beginDate','endDate');
	if(s < 0) {
		alert("起始日不能大于终止日！");
		$t.value = "";
		return false;
	}
}