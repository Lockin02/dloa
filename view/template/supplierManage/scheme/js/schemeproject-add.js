$(document).ready(function() {

	/**
	 * 验证信息
	 */
	validate({
		"schemeCode" : {
			required : true
		},
		"assesProName" : {
			required : true
		}
	});
	/**
	 * 评估项目名称唯一性验证
	 */
	 var url = "?model=supplierManage_scheme_schemeproject&action=checkRepeat";
	 if ($("#id").val()) {
	 url += "&id=" + $("#id").val();
	 }
	$("#assesProName").ajaxCheck({
		 url : url,
		 alertText : "* 该名称已存在",
		 alertTextOk : "* 该名称可用"
	 });
	$("#assesProCode").ajaxCheck({
		 url : url,
		 alertText : "* 该编码已存在",
		 alertTextOk : "* 该编码可用"
	 });
  })