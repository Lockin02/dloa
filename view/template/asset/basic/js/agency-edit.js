$(document).ready(function() {
	validateForm();
	//������룬��������Ψһ����֤
	$("#agencyCode").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* ����������Ѵ���",
		alertTextOk : "* ������������"
	});
	$("#agencyName").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* �����������Ѵ���",
		alertTextOk : "* ���������ƿ���"
	});
})

/**
 * ��֤��Ϣ
 */
function validateForm() {
	validate({
		"agencyCode" : {
			custom : ['noSpecialCaracters']
		},
		"agencyName" : {
			required : true
		},
		"chargeName" : {
			required : true
		}
	});
	$("#chargeName").yxselect_user({
		hiddenId : 'chargeId'
	});
}