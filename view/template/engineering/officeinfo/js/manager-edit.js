$(document).ready(function() {
	//������
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check',
		formCode : 'officeMainManager'
	});

	//������˾
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

	//ʡ�ݻ�ȡ
	initProvince($("#provinceIdHidden").val());

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"businessBelongName" : {
			required : true
		}
	});
});