
// ѡ��license
$(function() {
	$("#licenseType1").yxcombogrid_licenseType({
		hiddenId : 'licenseNodeId1',
		gridOptions : {
			showcheckbox : true,
			event : {
				'after_row_check' : function(e, checkbox, row, data) {
					// alert($('#licenseNodeId1').val())
					$('#licenseinput1').val($('#licenseNodeId1').val());
				}
			}
		}
	});

});

// ѡ��ʡ��
$(function() {
	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
// ѡ�񸶿λ--Ĭ��Ϊ�ͻ�
$(function() {

	$("#payUnit").yxcombogrid_customer({
		hiddenId : 'payUnitId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
// �ͻ���ͻ���ϵ������
$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert(i);
					var getGrid = function() {
						return $("#linkman1").yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#linkman1")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}
					$("#customerType").val(data.TypeOne);
					$("#provincecity").val(data.Prov);
					$("#customerId").val(data.id);
					$('#mylink input').val('');
				}
			}
		}
	});

	$("#linkman1").yxcombogrid_linkman({
		hiddenId : 'linkmanId1',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#telephone1").val(data.phone);
					$("#Email1").val(data.email);
				}
			}
		}
	});
	// ��ѡ�豸
	$("#EquName1").yxcombogrid_product({
		hiddenId : 'ProductId1',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					$("#EquId1").val(data.sequence);
					$("#EquModel1").val(data.pattern);
				}
			}
		}
	});
});

// ������������
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
		$.each($(':input[id^="EquDeliveryDT"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

	$('#invoiceType').bind('focusout', function() {
		var thisDate = $(this).val();
		$.each($(':input[id^="invoiceListType"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});
});

// ѡ��license���ʹٷ�
function lissel(t, count) {
	$
			.post(
					'index1.php?model=product_licensetype_licensetype&action=getRelateId',
					{
						id : t.value
					}, function(data) {
						$("#licenseinput" + count).val(data);
					});
}

// �����Ⱦ
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
//������ͬ��Ψһ����֤
function check_code(code) {

	if (code != '') {
		$.get('index1.php', {
			model : 'contract_sales_sales',
			action : 'checkRepeat',
			equcontNumber : code
		}, function(data) {
			if (data != '') {
				$('#_contNumber').html('�Ѵ��ڵĺ�ͬ�ţ�');
				equformalNo = false;
			} else {
				$('#_contNumber').html('��ͬ�ſ��ã�');
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

function saveContract() {
	document.getElementById('form1').action = "index1.php?model=contract_sales_sales&action=add&act=save";
}

function openDia(count) {
	var licensetypeid = "licenseinput" + count;
	var licensetype = $("#" + licensetypeid).val();
	if (!licensetype) {
		alert("����ѡ��license���ͣ�");
	} else {
		var hideid = "licenseNodeId" + count;
		var textid = "licenseNodeName" + count;

		var hidevalue = $("#" + hideid).val();

		// var thisurl =
		// '?model=product_licensecheck_licensecheck&action=setInHtml&contractLicenseId=-1&hidevalue='+hidevalue+'&hideid='+hideid+'&textid='+textid;
		var thisurl = '?model=product_licensetype_licensetype&action=viewPages&ids='
				+ licensetype
				+ '&hidevalue='
				+ hidevalue
				+ '&hideid='
				+ hideid
				+ '&textid=' + textid;
		window.open(thisurl);
	}
}

var uploadfile;
$(function() {
	uploadfile = createSWFUpload({
		"serviceType" : "oa_contract_sales"
	});
});

// ����
// function salesBack() {
// location="?model=projectmanagent_order_order&action=myChargeOrder";
// }
/** ******************************************************************************************************** */
// license����
// var licenseTypeArr = [];
var invoiceTypeArr = [];
var licensetypeStore = null;// license����store
var licensetypeRecords = [];// license���ͼ�¼����
/*
 * ��̬���license����
 *
 * function addLicenseType(licenseTypeId, licenseTypeArr) { for (var i = 0, l =
 * licenseTypeArr.length; i < l; i++) { $("#" + licenseTypeId).append("<option
 * value='" + licenseTypeArr[i].id + "'>" + licenseTypeArr[i].typeName + "</option>"); } }
 */

// ��Ʒ������
$(function() {

	// ��Ʊ����
	invoiceTypeArr = getData('FPLX');
	if (!$("#contNumber").val()) {
		addDataToSelect(invoiceTypeArr, 'invoiceType');
		addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	}

//	// �ͻ�����
//	customerTypeArr = getData('KHLX');
//	if (!$("#contNumber").val()) {
//		addDataToSelect(customerTypeArr, 'customerType');
//		addDataToSelect(customerTypeArr, 'customerListTypeArr1');
//	}
});

// **************��Ʊ�ƻ�******************

function inv_add(myinv, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myinv = document.getElementById(myinv);
	i = myinv.rows.length;
	oTR = myinv.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ i + "][money]' id='InvMoney" + mycount + "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ i + "][softM]' id='InvSoftM" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtmiddle' name='order[invoice][" + i
			+ "][iType]' id='invoiceListType" + mycount + "'></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ i
			+ "][invDT]' id='InvDT"
			+ mycount
			+ "' onfocus='WdatePicker()'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='order[invoice]["
			+ i + "][remark]' id='InvRemark" + mycount + "' />";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myinv.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(invoiceTypeArr, 'invoiceListType' + mycount);

	createFormatOnClick('InvMoney' + mycount);
	createFormatOnClick('InvSoftM' + mycount);
}

// **********************�Զ����嵥******************
function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][productnumber]' id='PequID" + mycount + "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][name]' id='PequName" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][prodectmodel]' id='PreModel" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][amount]' id='PreAmount"
			+ mycount
			+ "' onblur='FloatMul(\"PreAmount"
			+ mycount
			+ "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount + "\")'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][price]' id='PrePrice"
			+ mycount
			+ "' onblur='FloatMul(\"PreAmount"
			+ mycount
			+ "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount + "\")'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][countMoney]' id='CountMoney" + mycount + "'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][projArraDT]' id='PreDeliveryDT"
			+ mycount
			+ "' onfocus='WdatePicker()'>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='order[customizelist]["
			+ mycount + "][remark]' id='PRemark" + mycount + "'/>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='order[customizelist]["
			+ mycount + "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	createFormatOnClick('PrePrice' + mycount, 'PreAmount' + mycount, 'PrePrice'
			+ mycount, 'CountMoney' + mycount);
	createFormatOnClick('CountMoney' + mycount, 'PreAmount' + mycount,
			'PrePrice' + mycount, 'CountMoney' + mycount);
}
/**
 *
 * @param {}
 *            mycount ��Ⱦ��ϵ�������б�
 *
 */
function reloadLinkman(linkman) {
	var getGrid = function() {
		return $("#" + linkman).yxcombogrid_linkman("getGrid");
	}
	var getGridOptions = function() {
		return $("#" + linkman).yxcombogrid_linkman("getGridOptions");
	}
	if (!$('#customerId').val()) {
	} else {
		if (getGrid().reload) {
			getGridOptions().param = {
				customerId : $('#customerId').val()
			};
			getGrid().reload();
		} else {
			getGridOptions().param = {
				customerId : $('#customerId').val()
			}
		}
	}
}

// ************************�ͻ���ϵ��*************************
function link_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;

	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	oTR.id = "linkDetail" + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txt' type='hidden' name='order[linkman]["
			+ mycount + "][linkmanId]' id='linkmanId" + mycount + "'/>"
			+ "<input class='txt' type='text' name='order[linkman][" + mycount
			+ "][linkman]' id='linkman" + mycount
			+ "' onclick=\"reloadLinkman('linkman" + mycount + "\');\"/>";

	/**
	 * �ͻ���ϵ��
	 */
	$("#linkman" + mycount).yxcombogrid_linkman({
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						// alert( "linkman" + mycount );
						$("#linkmanId" + mycount).val(data.id);
						$("#telephone" + mycount).val(data.phone);
						$("#Email" + mycount).val(data.email);
					};
				}(mycount)
			}
		}
	});

	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txt' type='text' name='order[linkman]["
			+ mycount + "][telephone]' id='telephone" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='order[linkman]["
			+ mycount + "][Email]' id='Email" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[linkman]["
			+ mycount + "][remark]' id='remark" + mycount + "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// ************************�տ�ƻ�*************************
function pay_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan]["
			+ mycount + "][money]' id='PayMoney" + mycount + "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan]["
			+ mycount
			+ "][payDT]' id='PayDT"
			+ mycount
			+ "' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='order[receiptplan]["
			+ mycount
			+ "][pType]'><option value='���'>���</option><option value='�ֽ�'>�ֽ�</option><option value='���л�Ʊ'>���л�Ʊ</option><option value='��ҵ��Ʊ'>��ҵ��Ʊ</option></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[receiptplan]["
			+ mycount
			+ "][collectionTerms]' id='collectionTerms"
			+ mycount
			+ "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' style='width: 5%' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney' + mycount);
}

// **********��ѵ�ƻ�***********************

function train_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount
			+ "][beginDT]' id='TraDT"
			+ mycount
			+ "' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount
			+ "][endDT]' id='TraEndDT"
			+ mycount
			+ "' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount + "][traNum]'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][trainerDemand]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// *****************ɾ�����������******************************
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

// *****************��Ʒ�嵥������ʾ��1******************************
function soft_add(myequ, countNum) {
	deliveryDate = $('#deliveryDate').val();
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myequ = document.getElementById(myequ);
	i = myequ.rows.length;
	oTR = myequ.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount + "][productNumber]' id='EquId" + mycount + "' />";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input  type='text' class='txtmiddle' name='order[equipment]["
			+ mycount
			+ "][productName]' id='EquName"
			+ mycount
			+ "'/>"
			+ "<input type='hidden' name='order[equipment]["
			+ mycount
			+ "][productId]' id='ProductId" + mycount + "'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount + "][productModel]' id='EquModel" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount
			+ "][amount]' id='EquAmount"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount + "\",\"EquAllMoney" + mycount + "\",2)'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount
			+ "][price]' id='EquPrice"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount + "\",\"EquAllMoney" + mycount + "\",2)'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount + "][countMoney]' id='EquAllMoney" + mycount + "'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='order[equipment]["
			+ mycount
			+ "][projArraDate]' id='EquDeliveryDT"
			+ mycount
			+ "' value='" + deliveryDate + "' onfocus='WdatePicker()'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<select class='txtshort' name='order[equipment]["
			+ mycount + "][warrantyPeriod]' id='warrantyPeriod" + mycount
			+ "'>" + "<option value='����'>����</option>"
			+ "<option value='һ��'>һ��</option>"
			+ "<option value='����'>����</option>"
			+ "<option value='����'>����</option>" + "</select>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='order[equipment]["
			+ mycount + "][isSell]' checked='checked'>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myequ.id + "\")' title='ɾ����'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	createFormatOnClick('EquPrice' + mycount, 'EquAmount' + mycount, 'EquPrice'
			+ mycount, 'EquAllMoney' + mycount);
	createFormatOnClick('EquAllMoney' + mycount, 'EquAmount' + mycount,
			'EquPrice' + mycount, 'EquAllMoney' + mycount);

	var countNum = $('#EquNum').val();
	// ��ѡ��Ʒ
	$("#EquName" + mycount).yxcombogrid_product({
		hiddenId : 'ProductId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						$("#EquId" + mycount).val(data.sequence);
						$("#EquModel" + mycount).val(data.pattern);
					};
				}(mycount)
			}
		}
	});
}

/** **********************************������Ϣ**************************** */
function license_add(mylicense, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mylicense = document.getElementById(mylicense);
	i = mylicense.rows.length;
	oTR = mylicense.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<select class='txtshort' name='order[licenselist]["
			+ mycount + "][softdogType]' id='softdogType" + mycount
			+ "'><option value='HASP'>HASP</option></select>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[licenselist]["
			+ mycount + "][amount]' id='softdogAmount" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtmiddle' type='text' value='' id='licenseType"
			+ mycount
			+ "' name='order[licenselist]["
			+ mycount
			+ "][licenseType]'/><input type='hidden' value='' id='licenseinput"
			+ mycount
			+ "' name='order[licenselist]["
			+ mycount
			+ "][licenseTypeIds]'/>";

	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='hidden' name='order[licenselist]["
			+ mycount
			+ "][nodeId]' id='licenseNodeId"
			+ mycount
			+ "'><textarea name='order[licenselist]["
			+ mycount
			+ "][nodeName]' onclick='openDia("
			+ mycount
			+ ")' id='licenseNodeName"
			+ mycount
			+ "' title='ѡ�������Ϣ'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<select class='txtshort' name='order[licenselist]["
			+ mycount
			+ "][validity]' id='validity"
			+ mycount
			+ "'><option value='����'>����</option><option value='һ��'>һ��</option><option value='����'>����</option></select>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txt' type='text' name='order[licenselist]["
			+ mycount + "][remark]' id='licenseRemark" + mycount + "'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input  type='checkbox' name='order[licenselist]["
			+ mycount + "][isSell]' id='licenseisSell" + mycount
			+ "' checked='checked' />";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mylicense.id + "\")' title='ɾ����'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	$(function() {
		$("#licenseType" + mycount).yxcombogrid_licenseType({
			hiddenId : 'licenseinput' + mycount,
			gridOptions : {
				showcheckbox : true,
				event : {
					'after_row_check' : function(mycount) {
						return function(e, checkbox, row, data) {
						}
					}(mycount)
				}
			}
		});
	});
}

// *******************���ؼƻ�********************************
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

// ************************��Ʊ���͵������ֵ��滻******************************
$(function() {
	invoeceType = getData('FPLX');
	addDataToSelect(invoeceType, 'invoeceType');
});

// *****************************��Ʒ��***********************************

