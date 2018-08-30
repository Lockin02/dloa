$(document).ready(function() {
	validate({
		"personName" : {
			required : true
		},
		"phone" : {
			required : true
		},
		"specialty" : {
			required : true
		}
	});


    var countryId = $("#countryId").val();
    var provinceId = $("#provinceId").val();
    var cityId = $("#cityId").val();
    $("#country").val(countryId);//所属国家Id
    $("#country").trigger("change");
    $("#province").val(provinceId);//所属省份Id
    $("#province").trigger("change");
	$("#city").val(cityId);//城市ID
	$("#city").trigger("change");
})