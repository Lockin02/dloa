$(function(){

	/**
	 * ��֤��Ϣ
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