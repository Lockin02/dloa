$(document).ready(function() {

	/**
	 * ��֤��Ϣ
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
	 * ������Ŀ����Ψһ����֤
	 */
	 var url = "?model=supplierManage_scheme_schemeproject&action=checkRepeat";
	 if ($("#id").val()) {
	 url += "&id=" + $("#id").val();
	 }
	$("#assesProName").ajaxCheck({
		 url : url,
		 alertText : "* �������Ѵ���",
		 alertTextOk : "* �����ƿ���"
	 });
	$("#assesProCode").ajaxCheck({
		 url : url,
		 alertText : "* �ñ����Ѵ���",
		 alertTextOk : "* �ñ������"
	 });
  })