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


//function checkPhone() {
//	var tel = $("#linkPhone").val();
//	var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
//	if (t.test(tel) == false) {
//		alert("请正确填写电话信息！");
//		$("#linkPhone").val("");
//		$("#linkPhone").focus();
//	}
//
//}