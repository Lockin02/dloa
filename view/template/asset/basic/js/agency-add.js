$(document).ready(function() {
	validateForm();
	//������룬��������Ψһ����֤
	$("#agencyCode").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat",
		alertText : "* ����������Ѵ���",
		alertTextOk : "* ������������"
	});
	$("#agencyName").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat",
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
		"chargeName" : {
			required : true
		}
	});
	$("#chargeName").yxselect_user({
		hiddenId : 'chargeId'
	});
}