$(document).ready(function() {

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