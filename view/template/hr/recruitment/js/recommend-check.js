$(document).ready(function() {
	$("#assistManNameTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		defaultClass : 'txtlong',
		isFristRowDenyDel : true,
		isAddOneRow : true,
		colModel : [{
			display : '����������',
			name : 'assistManName',
			validation : {
				required : true
			}
		}]
	});
	$("#recruitManName").yxselect_user({
		hiddenId : 'recruitManId'
	});
	$("#assistManName").yxselect_user({
		mode : 'check',
		hiddenId : 'assistManId'
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })