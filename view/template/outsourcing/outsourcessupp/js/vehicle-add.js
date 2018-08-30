$(document).ready(function() {

	$("#suppName").yxcombogrid_outsuppvehicle({
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                    $("#suppCode").val(data.suppCode);
                    $("#suppName").val(data.suppName);
                    $("#suppId").val(data.id);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#suppCode").val("");
				$("#suppName").val("");
				$("#suppId").val("");
			}
		}
	});

	validate({
		"rentPrice" : {
			required : true,
			custom : ['percentageNum']
		},
		"suppName" : {
			required : true
		},
		"carNumber" : {
			required : true
		},
		"phoneNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"idNumber" : {
			required : true
		}
	});
})