$(document)
		.ready(
				function() {

					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */
					// $("#itemTable").yxeditgrid({
					// objName : 'salarydoc[items]',
					// isAddOneRow : true,
					// colModel : [ {
					// name : 'itemContent',
					// tclass : 'txt',
					// display : 'ֵ����',
					// validation : {
					// required : true
					// }
					// } ]
					// })
					// ��ְ�嵥ģ��
					$("#schemeName")
							.yxcombogrid_hrsalaryplate(
									{
										hiddenId : 'schemeId',
										width : 600,
										gridOptions : {
											showcheckbox : false,
											event : {
												'row_dblclick' : function(e,
														row, data) {
													$(".clearClass").remove();
													$
															.ajax({
																type : "POST",
																url : "?model=hr_leave_salarytplate&action=addItemList",
																data : {
																	"mainId" : data.id
																},
																success : function(
																		data) {
																	$(
																			"#appendHtml")
																			.after(
																					data);
																}
															});
												}
											}
										}
									});
				})
/**
 * �����
 * 
 * @param actionType
 */
function saveForm(actionType) {
	$("#form1").attr("action",
			"?model=hr_leave_salarydoc&action=add&actType=" + actionType);
	$("#form1").submit();
}