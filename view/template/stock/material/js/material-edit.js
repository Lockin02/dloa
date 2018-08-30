$(document).ready(function() {


	validate({
				"materialNum" : {
					required : true,
					custom : ['onlyNumber']
				}
 		});
 })