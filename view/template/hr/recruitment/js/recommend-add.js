$(document).ready(function() {
//	$("#positionName").yxcombogrid_jobs({
//		hiddenId : 'positionId',
//		width : 350
//	});
	validate({
		"positionName" : {
			required : true
		},
		"recommendName" : {
			required : true
		},
		"isRecommendName" : {
			required : true
		},
		"source" : {
			required : true
		},
		"recommendReason" : {
			require : true
		},
		"recommendRelation" : {
			require : true
		}
	});
	/*
	 validate({
	 "orderNum" : {
	 required : true,
	 custom : 'onlyNumber'
	 }
	 });
	 */
})
function checkForm() {
	if ($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件") {
		alert('请上传附件！');
		return false;
	}
	return true;
}

function Post() {
	$("#form1").attr("action", "?model=hr_recruitment_recommend&action=handup")
	if (checkForm())
		$("#form1").submit();
}