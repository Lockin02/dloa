$(document).ready(function() {
	if($("#ExaStatus").val()=="YSH"){
		$("#saveBtn").hide();
		$("#confirmBtn").hide();		
	}

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */
//	$("#itemTable").yxeditgrid({
//		objName : 'salarydoc[items]',
//		url : '?model=hr_leave_salarydocitem&action=pageItemJson',
//		param : {
//			mainId : $("#id").val()
//		},
//		colModel : [ {
//			name : 'id',
//			display : 'id',
//			type : 'hidden'
//		}, {
//			name : 'itemContent',
//			tclass : 'txt',
//			display : '值内容',
//			validation : {
//				required : true
//			}
//		} ]
//	})
})

/**
 * 保存表单
 * 
 * @param actionType
 */
function saveForm(actionType) {
	$("#form1").attr("action",
			"?model=hr_leave_salarydoc&action=edit&actType=" + actionType);
	$("#form1").submit();
}