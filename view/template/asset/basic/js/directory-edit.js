$(function(){

	/**
	* 编号唯一性验证
	*/

	var url = "?model=asset_basic_directory&action=checkRepeat";
	if ($("#id").val()) {
				url += "&id=" + $("#id").val();
			}
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
	validate( {"limitYears" : {
			custom : ['onlyNumber']
		},"salvage" : {
			custom : ['percentageNum']
		}
	});
	//修改时将已选的单选按钮带出来
	var isDepr=$("#isDeprHidden").val();
	$("input[type=radio]").each(function(){
		if(isDepr==$(this).val()){
			$(this).attr("checked","checked");
		}
	});

});
