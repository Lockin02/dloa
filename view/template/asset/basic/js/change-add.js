

$(function() {
	/**
	 * ����Ψһ����֤
	 */

	var url = "?model=asset_basic_change&action=checkRepeat";
	if ($("#id").val()) {
		url += "&id=" + $("#id").val();
	}
	$("#code").ajaxCheck({
		url : url,
		alertText : "* �ñ����Ѵ���",
		alertTextOk : "* �ñ������"
	});

	$("#name").ajaxCheck({
		url : url,
		alertText : "* �������Ѵ���",
		alertTextOk : "* �����ƿ���"
	});
	/**
		/**
	 * ��֤��Ϣ
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
