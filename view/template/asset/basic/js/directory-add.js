
$(function(){
	    /**
		* ���Ψһ����֤
		*/

	var url = "?model=asset_basic_directory&action=checkRepeat";
	$("#code").ajaxCheck({
						url : url,
						alertText : "* �ñ���Ѵ���",
						alertTextOk : "* �ñ�ſ���"
					});
		$("#name").ajaxCheck({
						url : url,
						alertText : "* �������Ѵ���",
						alertTextOk : "* �����ֿ���"
					});

	/**
	 * ��֤��Ϣ
	 */
	validate({
//		"code" : {
//			custom : ['onlyNumber']
//		},
		"limitYears" : {
			custom : ['onlyNumber']
		},"salvage" : {
			custom : ['percentageNum']
		}
	});
	$('#name').blur(function(){
		name = strTrim($('#name').val());
		$('#name').val(name);
	})
});


