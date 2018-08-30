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
		"suppName" : {
			required : true
		},
		"blackReason" : {
			required : true
		}
	});
})