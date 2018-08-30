function validate(rule,setting) {
	// if (isCheckForm) {
	for (var id in rule) {
		validateSingle($("#" + id), rule[id])
	}
	$("form").validationEngine(setting);
	// }
}

function validateSingle($dom, validation,isValidationForm) {
	// var obj = rule[id];
	var str = "validate[required,";
	var domId = $dom.attr("id");
	if (validation['required'] != undefined && validation['required'] == false) {
		str = str.split("[")[0] + "[optional,";
	}
	for (var i in validation) {
		if (i == 'required') {
			continue;
		} else if (i == 'custom') {
			for (var j = 0; j < validation[i].length; j++) {
				str += "custom[" + validation[i][j] + "],";
			}
		} else if (i == 'ajax') {
			str += "ajax[" + validation[i] + "],";
		} else if (i == 'funcCall') {
			str += "funcCall[" + validation[i] + "],";
		} else {
			str += i + "[" + validation[i] + "],";
		}
	}
	str = str.substring(0, str.lastIndexOf(",")) + "]";
	// alert($dom.attr("id")+"===>"+str)
	$dom.addClass(str);
	//$("form").validationEngine();
}
$.fn.validation = function(validation) {
	validateSingle($(this), validation);
	// $("form").validationEngine();
}
