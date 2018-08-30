/**
 * ��ѡʡ��
 */
$(function() {

	$("#province").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

/**
 * ��ѡlicense
 */
$(function(){
	var licensetypenum = $('#licensetypenum').val();
	for( var i = 1; i <= licensetypenum; i++){
		$(function() {
			$("#licenseType" + i).yxcombogrid_licenseType({
				hiddenId : 'licenseinput' + i,
				gridOptions : {
					showcheckbox : true,
					event : {
						'after_row_check' : function(i){
								return function(e, checkbox, row, data){
							}
						}(i)
					}
				}
			});
		});
	}
});

/**
 * ��ѡ��ϵ��
 */
$(function(){
	var linkNum = $('#linkNum').val();
	for( var i = 1; i <= linkNum; i++ ){
		$("#linkman" + i).yxcombogrid_linkman({
			hiddenId : 'linkmanId' + i,
			gridOptions : {
				reload : true,
				showcheckbox : false,
				// param : param,
				event : {
					'row_dblclick' : function(i){
							return function(e, row, data) {
							$("#telephone" + i).val(data.phone);
							$("#Email" + i).val(data.email);
						}
					}(i)
				}
			}
		});
	}
});


/**
 * ��ѡ�ͻ�
 */
$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerType").val(data.TypeOne);
					$("#province").val(data.Prov);
					$("#customerId").val(data.id);
					$('#mylink input').val('');
				}
			}
		}
	});

});

/**
 * ��ѡ��Ʒ
 */
$(function(){
	var equNum = $('#EquNum').val();
	for( var i = 1; i <= equNum; i++ ){
		$("#EquName" + i).yxcombogrid_product({
			hiddenId : 'ProductId' + i,
			gridOptions : {
				reload : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							// alert( $('#customerId').val() );
							$('#ProductId' + i).val(data.id);
							$("#EquId" + i).val(data.sequence);
							$("#EquModel" + i).val(data.pattern);
						}
					}(i)
				}
			}
		});
	}
});



(function(){
	$('#deliveryDate').bind('focusout',function(){
		var thisDate = $(this).val();
		$.each($(':input[id^="EquDeliveryDT"]'),function(i,n){
			$(this).val(thisDate);
		})
    });

	$('#invoiceType').bind('focusout',function(){
		var thisDate = $(this).val();
		$.each($(':input[id^="invoiceListType"]'),function(i,n){
			$(this).val(thisDate);
		})
    });
});


function CheckForm() {
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
	if ($('#customerName').val() == "") {
		alert("�ͻ�������Ҫ��д��");
		$('#customerName').focus();
		return (false);
	}
	isCorret = checkMoney($("#money").val());
	if (!isCorret) {
		alert("����������");
		$("#money").focus();
		return false;
	}
//	phoneNum = $('#customerTel').val();
//	if (!checkPhone(phoneNum)) {
//		return false;
//	}
//
//	mailNumber = $('#customerEmail').val();
//	if (mailNumber != "") {
//		thisMail = ismail(mailNumber);
//		if (!thisMail) {
//			return false;
//		}
//	}

	deliveryDate = $('#deliveryDate').val();

	equNumber = $('#EquNum').val();
	for (i = 1; i <= equNumber; ++i) {
		if (!$('#EquId' + i)[0]) continue;
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
			if ($('#EquId' + i).val() != "" && ��equDeliveryDT == "") {
				$('#EquDeliveryDT' + i).val(deliveryDate);
			}
		}
	}

	licenseNumber = $('#licenseNum').val();
	for (i = 1; i <= licenseNumber; i++) {
		if (!$('#softdogAmount' + i)[0]) continue;
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
		if (!$('#PequID' + i)[0]) continue;
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
			if ($('#PequName' + i).val() != "" && ��preDeliveryDT == "") {
				$('#PreDeliveryDT' + i).val(deliveryDate);
			}
		}
	}

	payNumber = $('#PayNum').val();
	for (i = 1; i <= payNumber; i++) {
		if (!$('#PayMoney' + i)[0]) continue;
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
			if ($('#PayMoney' + i).val() != "" && ��payDT == "") {
				$('#PayDT' + i).val(deliveryDate);
			}
		}
	}

	return true;
}

function check_code(code) {
	if (code == '') {
		$('#_contNumber').html('��ͬ�Ų���Ϊ�գ�');
	} else {
		var rand = Math.random() * 100000;
		$.get('index1.php', {
			model: 'contract_sales_sales',
			action: 'checkRepeat',
			equC: code,
			rand: rand
		},
		function(data) {
			if (data != '') {
				$('#_contNumber').html('�Ѵ��ڵĺ�ͬ�ţ�');
				contnumber = false;
			} else {
				$('#_contNumber').html('��ͬ�ſ��ã�');
				contnumber = true;
			}
		})
	}
	return false;
}

function saveContract(){
	document.getElementById('form1').action="index1.php?model=contract_sales_sales&action=editForBackAction&act=save";
}

function openDia(count){
	var licensetypeid="licenseinput"+count;
	var licensetype=$("#"+licensetypeid).val();
	if(!licensetype){
		alert("����ѡ��license���ͣ�");
	}else{
		var hideid="licenseNodeId"+count;
		var textid="licenseNodeName"+count;

		var hidevalue = $("#"+hideid).val();

		//var thisurl = '?model=product_licensecheck_licensecheck&action=setInHtml&contractLicenseId=-1&hidevalue='+hidevalue+'&hideid='+hideid+'&textid='+textid;
		var thisurl = '?model=product_licensetype_licensetype&action=viewPages&ids='+licensetype+'&hidevalue='+hidevalue+'&hideid='+hideid+'&textid='+textid;
		window.open(thisurl );
	}
}