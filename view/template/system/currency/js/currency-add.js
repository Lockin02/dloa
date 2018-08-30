

//验证
function check_all() {
	var currency = $("#currency").val();
	if (currency == '') {
		alert("币别不能为空！")
		return false;
	}
	var currencyCode = $("#currencyCode").val();
	if (currencyCode == '') {
		alert("币别编码不能为空！")
		return false;
	}
	var rate = $("#rate").val();
	if (rate == ''){
	    alert("请正确填写汇率！");
	    return false;
	}
	return true;
}


function curr(){
	if ($('#currency').val() == '') {
		$('#icon').html('币别不能为空！');
	} else {
		var param = {
			model : 'system_currency_currency',
			action : 'ajaxCurrency',
			ajaxCurrency : $('#currency').val()
		};
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('已存在的币别！');
						$("#currency").focus();
					} else {
						$('#icon').html('币别可用！');
					}
				})
	}
}