$(document).ready(function() {
	// ¸ÇÕÂÀà±ğ
	customerTypeArr = getData('GZLB');
	addDataToSelect(customerTypeArr, 'stamp_cId');

	validate({
		"matterName" : {
			required : true
		},
		"stamp_cId" : {
			required : true
		}
	});

});