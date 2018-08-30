$(document).ready(function() {

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=pageItemJson',
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'itemContent',
			tclass : 'txt',
			display : 'ÖµÄÚÈÝ',
			validation : {
				required : true
			}
		} ]
	})
})