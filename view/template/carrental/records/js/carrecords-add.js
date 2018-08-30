$(document).ready(function() {
	validate({
		"projectName" : {
			required : true
		},
		"carNo" : {
			required : true
		},
		"carType" : {
			required : true
		},
		"driver" : {
			required : true
		},
		"linkPhone" : {
			required : true
		}
	});
	$("#projectName").yxcombogrid_project({
		hiddenId : 'projectId',
		width : 600,
		isFocusoutCheck : false,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#projectName").val(data.projectName);
					$("#projectId").val(data.projectId);
					$("#projectCode").val(data.projectCode);
				}
			}
		}
	});

	$("#carNo").yxcombogrid_carrecords({
		hiddenId : 'carId',
		width : 600,
		isFocusoutCheck : false,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param : {
				'status' : 0
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#carType").val(data.carType);
				}
			}
		}
	});

  })