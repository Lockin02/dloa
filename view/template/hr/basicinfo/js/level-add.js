$(document).ready(function() {

	validate({
				"personLevel" : {
					required : true
				}
			});

	$("#esmLevel").yxcombogrid_eperson({
		hiddenId : 'esmLevelId',
		width : 600,
		height : 250,
		gridOptions : {
			param : {"status" : "0"},
			showcheckbox : false
		}
	});
})