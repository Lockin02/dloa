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
    $("#country").val(countryId);//��������Id
    $("#country").trigger("change");
    $("#province").val(provinceId);//����ʡ��Id
    $("#province").trigger("change");
	$("#city").val(cityId);//����ID
	$("#city").trigger("change");
})