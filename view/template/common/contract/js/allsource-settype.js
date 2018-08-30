$(function(){

	/**
	 * 验证信息
	 */
	validate({
		"customTypeId" : {
			required : true
		},
		"warnDate" : {
			required : true
		}
	});


	$('#customTypeId').change(function(){
  	  	$('#customTypeName').val($('#customTypeId').get(0).options[$('#customTypeId').get(0).selectedIndex].innerText);
	})

})