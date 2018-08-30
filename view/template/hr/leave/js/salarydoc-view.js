$(document).ready(function() {


      	$("#itemTable").yxeditgrid({
		objName : 'salarydoc[items]',
		url : '?model=hr_leave_salarydocitem&action=pageItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
					name : 'itemContent',
					tclass : 'txt',
					display : 'ÖµÄÚÈÝ',
					sortable : true
               }]
      });  })