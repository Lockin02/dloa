
$(function(){
	    /**
		* 编号唯一性验证
		*/

	var url = "?model=asset_basic_directory&action=checkRepeat";
	$("#code").ajaxCheck({
						url : url,
						alertText : "* 该编号已存在",
						alertTextOk : "* 该编号可用"
					});
		$("#name").ajaxCheck({
						url : url,
						alertText : "* 该名字已存在",
						alertTextOk : "* 该名字可用"
					});

	/**
	 * 验证信息
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


