
equformalNo = true;
equtemporaryNo = true;
function CheckForm() {
	if (!equformalNo) {
		alert("������ͬ���Ѵ��ڣ�");
		$('#formalNo').focus();
		return (false);
	}
	if (!equtemporaryNo) {
		alert("��ʱ��ͬ���Ѵ��ڣ�");
		$('#temporaryNo').focus();
		return (false);
	}
	if ($('#formalNo').val() == "" && $('#temporaryNo').val() == "") {
		alert("������дһ����ͬ�ţ�");
		$('#formalNo').focus();
		return (false);
	}
	if ($('#contName').val() == "") {
		alert("��ͬ������Ҫ��д��");
		$('#contName').focus();
		return (false);
	}
	if ($('#money').val() == "") {
		alert("��ͬ�����Ҫ��д��");
		$('#money').focus();
		return (false);
	}
	if ($('#principalName').val() == "") {
		alert("��ͬ��������Ҫ��д��");
		$('#principalName').focus();
		return (false);
	}
	if ($('#deliveryDate').val() == "") {
		alert("����������Ҫ��д��");
		$('#deliveryDate').focus();
		return (false);
	}
	isCorret = checkMoney($("#money").val());
	if (!isCorret) {
		alert("����������");
		$("#money").focus();
		return false;
	}
	phoneNum = $('#customerTel').val();
	if (!checkPhone(phoneNum)) {
		return false;
	}

	mailNumber = $('#customerEmail').val();
	if (mailNumber != "") {
		thisMail = ismail(mailNumber);
		if (!thisMail) {
			return false;
		}
	}

	deliveryDate = $('#deliveryDate').val();

	equNumber = $('#EquNum').val();
	for (i = 1; i <= equNumber; ++i) {
		if (!$('#EquId' + i)[0])
			continue;
		else {
			equAmount = $('#EquAmount' + i).val();
			if (equAmount != "") {
				if (parseInt(equAmount) != equAmount) {
					alert('��������ȷ������');
					$('#EquAmount' + i).focus();
					return false;
				}
			}
			equPrice = $('#EquPrice' + i).val();
			if (equPrice != "") {
				if (!checkMoney(equPrice)) {
					alert('��������ȷ�ĵ���');
					$('#EquPrice' + i).focus();
					return false;
				}
			}
			equAllMoney = $('#EquAllMoney' + i).val();
			if (equAllMoney != "") {
				if (!checkMoney(equAllMoney)) {
					alert('��������ȷ�ĵ���');
					$('#EquAllMoney' + i).focus();
					return false;
				}
			}
			equDeliveryDT = $('#EquDeliveryDT' + i).val();
			if ($('#EquId' + i).val() != "" && equDeliveryDT == "") {
				$('#EquDeliveryDT' + i).val(deliveryDate);
			}
		}
	}

	licenseNumber = $('#licenseNum').val();
	for (i = 1; i <= licenseNumber; i++) {
		if (!$('#softdogAmount' + i)[0])
			continue;
		else {
			softdogAmount = $('#softdogAmount' + i).val();
			if (softdogAmount != "") {
				if (parseInt(softdogAmount) != softdogAmount) {
					alert('��������ȷ������');
					$('#softdogAmount' + i).focus();
					return false;
				}
			}
		}
	}

	preNum = $('#PreNum').val();
	for (i = 1; i <= preNum; i++) {
		if (!$('#PequID' + i)[0])
			continue;
		else {
			preAmount = $('#PreAmount' + i).val();
			if (preAmount != "") {
				if (parseInt(preAmount) != preAmount) {
					alert('��������ȷ������');
					$('#PreAmount' + i).focus();
					return false;
				}
			}
			prePrice = $('#PrePrice' + i).val();
			if (prePrice != "") {
				if (!checkMoney(prePrice)) {
					alert('��������ȷ�ĵ���');
					$('#PrePrice' + i).focus();
					return false;
				}
			}
			countMoney = $('#CountMoney' + i).val();
			if (countMoney != "") {
				if (!checkMoney(countMoney)) {
					alert('��������ȷ�ĵ���');
					$('#CountMoney' + i).focus();
					return false;
				}
			}
			preDeliveryDT = $('#PreDeliveryDT' + i).val();
			if ($('#PequName' + i).val() != "" && preDeliveryDT == "") {
				$('#PreDeliveryDT' + i).val(deliveryDate);
			}
		}
	}

	payNumber = $('#PayNum').val();
	for (i = 1; i <= payNumber; i++) {
		if (!$('#PayMoney' + i)[0])
			continue;
		else {
			payMoney = $('#PayMoney' + i).val();
			if (payMoney != "") {
				if (!checkMoney(payMoney)) {
					alert('��������ȷ���տ���');
					$('#PayMoney' + i).focus();
					return false;
				}
			}
			payDT = $('#PayDT' + i).val();
			if ($('#PayMoney' + i).val() != "" && payDT == "") {
				$('#PayDT' + i).val(deliveryDate);
			}
		}
	}

	return true;
}

function check_code(code) {
	if (code != '') {
		var rand = Math.random() * 100000;
		$.get('index1.php', {
			model : 'contract_sales_sales',
			action : 'checkRepeat',
			equformalNo : code,
			rand : rand
		}, function(data) {
			if (data != '') {
				$('#_formalNo').html('�Ѵ��ڵĺ�ͬ�ţ�');
				equformalNo = false;
			} else {
				$('#_formalNo').html('��ͬ�ſ��ã�');
				equformalNo = true;
			}
		})
	}
	return false;
}

function check_codeT(code) {
	if (code != '') {
		var rand = Math.random() * 100000;
		$.get('index1.php', {
			model : 'contract_sales_sales',
			action : 'checkRepeat',
			equtemporaryNo : code,
			rand : rand
		}, function(data) {
			if (data != '') {
				$('#_temporaryNo').html('�Ѵ��ڵĺ�ͬ�ţ�');
				equtemporaryNo = false;
			} else {
				$('#_temporaryNo').html('��ͬ�ſ��ã�');
				equtemporaryNo = true;
			}
		})
	}
	return false;
}