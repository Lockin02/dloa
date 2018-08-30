$(document).ready(function() {
	
	var isNeedUpload = $("#isNeedUpload").val();
	if(isNeedUpload == 1){
		$("#isNeedUploadYes").attr("checked",true);
	}else{
		$("#isNeedUploadNo").attr("checked",true);
	}

	validate({
		"fileName" : {
			required : true
		},
		"fileType" : {
			required : true
		},
		"isNeedUpload" : {
			required : true
		}
	});
 })