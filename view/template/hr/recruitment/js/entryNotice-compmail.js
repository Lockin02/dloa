$(document).ready(function() {
	validate({
		"entryDate" : {
			required : true
		},
		"TO_NAME" : {
			required : true
		}
	});

	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode:'check'
	});

	$( 'textarea.editor' ).ckeditor();

/**
 * Ìî³äÄ£°æÄÚÈÝ
 * @param formwork
 * @param remark
 */
function fillTemp(formwork,remark){
	$("#formwork").val(formwork);
	$("#remark").val(remark);
}
});