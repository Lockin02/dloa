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
			 * ��֤��Ϣ
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
//		alert("����ȷ��д�绰��Ϣ��");
//		$("#linkPhone").val("");
//		$("#linkPhone").focus();
//	}
//
//}