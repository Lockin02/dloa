

//��֤
function check_all() {
	var currency = $("#currency").val();
	if (currency == '') {
		alert("�ұ���Ϊ�գ�")
		return false;
	}
	var currencyCode = $("#currencyCode").val();
	if (currencyCode == '') {
		alert("�ұ���벻��Ϊ�գ�")
		return false;
	}
	var rate = $("#rate").val();
	if (rate == ''){
	    alert("����ȷ��д���ʣ�");
	    return false;
	}
	return true;
}


function curr(){
	if ($('#currency').val() == '') {
		$('#icon').html('�ұ���Ϊ�գ�');
	} else {
		var param = {
			model : 'system_currency_currency',
			action : 'ajaxCurrency',
			ajaxCurrency : $('#currency').val()
		};
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('�Ѵ��ڵıұ�');
						$("#currency").focus();
					} else {
						$('#icon').html('�ұ���ã�');
					}
				})
	}
}