$(function() {
   validate({
				"planEndDate" : {
					required : true,
				},
				"executionDate" : {
					required : true,
				},
				"workloadDay" : {
					required : true,
				},
				"effortRate" : {
					required : true,
				}
			});
  })