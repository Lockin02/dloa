//��֤������ת���۵Ŀ�ִ������
						 function num(){
							   temp = $("#borproductNumber").val();
							   for(var i=1;i<= temp;i++){
							   	   var Num = $("#num"+i).val();
							       var proNum = $("#bornumber"+i).val();
							       if(proNum*1 > Num*1){
							          alert("��Ʒ�����ѳ���������д����");
							          $("#bornumber"+i).val(Num);
							       }
							       if(proNum*1 <= 0){
							          alert("��Ʒ��������Ϊ��0��");
							          $("#bornumber"+i).val(Num);
							       }
							   }
							}

function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrow&action=toOrderAdd&act=app";

}
function toSave() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrow&action=toOrderAdd";

}
// ���ʼ���
function conversion() {
	var currency = $("#currency").val();

	if (currency != '�����') {
		document.getElementById("currencyRate").style.display = "";
		$("#cur").html("(" + currency + ")");
		$("#cur1").html("(" + currency + ")");
		$("#orderTempMoney_v").attr('class', "readOnlyTxtNormal");
		$("#orderMoney_v").attr('class', "readOnlyTxtNormal");

		var tempMoney = $("#orderTempMoneyCur").val();
		var Money = $("#orderMoneyCur").val();
		var rate = $("#rate").val();
		$("#orderTempMoney_v").val(moneyFormat2(tempMoney * rate));
		$("#orderTempMoney").val(tempMoney * rate);
		$("#orderMoney_v").val(moneyFormat2(Money * rate));
		$("#orderMoney").val(Money * rate);
	}else if(currency == '�����'){
        $("#orderMoney_v").attr('class',"txt");
        $("#orderMoney_v").attr('readOnly',false);
        $("#orderTempMoney_v").attr('class',"txt");
        $("#orderTempMoney_v").attr('readOnly',false);
        $("#currencyRate").hide();
	}
}
// ѡ����ұ�
$(function() {
	$("#currency").yxcombogrid_currency({
		hiddenId : 'id',
//		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#rate").val(data.rate);
					conversion();
				}
			}
		}
	});
	createFormatOnClick("orderTempMoney_c");
	createFormatOnClick("orderMoney_c");
});
/**
 * license
 *
 * @type
 */

function License(licenseId, actType) {
	var licenseVal = $("#" + licenseId).val();
	if (licenseVal == "") {
		// ���Ϊ��,�򲻴�ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	} else {
		// ��Ϊ����ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&licenseId='
				+ licenseVal
				+ '&actType='
				+ actType
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

// ��дid
function setLicenseId(licenseId, thisVal) {
	$('#' + licenseId).val(thisVal);
}

var orderIdArr = [];
var orderTempCodeArr = [];

function toSelect() {
	// ѡ�񶩵�
//	$("#orderTempCode").yxcombogrid_orderTempCode({
	$("#orderTempCode").yxcombogrid_allorder({
		nameCol : 'orderTempCode',
		hiddenId:'orderId',
		isDown:false,
		gridOptions : {
			showcheckbox : true,
			param : {
				'ExaStatus' : '���',
				'orderCodeNull' : 1,
				'states' : '2',
				'tablename' : 'oa_sale_order',
				'prinvipalId' : $("#prinvipalId").val()
			},
			event : {
				'row_check' : function(e, col, row, data) {
//					$('#orderTempCode').val(data.orderTempCode);
					$('#invoiceType').val(data.invoiceType);
					$('#deliveryDate').val(data.deliveryDate);
					$('#customerId').val(data.customerId);
					$('#customerName').val(data.customerName);
					$('#customerType').val(data.customerType);
					$('#customerProvince').val(data.customerProvince);
					// ��ȡ�ӱ�����
					$.post(
									"?model=projectmanagent_order_order&action=ajaxList",
									{
										"id" : data.orgid
									}, function(data) {
										var obj = eval("(" + data + ")");

										index = jQuery.inArray(obj.id,
												orderIdArr);
										if (index != -1) {
											// �����,��ɾ�������еĸýڵ�id
											orderIdArr.splice(index, 1);
											$("tr[name^='equTab_" + obj.id
													+ "']").remove();
											recount('myequ');
											$("tr[name^='invTab_" + obj.id
													+ "']").remove();
											recount('myinv');
											$("tr[name^='payTab_" + obj.id
													+ "']").remove();
											recount('mypay');
											$("tr[name^='trainingTab_" + obj.id
													+ "']").remove();
											recount('mytra');
										} else {
											// ���û��,��id����������
											orderIdArr.push(obj.id);
											// �豸
											if (obj.orderequ) {
												// $("#myequ").html("");
												initEqu(obj.orderequ, 'myequ',
											 			obj.id);
											}
											// ��Ʊ�ƻ�
											if (obj.invoice) {
												// $("#myinv").html("");
												invlist(obj.invoice, 'myinv',
														obj.id);
											}
											// �տ�ƻ�
											if (obj.receiptplan) {
												// $("#mypay").html("");
												mypaylist(obj.receiptplan,
														'mypay', obj.id);
											}
											// ��ѵ�ƻ�
											if (obj.trainingplan) {
												// $("#mytra").html("");
												traininglist(obj.trainingplan,
														'mytra', obj.id);
											}
										}
									});
				}
			}
		}
	});
}

// TODO;grid��ʼ���豸
function initEqu(thisObj, tableId, orderId) {
	var tableObj = $('#' + tableId);
	var objLength = thisObj.length;
	var productNumber = $("#productNumber");
	var inStr = "";
	// ��ȡ��ǰ����
	var rowNum = $("tr[name^='equTab_']").length * 1
			+ $("tr[id^='equTab_']").length * 1;
	// ��ǰ����ֵ
	var i = productNumber.val() * 1;
	var j = 0;
	for (var k = 0; k < objLength; k++) {
		rowNum++;
		i++;
		inStr = "<tr name='equTab_" + orderId + "'><td>" + rowNum + "</td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i+ "][productNo]' id='EquId"+ i+ "' value='"+ thisObj[j].productNo+ "'/></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][productName]' id='EquName" + i + "' value='" + thisObj[j].productName + "'>"
				+ "<input type='hidden' name='order[orderequ][" + i + "][productId]' id='productId" + i + "' value='" + thisObj[j].productId + "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][productModel]' id='EquModel" + i + "' value='" + thisObj[j].productModel + "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][number]' id='EquAmount" + i + "' value='" + thisObj[j].number + "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][price]' id='EquPrice" + i + "' value='" + thisObj[j].price + "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][money]' id='EquAllMoney" + i + "' value='" + thisObj[j].money + "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='order[orderequ][" + i + "][projArraDate]' id='EquDeliveryDT" + i + "' value='" + thisObj[j].projArraDate+ "' onfocus='WdatePicker()'></td>"
				+ "<td><input type='text' class='readOnlyTxtItem' name='order[orderequ]["+i+"][warrantyPeriod]' id='warrantyPeriod"+i+"' value='"+thisObj[j].warrantyPeriod+"'/></td>"
				+ "<td><input type='hidden' id='licenseId"+ i+ "' name='order[orderequ]["+ i+ "][license]' value='"+ thisObj[j].license+ "'/>"
				+ "<input type='button' class='txt_btn_a' value='����' onclick='License(\"licenseId"+ i+ "\");'/></td>"
				+ "<td><input type='checkbox'  name='order[orderequ]["+ i+ "][isSell]' checked='checked'></td>"
				+ "<td><img src='images/closeDiv.gif' onclick='mydel(this,\"myequ\")' title='ɾ����'></td>"
				+ "</tr>";
		// ����Ԫ��
		tableObj.append(inStr);
		productNumber.val(i);
		j = k + 1;
	}
	// return inStr;
}

// TODO;grid��ʼ����Ʊ�ƻ�
function invlist(thisObj, tableId, orderId) {
	var tableObj = $('#' + tableId);
	var objLength = thisObj.length;
	var inStr = "";
	var InvNum = $("#InvNum");

	// ��ȡ��ǰ����
	var rowNum = $("tr[name^='invTab_']").length * 1
			+ $("tr[id^='invTab_']").length * 1;
	// ��ǰ����ֵ
	var i = InvNum.val() * 1;
	var j = 0;
	for (var k = 0; k < objLength; k++) {
		rowNum++;
		i++;
		inStr = "<tr name='invTab_"
				+ orderId
				+ "'><td>"
				+ i
				+ "</td>"
				+ "<td><input class='txtshort' type='text' name='order[invoice]["
				+ i
				+ "][money]' id='InvMoney"
				+ i
				+ "' value='"
				+ thisObj[j].money
				+ "'/></td>"
				+ "<td><input class='txtshort' type='text' name='order[invoice]["
				+ i
				+ "][softM]' id='InvSoftM"
				+ i
				+ "' value='"
				+ thisObj[j].softM
				+ "'/></td>"
				+ "<td><select class='txtmiddle' name='order[invoice]["
				+ i
				+ "][iType]' id='invoiceListType"
				+ i
				+ "'></select></td>"
				+ "<td><input class='txtshort' type='text' name='order[invoice]["
				+ i
				+ "][invDT]' id='InvDT"
				+ i
				+ "' value='"
				+ thisObj[j].invDT
				+ "'/></td>"
				+ "<td><input class='txtlong' type='text' name='order[invoice]["
				+ i + "][remark]' id='InvRemark" + i + "' value='"
				+ thisObj[j].remark + "'/></td>"
				+ "<td><img src='images/closeDiv.gif' onclick='mydel(this,\""
				+ tableId + "\")' title='ɾ����'></td>"
		"</tr>";
		// ����Ԫ��
		tableObj.append(inStr);
		InvNum.val(i);
		addDataToSelect(invoiceTypeArr, 'invoiceListType' + i);
		$('#invoiceListType' + i).val(thisObj[j].iType);
		j = k + 1;
	}
	// $('#InvNum').val(j);

}
// TODO;grid�տ�ƻ�
function mypaylist(thisObj, tableId, orderId) {
	var tableObj = $('#' + tableId);
	var objLength = thisObj.length;
	var inStr = "";
	var PayNum = $("#PayNum");
	// ��ȡ��ǰ����
	var rowNum = $("tr[name^='payTab_']").length * 1
			+ $("tr[id^='payTab_']").length * 1;
	// ��ǰ����ֵ
	var i = PayNum.val() * 1;
	var j = 0;
	for (var k = 0; k < objLength; k++) {
		rowNum++;
		i++;
		inStr = "<tr name='payTab_"
				+ orderId
				+ "'><td>"
				+ i
				+ "</td>"
				+ "<td><input class='txtshort' type='text' name='order[receiptplan]["
				+ i
				+ "][money]' id='PayMoney"
				+ i
				+ "' value='"
				+ thisObj[j].money
				+ "'/></td>"
				+ "<td><input class='txtshort' type='text' name='order[receiptplan]["
				+ i
				+ "][payDT]' id='PayDT"
				+ i
				+ "' value='"
				+ thisObj[j].payDT
				+ "'/></td>"
				+ "<td><select class='txtshort' name='order[receiptplan]["
				+ i
				+ "][pType]' id='pType"
				+ i
				+ "'>"
				+

				"<option value='���'>���</option>"
				+ "<option value='�ֽ�'>�ֽ�</option>"
				+ "<option value='���л�Ʊ'>���л�Ʊ</option>"
				+ "<option value='��ҵ��Ʊ'>��ҵ��Ʊ</option></select></td>"
				+ "<td><input class='txtlong' type='text' name='order[receiptplan]["
				+ i + "][collectionTerms]' id='collectionTerms" + i
				+ "' value='" + thisObj[j].collectionTerms + "'/></td>"
				+ "<td><img src='images/closeDiv.gif' onclick='mydel(this,\""
				+ tableId + "\")' title='ɾ����'></td>"
		"</tr>";
		// ����Ԫ��
		tableObj.append(inStr);
		PayNum.val(i);
		$("#pType" + i).val(thisObj[j].pType)
		j = k + 1;
	}
}
// TODO;grid������ѵ�ƻ�
function traininglist(thisObj, tableId, orderId) {
	var tableObj = $('#' + tableId);
	var objLength = thisObj.length;
	var inStr = "";
	var TraNumber = $("#TraNumber");
	// ��ȡ��ǰ����
	var rowNum = $("tr[name^='trainingTab_']").length * 1
			+ $("tr[id^='trainingTab_']").length * 1;
	// ��ǰ����ֵ
	var i = TraNumber.val() * 1;
	var j = 0;
	for (var k = 0; k < objLength; k++) {
		rowNum++;
		i++;
		inStr = "<tr name='trainingTab_"
				+ orderId
				+ "'><td>"
				+ i
				+ "</td>"
				+ "<td><input class='txtshort' type='text' name='order[trainingplan]["
				+ i
				+ "][beginDT]' id='TraDT"
				+ i
				+ "' onfocus='WdatePicker()' value='"
				+ thisObj[j].beginDT
				+ "'></td>"
				+ "<td><input class='txtshort' type='text' name='order[trainingplan]["
				+ i
				+ "][endDT]' id='TraEndDT"
				+ i
				+ "' onfocus='WdatePicker()' value='"
				+ thisObj[j].endDT
				+ "'></td>"
				+ "<td><input class='txtshort' type='text' name='order[trainingplan]["
				+ i + "][traNum]' value='" + thisObj[j].traNum + "'/></td>"
				+ "<td><textarea name='order[trainingplan][" + i
				+ "][adress]' rows='3' cols='15' style='width: 100%'>"
				+ thisObj[j].adress + "</textarea></td>"
				+ "<td><textarea name='order[trainingplan][" + i
				+ "][content]' rows='3' cols='15' style='width: 100%'>"
				+ thisObj[j].content + "</textarea></td>"
				+ "<td><textarea name='order[trainingplan][" + i
				+ "][trainer]' rows='3' cols='15' style='width: 100%'>"
				+ thisObj[j].trainer + "</textarea></td>"
				+ "<td><img src='images/closeDiv.gif' onclick='mydel(this,\""
				+ tableId + "\")' title='ɾ����'></td>"
		"</tr>";
		// ����Ԫ��
		tableObj.append(inStr);
		TraNumber.val(i);
		j = k + 1;
	}
	// $('#TraNumber').val(j);

}

// ������
function countAll() {
	var invnumber = $('#productNumber').val();
	var incomeMoney = $('#money').val();
	var thisAmount = 0;
	var allAmount = 0;
	for (var i = 1; i <= invnumber; i++) {
		thisAmount = $('#money' + i).val() * 1;
		if (!isNaN(thisAmount)) {
			allAmount += thisAmount;
		}
	}

	$('#orderMoney').val(allAmount);

}
// //�������Ψһ����֤
// function check_code(code) {
// if (code != '') {
// $.get('index1.php', {
// model : 'projectmanagent_order_order',
// action : 'checkRepeat',
// ajaxOrderCode : code
// }, function(data) {
// if (data != '') {
// $('#_orderCode').html('�Ѵ��ڵı�ţ�');
//
// } else {
// $('#_orderCode').html('��ſ��ã�');
//
// }
// })
// }
// return false;
// }

// ��������
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
		deliveryDate = $('#deliveryDate').val();
		$.each($(':input[id^="projArraDate"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

});

// ѡ��ʡ��
$(function() {

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
// ��������
$(function() {

	$("#district").yxcombogrid_province({
		hiddenId : 'districtId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
// ��֯����ѡ��
$(function() {
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId'
	});
});

$(function() {

	// �ͻ�����
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
	addDataToSelect(customerTypeArr, 'customerListTypeArr1');
	// ��Ʊ����
	invoiceTypeArr = getData('FPLX');
	addDataToSelect(invoiceTypeArr, 'invoiceType');
	addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	// ��ͬ����
	orderNatureCodeArr = getData('XSHTSX');
	addDataToSelect(orderNatureCodeArr, 'orderNature');
	// addDataToSelect(orderNatureCodeArr, 'invoiceListType1');

});

function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#customerLinkman").yxcombogrid('grid').reload;
}
// �ͻ���ϵ��
function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#customerLinkman").yxcombogrid('grid').reload;

}

$(function() {

	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#customerLinkman")
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
					$("#customerProvince").val(data.Prov);
					$("#customerId").val(data.id);
					$("#province").val(data.ProvId);//����ʡ��Id
				    $("#province").trigger("change");
				    $("#provinceName").val(data.Prov);//����ʡ��
					$("#city").val(data.CityId);//����ID
					$("#cityName").val(data.City);//��������
					$("#areaPrincipal").val(data.AreaLeader);// ��������
					$("#areaPrincipalId_v").val(data.AreaLeaderId);// ��������Id
					$("#areaName").val(data.AreaName);// ��ͬ��������
					$("#areaCode").val(data.AreaId);// ��ͬ��������
					$("#address").val(data.Address);// �ͻ���ַ
					// $("#customerLinkman").yxcombogrid('grid').param={}
					// $("#customerLinkman").yxcombogrid('grid').reload;
				}
			}
		}
	});
	// customerId = $("#customerId").val()
	// $("#customerId").val(customerId)
	$("#customerLinkman").yxcombogrid_linkman({
	    isFocusoutCheck : false,
		hiddenId : 'customerLinkmanId',
		isFocusoutCheck : false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					// unset($('#customerId'));
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#customerTel").val(data.mobile);
					$("#customerEmail").val(data.email);
				}
			}
		}
	});
	$("#linkman1").yxcombogrid_linkman({
		isFocusoutCheck : false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					// alert( "linkman" + mycount );
					$("#linkmanId1").val(data.id);
					$("#telephone1").val(data.phone);
					$("#Email1").val(data.email);

				}
			}
		}
	});

});

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

	j = i + 1;
	oTR = mypay.insertRow([i]);
	oTR.id = "linkTab_" + i;
	// oTR.id = "linkDetail" + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
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
		isFocusoutCheck : false,
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
			+ mycount + "][telephone]' id='telephone" + mycount
			+ "' onchange='tel(" + mycount + ")'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='order[linkman]["
			+ mycount + "][Email]' id='Email" + mycount + "' onchange='Email("
			+ mycount + ")'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[linkman]["
			+ mycount + "][remark]' id='Lremark" + mycount + "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}
/** ******************************************************************************************** */
/*
 * ˫���鿴��Ʒ�嵥 ���� ������Ϣ
 */
function conInfo(productId,proRemarkNum) {
	var proId = $("#" + productId).val();
	var proRemarkId = "remark" + proRemarkNum;
	if (proId == '') {
		alert("����ѡ�����ϡ�");
	} else {
		showThickboxWin('?model=projectmanagent_order_order&action=proinfotab&productId='
				+ proId
				+ "&proRemarkId="
				+ proRemarkId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
	}

}

$(function() {
	temp = $('#productNumber').val();
	for (var i = 1; i <= temp; i++) {
		$("#productNo" + i).yxcombogrid_product({
			hiddenId : 'productId' + i,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							myedit(document.getElementById("Del" + i), "myequ");
							$("#productName" + i).val(data.productName);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
							$("#number" + i).val(1);
                           if (encrypt == 'on') {
							var encrypt = data.encrypt;
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i)
								$.get('index1.php', {
									model : 'projectmanagent_order_order',
									action : 'ajaxorder',
									id : data.id,
									trId : "isCon_" + i,
									Num : $("#productNumber").val(),
									dataType : "html"
								}, function(pro) {
									if (pro) {
										$("#equTab_" + i).after(pro);
										var rowNums = $("tr[name^='equTab_']").length
												* 1
												+ $("tr[id^='equTab_']").length
												* 1;
										document.getElementById("productNumber").value = document
												.getElementById("productNumber").value
												* 1 + 1;
										recount("myequ");
									} else {
									}
								})

						}
					}(i)
				}
			}
		});
	}
});

$(function() {
	temp = $('#productNumber').val();
	for (var i = 1; i <= temp; i++) {
		$("#productName" + i).yxcombogrid_productName({
			hiddenId : 'productId' + i,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							myedit(document.getElementById("Del" + i), "myequ");
							$("#productNo" + i).val(data.productCode);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
							$("#number" + i).val(1);

							var encrypt = data.encrypt;
							if (encrypt == 'on') {
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								$.get('index1.php', {
									model : 'projectmanagent_order_order',
									action : 'ajaxorder',
									id : data.id,
									trId : "isCon_" + i,
									Num : $("#productNumber").val(),
									dataType : "html"
								}, function(pro) {
									if (pro) {
										$("#equTab_" + i).after(pro);
										var rowNums = $("tr[name^='equTab_']").length
												* 1
												+ $("tr[id^='equTab_']").length
												* 1;
										document
												.getElementById("productNumber").value = document
												.getElementById("productNumber").value
												* 1 + 1;
										recount("myequ");
									} else {
									}
								})
						}
					}(i)
				}
			}
		});
	}
});
// TODO:��̬��Ʒ��Ϣ
/** ********************��Ʒ��Ϣ************************ */
function dynamic_add(packinglist, countNumP) {

	deliveryDate = $('#deliveryDate').val();

	// ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='equTab_']").length * 1
			+ $("tr[id^='equTab_']").length * 1;
	// ��ȡ��ǰ����ֵ,������id����
	mycount = $('#' + countNumP).val() * 50 + 1;
	// ���������
	var packinglist = document.getElementById(packinglist);
	// ������
	oTR = packinglist.insertRow([rowNums]);
	oTR.id = "equTab_" + mycount;
	// ��ǰ�к�
	j = rowNums + 1;
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
			+ "' class='txtshort' name='order[orderequ][" + mycount
			+ "][productNo]' >";
	// ��ѡ��Ʒ
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						myedit(document.getElementById("Del" + mycount),
								"myequ");
						$("#productName" + mycount).val(data.productName);
						$("#productModel" + mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
						$("#number" + mycount).val(1);
						var encrypt = data.encrypt;
						if (encrypt == 'on') {
							alert("�����������ü��ܱ������ԣ�����д��������");
							License('licenseId' + mycount);
						}
						var allocation = data.allocation;
							$("#isCon" + mycount).val("isCon_" + mycount);
							$.get('index1.php', {
								model : 'projectmanagent_order_order',
								action : 'ajaxorder',
								id : data.id,
								trId : "isCon_" + mycount,
								Num : mycount,
								dataType : "html"
							}, function(pro) {
								if (pro) {
									$("#equTab_" + mycount).after(pro);
									var rowNums = $("tr[name^='equTab_']").length
											* 1
											+ $("tr[id^='equTab_']").length
											* 1;
									document.getElementById(countNumP).value = document
											.getElementById(countNumP).value
											* 1 + 1;
									recount("myequ");
								} else {
								}
							})
					};
				}(mycount)
			}
		}
	});

	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId"
			+ mycount
			+ "'  name='order[orderequ]["
			+ mycount
			+ "][productId]'/>"
			+ "<input id='productName"
			+ mycount
			+ "' type='text' class='txtmiddle' name='order[orderequ]["
			+ mycount
			+ "][productName]' />"
			+ "&nbsp<img src='images/icon/icon105.gif' onclick='conInfo(\"productId" + mycount + "\",\""+mycount+" \");' title='�鿴������Ϣ'/>";
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						myedit(document.getElementById("Del" + mycount),
								"myequ");
						$("#productNo" + mycount).val(data.productCode);
						$("#productModel" + mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
						$("#number" + mycount).val(1);
						var encrypt = data.encrypt;
						if (encrypt == 'on') {
							alert("�����������ü��ܱ������ԣ�����д��������");
							License('licenseId' + mycount);
						}
						var allocation = data.allocation;
							$("#isCon" + mycount).val("isCon_" + mycount);
							$.get('index1.php', {
								model : 'projectmanagent_order_order',
								action : 'ajaxorder',
								id : data.id,
								trId : "isCon_" + mycount,
								Num : mycount,
								dataType : "html"
							}, function(pro) {
								if (pro) {
									$("#equTab_" + mycount).after(pro);
									var rowNums = $("tr[name^='equTab_']").length
											* 1
											+ $("tr[id^='equTab_']").length
											* 1;
									document.getElementById(countNumP).value = document
											.getElementById(countNumP).value
											* 1 + 1;
									recount("myequ");
								} else {
								}
							})
					};
				}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='txtshort' name='order[orderequ][" + mycount
			+ "][productModel]' readonly>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[orderequ]["
			+ mycount + "][number]' id='number" + mycount + "'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort formatMoney' type='text' name='order[orderequ]["
			+ mycount + "][price]' id='price" + mycount + "'/>";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort  formatMoney' type='text' name='order[orderequ]["
			+ mycount + "][money]' id='money" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input  id='projArraDate" + mycount
			+ "'type='text'  class='txtshort' name='order[orderequ][" + mycount
			+ "][projArraDate]' value='" + deliveryDate
			+ "' onfocus='WdatePicker()'>"
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input  type='text' class='txtshort' name='order[orderequ]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "' >";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='licenseId"
			+ mycount
			+ "' name='order[orderequ]["
			+ mycount
			+ "][license]'/>"
			+ "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"licenseId"
			+ mycount + "\");'>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='order[orderequ][" + mycount
			+ "][isSell]' checked='checked'>"
			+ "<input type='hidden' name='order[orderequ][" + mycount + "][isCon]'id='isCon" + mycount + "'>"
			+ "<input type='hidden' name='order[orderequ][" + mycount + "][isConfig]' id='isConfig" + mycount + "'>"
			+ "<input type='hidden' name='order[orderequ][" + mycount + "][remark]' id='remark"+mycount+"' >";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='ɾ����' id='Del" + mycount + "'	>";
	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
	// ǧ��λ������
	createFormatOnClick('number' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);
	createFormatOnClick('price' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);
	createFormatOnClick('money' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);
}
/** **********************�Զ����嵥********************************** */

function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	j = i + 1;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][productCode]' id='PequID"
			+ mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][productName]' id='PequName"
			+ mycount
			+ "' value='' size='15' maxlength='20'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][productModel]' id='PreModel"
			+ mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][number]' id='PreAmount" + mycount + "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][price]' id='PrePrice" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount + "][money]' id='CountMoney" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='order[customizelist]["
			+ mycount
			+ "][projArraDT]' id='PreDeliveryDT"
			+ mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='order[customizelist]["
			+ mycount
			+ "][remark]' id='PRemark"
			+ mycount
			+ "' value='' size='18' maxlength='100'/>";

	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='cuslicenseId"
			+ mycount
			+ "' name='order[customizelist]["
			+ mycount
			+ "][license]'/>"
			+ "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"cuslicenseId"
			+ mycount + "\");'>";

	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='order[customizelist]["
			+ mycount + "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	// ǧ��λ������
	createFormatOnClick('PreAmount' + mycount, 'PreAmount' + mycount,
			'PrePrice' + mycount, 'CountMoney' + mycount);
	createFormatOnClick('PrePrice' + mycount, 'PreAmount' + mycount, 'PrePrice'
			+ mycount, 'CountMoney' + mycount);
	createFormatOnClick('CountMoney' + mycount, 'PreAmount' + mycount,
			'PrePrice' + mycount, 'CountMoney' + mycount);
}

// TODO:��̬��Ʊ�ƻ�
/** ********************��Ʊ�ƻ�******************************* */

function inv_add(myinv, countNum) {

	// ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='invTab_']").length * 1
			+ $("tr[id^='invTab_']").length * 1;

	mycount = $('#' + countNum).val() * 1 + 1;

	var myinv = document.getElementById(myinv);

	oTR = myinv.insertRow([rowNums]);
	oTR.id = 'invTab_' + mycount;
	j = rowNums + 1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ mycount
			+ "][money]' id='InvMoney"
			+ mycount
			+ "' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ mycount
			+ "][softM]' id='InvSoftM"
			+ mycount
			+ "' size='10' maxlength='40'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtmiddle' name='order[invoice]["
			+ mycount + "][iType]' id='invoiceListType" + mycount
			+ "'></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[invoice]["
			+ mycount
			+ "][invDT]' id='InvDT"
			+ mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='order[invoice]["
			+ mycount + "][remark]' id='InvRemark" + mycount
			+ "' size='60' maxlength='100'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myinv.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(invoiceTypeArr, 'invoiceListType' + mycount);

	createFormatOnClick('InvMoney' + mycount);
	createFormatOnClick('InvSoftM' + mycount);
}

// TODO:��̬�տ�ƻ�
/** **********************�տ�ƻ�************************************* */

function pay_add(mypay, countNum) {

	// ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='payTab_']").length * 1
			+ $("tr[id^='payTab_']").length * 1;

	mycount = $('#' + countNum).val() * 1 + 1;

	j = rowNums + 1;

	var mypay = document.getElementById(mypay);
	oTR = mypay.insertRow([rowNums]);
	oTR.id = 'payTab_' + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan]["
			+ mycount
			+ "][money]' id='PayMoney"
			+ mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan]["
			+ mycount
			+ "][payDT]' id='PayDT"
			+ mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='order[receiptplan]["
			+ mycount
			+ "][pType]'><option value='���'>���</option><option value='�ֽ�'>�ֽ�</option><option value='���л�Ʊ'>���л�Ʊ</option><option value='��ҵ��Ʊ'>��ҵ��Ʊ</option></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[receiptplan]["
			+ mycount
			+ "][collectionTerms]' id='collectionTerms"
			+ mycount
			+ "' size='70' />";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney' + mycount);
}

// TODO:��̬��ѵ�ƻ�
/** ***********************��ѵ�ƻ�********************************** */

function train_add(mytra, countNum) {
	// ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='trainingTab_']").length * 1
			+ $("tr[id^='trainingTab_']").length * 1;

	mycount = $('#' + countNum).val() * 1 + 1;

	j = rowNums + 1;

	var mytra = document.getElementById(mytra);
	oTR = mytra.insertRow([rowNums]);
	oTR.id = 'trainingTab_' + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount
			+ "][beginDT]' id='TraDT"
			+ mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount
			+ "][endDT]' id='TraEndDT"
			+ mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan]["
			+ mycount + "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][trainer]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// TODO:��̬ɾ����
/** ********************ɾ����̬��************************ */
function myedit(obj, mytable) {
	var rowSize = $("#" + mytable).children().length;
	var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
	var mytable = document.getElementById(mytable);
	var myrows = rowSize - 1;
	for (i = 0; i < myrows; i++) {
		mytable.rows[i].childNodes[0].innerHTML = i + 1;
	}
	var objA = obj.parentNode.parentNode;
	if ($(objA).find("input[id^='isConfig']").val() == '') {
		$("tr[parentRowId=" + $(objA).find("input[id^='isCon']").val() + "]")
				.remove();
	}
}
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowSize = $("#" + mytable).children().length;
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
		var mytable = document.getElementById(mytable);

		var objA = obj.parentNode.parentNode;
		if ($(objA).find("input[id^='isConfig']").val() == '') {
			var cong = $("tr[parentRowId=" + $(objA).find("input[id^='isCon']").val()+ "]").size();
			$("tr[parentRowId=" + $(objA).find("input[id^='isCon']").val()+ "]").remove();
		}
		mytable.deleteRow(rowNo);

		var i = 1;
	   $(mytable).children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(0).text(i);
					i++;

				}
			})


	}
}

/**
 * ���¼��������
 *
 * @param {}
 *            name
 */
function recount(mytable) {
	var mytable = document.getElementById(mytable);

	var myrows = mytable.rows.length;
	for (i = 0; i < myrows; i++) {
		mytable.rows[i].childNodes[0].innerHTML = i + 1;
	}
}

/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/** ****************��ͬ �������� --- ��ͬ��������************************************** */
$(function() {
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#areaPrincipal").val(data.areaPrincipal);
					$("#areaCode").val(data.id);
					$("#areaPrincipalId_v").val(data.areaPrincipalId);
				}
			}
		}
	});

});

/****************************************************************************************/
//ѡ�����к�
function serialNum(borrowId,productId,num){
	 var amount = $("#bornumber"+num).val();
	 showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNum&borrowId=' + borrowId + '&productId=' + productId + '&num=' + num + '&amount=' + amount
				     + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}





