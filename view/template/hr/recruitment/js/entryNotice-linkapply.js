
$(function() {
	$("#applyCode").yxcombogrid_interview({
		hiddenId : 'applyId',
		width : 500,
		nameCol:'employmentCode',
		isFocusoutCheck:false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e, row, data) {
					$("#applyCode").val(data.employmentCode);
				}
			},
			showcheckbox : false
		}
	});
	/**
	 * 验证信息
	 */
	validate({
		"applyCode" : {
			required : true
		}
	});
});