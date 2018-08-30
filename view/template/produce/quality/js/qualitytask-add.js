$(document).ready(function() {

   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */      $("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		isAddOneRow : true,
		colModel : [{
					name : 'itemContent',
					tclass : 'txt',
					display : 'ÖµÄÚÈÝ',
					validation : {
						required : true
					}
               }]
     })  })