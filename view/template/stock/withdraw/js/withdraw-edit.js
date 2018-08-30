$(document).ready(function() {

   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */   	$("#itemTable").yxeditgrid({
		objName : 'withdraw[items]'',
		url : '?model=stock_withdraw_equ&action=pageItemJson',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
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
				}  })