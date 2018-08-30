

$(function() {
	/**
	 * 编码唯一性验证
	 */

	var url = "?model=asset_basic_change&action=checkRepeat";
	if ($("#id").val()) {
		url += "&id=" + $("#id").val();
	}
	$("#code").ajaxCheck({
		url : url,
		alertText : "* 该编码已存在",
		alertTextOk : "* 该编码可用"
	});

	$("#name").ajaxCheck({
		url : url,
		alertText : "* 该名称已存在",
		alertTextOk : "* 该名称可用"
	});
	/**
		/**
	 * 验证信息
	 */
	validate({

//		"vouchers" : {
//			required : true
//
//		},
//		"subName" : {
//			required : true
//		},
//		"code" : {
//			required : true
//
//		}
	});
	$('#name').blur(function(){
		name = strTrim($('#name').val());
		$('#name').val(name);
	})
});
