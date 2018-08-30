$(document).ready(function() {
	/**
	 * /** ��֤��Ϣ
	 */
	validate({
		"productCode" : {
			required : true
		},
		"productName" : {
			required : true
		},

		"miniNum" : {
			required : true,
			custom : [ 'onlyNumber' ]
		},
		"maxNum" : {
			required : true,
			custom : [ 'onlyNumber' ]
		}
	});
// $("#productId").ajaxCheck({
// url : "?model=stock_productinfo_prostockwarn&action=checkRepeat",
// alertText : "* �����ϱ��������",
// alertTextOk : "* OK"
// });
})

/**
 * ��У��
 * 
 * @returns {Boolean}
 */
function checkForm() {
	var resultBack = true;

	$.ajax({
		type : "GET",
		async : false,
		url : "?model=stock_productinfo_prostockwarn&action=checkRepeat",
		data : {
// id : $("#id").val(),
			productId : $("#productId").val(),
		},
		success : function(result) {
			if (result == 1) {
				alert("������������ã�")
				resultBack = false;
			}
		}
	})
	return resultBack;
}
