$(document).ready(function() {

	validate({
				"relationName" : {
					required : true,
					length : [0,200]
				}
			});
    });