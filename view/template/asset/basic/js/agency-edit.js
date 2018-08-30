$(document).ready(function() {
	validateForm();
	//区域编码，区域名称唯一性验证
	$("#agencyCode").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* 该区域编码已存在",
		alertTextOk : "* 该区域编码可用"
	});
	$("#agencyName").ajaxCheck({
		url : "?model=asset_basic_agency&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* 该区域名称已存在",
		alertTextOk : "* 该区域名称可用"
	});
})

/**
 * 验证信息
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