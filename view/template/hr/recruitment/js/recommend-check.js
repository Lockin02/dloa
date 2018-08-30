$(document).ready(function() {
	$("#assistManNameTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		defaultClass : 'txtlong',
		isFristRowDenyDel : true,
		isAddOneRow : true,
		colModel : [{
			display : '负责人姓名',
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