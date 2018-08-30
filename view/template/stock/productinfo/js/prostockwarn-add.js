$(document).ready(function() {
	/**
	 * /** 验证信息
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
// alertText : "* 该物料编号已设置",
// alertTextOk : "* OK"
// });
})

/**
 * 表单校验
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
				alert("你该物料已设置！")
				resultBack = false;
			}
		}
	})
	return resultBack;
}
