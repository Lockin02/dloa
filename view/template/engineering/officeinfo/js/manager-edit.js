$(document).ready(function() {
	//服务经理
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check',
		formCode : 'officeMainManager'
	});

	//归属公司
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
				}
			}
		}
	});

	//省份获取
	initProvince($("#provinceIdHidden").val());

	/**
	 * 验证信息
	 */
	validate({
		"businessBelongName" : {
			required : true
		}
	});
});