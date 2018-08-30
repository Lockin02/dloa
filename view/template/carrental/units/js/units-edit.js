$(document).ready(function() {

	$.formValidator.initConfig({
				theme : "Default",
				submitOnce : true,
				formID : "form1",
				onError : function(msg, obj, errorlist) {
					alert(msg);
				}
			});

			$("#createName").yxselect_user({
						hiddenId : 'createId'
					});

			/**
			 * 验证信息
			 */
			validate({

						"unitName" : {
							required : true

						},
						"linkMan" : {
							required : true
						},
						"linkPhone" : {
							required : true
						},
						"unitNature" : {
							required : true

						}
					});

 })