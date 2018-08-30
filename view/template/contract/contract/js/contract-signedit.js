$(function() {
	// ��������
	fileTypeArr = getData('FJLX');
	addDataToSelect(fileTypeArr, 'fileType');
	// ��Ʊ����
	var added = $("#addedV").val();
	if (added == "on") {
		$("#addedC").attr("checked", true);
		$("#addedHide").show();
	} else {
		$("#addedC").attr("checked", false);
	}

	var exportInv = $("#exportInvV").val();
	if (exportInv == "on") {
		$("#exportInvC").attr("checked", true);
		$("#exportInvHide").show();
	} else {
		$("#exportInvC").attr("checked", false);
	}

	var serviceInv = $("#serviceInvV").val();
	if (serviceInv == "on") {
		$("#serviceInvC").attr("checked", true);
		$("#serviceInvHide").show();
	} else {
		$("#serviceInvC").attr("checked", false);
	}
    $("#province").change(function () {
        setProExeDept();
    });
    $("#module").change(function () {
        setProExeDept();
    });
})
/**
 * ��Ʊ���Ϳ���
 *
 * @return {Boolean}
 */
function Kcontrol(obj) {
	var KPLX = document.getElementById(obj + "C").checked
	var objHide = obj + "Hide";
	var objMoney = obj + "Money";
	if (KPLX == true) {
		document.getElementById(objHide).style.display = "";
		$("#" + obj + "").val("on");
	} else {
		document.getElementById(objHide).style.display = "none";
		$("#" + obj + "").val("");
		$("#" + objMoney + "").val("0.00");
		$("#" + objMoney + "_v").val("0.00");
	}
}

/**
 * �Ƿ���±����ж�
 */
function changeRadio() {
	// ����������֤
	if ($("#uploadfileList2").html() == ""
		|| $("#uploadfileList2").html() == "�����κθ���") {
		alert('�������ǰ��Ҫ�ϴ���ͬ�ı�!');
		$("#isNeedStampNo").attr("checked", true);
		//����������Ⱦ
		$("#radioSpan").attr('style', "color:black");
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
		return false;
	}
	// ��ʾ������
	if ($("#isNeedStampYes").attr("checked")) {
		$("#radioSpan").attr('style', "color:blue");
		// ��ֹ�ظ������������
		if ($("#stampType").yxcombogrid_stampconfig('getIsRender') == true)
			return false;

		// ����������Ⱦ
		$("#stampType").yxcombogrid_stampconfig({
			hiddenId: 'stampType',
			height: 250,
			gridOptions: {
				isTitle: true,
				showcheckbox: true
			}
		});
	} else {
		$("#radioSpan").attr('style', "color:black");

		// ����������Ⱦ
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
	}
}

/**
 * ���¸���ѡ��
 */
function restampRadio(thisVal) {
	if (thisVal == 1) {
		$(".restamp").show();
		$(".restampIn").attr('disabled', false);
	} else {
		$(".restamp").hide();
		$(".restampIn").attr('disabled', true);
	}
}
// ������ת��������
$(function() {
	var ids = $("#ids").val();
	if (ids != '') {
		var returnValue = $.ajax({
			type: 'POST',
			url: "?model=projectmanagent_borrow_borrow&action=getBorrowequInfo",
			data: {
				ids: ids
			},
			async: false,
			success: function(data) {
			}
		}).responseText;
		returnValue = eval("(" + returnValue + ")");
		if (returnValue) {
			var g = $("#borrowConEquInfo").data("yxeditgrid");
			// ѭ���������
			for (var i = 0; i < returnValue.length; i++) {
				outJson = {
					"productId": returnValue[i].productId,
					"productCode": returnValue[i].productNo,
					"productName": returnValue[i].productName,
					"productModel": returnValue[i].productModel,
					"number": returnValue[i].number - returnValue[i].backNum,
					"price": returnValue[i].price,
					"money": returnValue[i].money,
					"warrantyPeriod": returnValue[i].warrantyPeriod,
					"isBorrowToorder": 1,
					"toBorrowId": returnValue[i].borrowId,
					"toBorrowequId": returnValue[i].id
				};
				// ��������
				$("#customerIdtest").val(returnValue[i].customerId);
				g.addRow(i, outJson);
			}
		}
	}
});
// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		if (tempH != null) {
			tempH.style.display = "";
		}
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		if (tempH != null) {
			tempH.style.display = 'none';
		}
	}
}
$(function() {
	var winRate = $("#winRate").val();
	if (winRate == '100%') {
		$("#moneyName").html("ǩԼ���(��)��");
		document.getElementById("signDateNone").style.display = "";
	} else {
		$("#moneyName").html("Ԥ�ƽ��(��)��");
		document.getElementById("signDateNone").style.display = "none";
	}
})
// �ж��Ƿ�ǩԼ
function isSign(obj) {
	if (obj.value == '100%') {
		document.getElementById("signDateNone").style.display = "";
		$("#moneyName").html("ǩԼ���(��)��");
		$("#signDate").val("");
	} else {
		$("#signDate").val("");
		$("#moneyName").html("Ԥ�ƽ��(��)��");
		document.getElementById("signDateNone").style.display = "none";
	}
}

// //���������ֵ���
// $(function() {
// // ��ͬ����
// contractTypeArr = getData('HTLX');
// addDataToSelect(contractTypeArr, 'contractType');
// // ��ͬ����
// contractNatureArr = getData('XSHTSX');
// addDataToSelect(contractNatureArr, 'contractNature');
// // ��Ʊ����
// invoiceTypeArr = getData('FPLX');
// addDataToSelect(invoiceTypeArr, 'invoiceType');
// //�ͻ�����
// customerTypeArr = getData('KHLX');
// addDataToSelect(customerTypeArr, 'customerType');
//
// });
// ���ݺ�ͬ���͸ı��ͬ���Եļ�����
function changeNature(obj) {
	$('#contractNature').empty();

	contractNatureCodeArr = getData(obj.value);
	addDataToSelect(contractNatureCodeArr, 'contractNature');

	// if (obj.value == 'HTLX-XSHT') {
	// contractNatureCodeArrXS = getData('XSHTSX');
	// addDataToSelect(contractNatureCodeArrXS, 'contractNature');
	// } else if (obj.value == 'HTLX-FWHT') {
	// contractNatureCodeArrFW = getData('FWHTSX');
	// addDataToSelect(contractNatureCodeArrFW, 'contractNature');
	// } else if (obj.value == 'HTLX-ZLHT') {
	// contractNatureCodeArrZL = getData('ZLHTSX');
	// addDataToSelect(contractNatureCodeArrZL, 'contractNature');
	// } else if (obj.value == 'HTLX-YFHT') {
	// contractNatureCodeArrYF = getData('YFHTSX');
	// addDataToSelect(contractNatureCodeArrYF, 'contractNature');
	// } else {
	// var obj=document.getElementById('contractNature');
	// obj.options.add(new Option("��","��")); //�������IE��firefox
	// }
	//���ۺ�ͬ ȥ�� ��ͬ��ʼ��ֹ����
	if (obj.value == 'HTLX-XSHT') {
//       $('#beginDate').removeClass("validate[required]");
//       $('#endDate').removeClass("validate[required]");
		$("#beginSpan").attr('style', "color:black");
		$("#endSpan").attr('style', "color:black");
	} else {
//       $('#beginDate').addClass("validate[required]");
//       $('#endDate').addClass("validate[required]");
		$("#beginSpan").attr('style', "color:blue");
		$("#endSpan").attr('style', "color:blue");
	}
	// �ı�ϣ���������� ������֤
	if (obj.value == 'HTLX-FWHT' || obj.value == 'HTLX-YFHT') {
		$("#shipConditionSpan").attr('style', "color:black");
		$("#deliveryDateSpan").attr('style', "color:black");
	} else {
		$("#shipConditionSpan").attr('style', "color:blue");
		$("#deliveryDateSpan").attr('style', "color:blue");
	}
}
$(function() {
	var contractType = $("#contractType").val();
	if (contractType == 'HTLX-XSHT') {
//       $('#beginDate').removeClass("validate[required]");
//       $('#endDate').removeClass("validate[required]");
		$("#beginSpan").attr('style', "color:black");
		$("#endSpan").attr('style', "color:black");
	} else {
//       $('#beginDate').addClass("validate[required]");
//       $('#endDate').addClass("validate[required]");
		$("#beginSpan").attr('style', "color:blue");
		$("#endSpan").attr('style', "color:blue");
	}
});
// ��֯����ѡ��
$(function() {
	$("#prinvipalName").yxselect_user({
		hiddenId: 'prinvipalId',
		isGetDept: [true, "depId", "depName"]
	});
	$("#contractSigner").yxselect_user({
		hiddenId: 'contractSignerId'
	});
	$("#areaPrincipal").yxselect_user({
		hiddenId: 'areaPrincipalId'
	});
});
//��������
function regionList() {
	$("#areaName").yxcombogrid_area({
		hiddenId: 'areaCode',
		gridOptions: {
			showcheckbox: false,
//			param : { 'businessBelong' : $("#businessBelong").val()},
			event: {
				'row_dblclick': function(e, row, data) {
					$("#areaPrincipal").val(data.areaPrincipal);
					$("#areaCode").val(data.id);
					$("#areaPrincipalId").val(data.areaPrincipalId);
				}
			}
		}
	});
}
// ���������б�
$(function() {
	// �ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId: 'customerId',
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					$("#customerType").val(data.TypeOne);
					if ($("#countryName").val() == "�й�") {
						$("#province").val(data.ProvId);// ����ʡ��Id
						$("#province").trigger("change");
						$("#provinceName").val(data.Prov);// ����ʡ��
						$("#city").val(data.CityId);// ����ID
						$("#cityName").val(data.City);// ��������
					}
					$("#customerId").val(data.id);
//					$("#areaPrincipal").val(data.AreaLeader);// ��������
//					$("#areaPrincipalId_v").val(data.AreaLeaderId);// ��������Id
//					$("#areaName").val(data.AreaName);// ��ͬ��������
//					$("#areaCode").val(data.AreaId);// ��ͬ��������
					$("#address").val(data.Address);// �ͻ���ַ
					var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
						"getCmpByCol", "linkmanName");
					linkmanCmp.yxcombogrid_linkman("remove");
					$("#linkmanListInfo").yxeditgrid('remove');
					linkmanList(data.id, 1);
				}
			}
		}
	});
	//��˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		height: 250,
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					$("#areaName").val("");
					$("#areaCode").val("");
					$("#areaPrincipal").val("");
					$("#areaPrincipalId").val("");

					$("#areaName").yxcombogrid_area("remove");
					regionList();
				}
			}
		}
	});
	regionList();

});

// ���ʼ���
function conversion() {
	var currency = $("#currency").val();

	if (currency != '�����') {
		document.getElementById("currencyRate").style.display = "";
		$("#cur").html("(" + currency + ")");
		$("#cur1").html("(" + currency + ")");
		$("#contractTempMoney_v").attr('class', "readOnlyTxtNormal");
		$("#contractMoney_v").attr('class', "readOnlyTxtNormal");

		var tempMoney = $("#contractTempMoneyCur").val();
		var Money = $("#contractMoneyCur").val();
		var rate = $("#rate").val();
		$("#contractTempMoney_v").val(moneyFormat2(tempMoney * rate));
		$("#contractTempMoney").val(tempMoney * rate);
		$("#contractMoney_v").val(moneyFormat2(Money * rate));
		$("#contractMoney").val(Money * rate);
	} else if (currency == '�����') {
		$("#contractMoney_v").attr('class', "txt");
		$("#contractMoney_v").attr('readOnly', false);
		$("#contractTempMoney_v").attr('class', "txt");
		$("#contractTempMoney_v").attr('readOnly', false);
		$("#currencyRate").hide();
	}
}

// �༭ҳ ��������
$(function() {
	var shipCondition = $("#shipConditionV").val();
	if (shipCondition == '') {
		document.getElementById("shipCondition").options.add(new Option("��ѡ��",
			""));
		document.getElementById("shipCondition").options.add(new Option("��������",
			"0"));
		document.getElementById("shipCondition").options.add(new Option("֪ͨ����",
			"1"));
	} else if (shipCondition == '0') {
		document.getElementById("shipCondition").options.add(new Option("��������",
			"0"));
		document.getElementById("shipCondition").options.add(new Option("֪ͨ����",
			"1"));
	} else if (shipCondition == '1') {
		document.getElementById("shipCondition").options.add(new Option("֪ͨ����",
			"1"));
		document.getElementById("shipCondition").options.add(new Option("��������",
			"0"));
	}
    // �Ƿ��ܺ�ͬ
    var isFrame = $("#isFrameV").val();
    if (typeof(isFrame) != 'undefined') {
        if (isFrame == '0') {
            document.getElementById("isFrame").options.add(new Option("��",
                "0"));
            document.getElementById("isFrame").options.add(new Option("��",
                "1"));
        } else if (isFrame == '1') {
            document.getElementById("isFrame").options.add(new Option("��",
                "1"));
            document.getElementById("isFrame").options.add(new Option("��",
                "0"));
        }
    }
});
// ���ʼ���
function conversion() {
	var currency = $("#currency").val();

	if (currency != '�����') {
		document.getElementById("currencyRate").style.display = "";
		$("#cur").html("(" + currency + ")");
		$("#cur1").html("(" + currency + ")");
		$("#contractTempMoney_v").attr('class', "readOnlyTxtNormal");
		$("#contractMoney_v").attr('class', "readOnlyTxtNormal");

		var tempMoney = $("#contractTempMoneyCur").val();
		var Money = $("#contractMoneyCur").val();
		var rate = $("#rate").val();
		$("#contractTempMoney_v").val(moneyFormat2(tempMoney * rate));
		$("#contractTempMoney").val(tempMoney * rate);
		$("#contractMoney_v").val(moneyFormat2(Money * rate));
		$("#contractMoney").val(Money * rate);
	} else if (currency == '�����') {
		$("#contractMoney_v").attr('class', "txt");
		$("#contractMoney_v").attr('readOnly', false);
		$("#contractTempMoney_v").attr('class', "txt");
		$("#contractTempMoney_v").attr('readOnly', false);
		$("#currencyRate").hide();
	}
};
// ���ؽ��
$(function() {
	conversion();
	$("#currency").yxcombogrid_currency({
		hiddenId: 'id',
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					$("#rate").val(data.rate);
					conversion();
					checkConMoney();
				}
			}
		}
	});
	createFormatOnClick("orderTempMoney_c");
	createFormatOnClick("orderMoney_c");
});

// ʡ��
$(function() {
	var countryId = $("#contractContryId").val();
	var proId = $("#contractProvinceId").val();
	var cityId = $("#contractCityId").val();
	$("#country").val(countryId);// ��������Id
	$("#country").trigger("change");
	$("#province").val(proId);// ����ʡ��Id
	$("#province").trigger("change");
	$("#city").val(cityId);// ����ID
	$("#city").trigger("change");

});

$(function() {

	// �ύ��֤
	$("#form1").validationEngine({
		inlineValidation: false,
		success: function() {
			sub();
			$("#form1").submit();// ������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug
		},
		failure: false
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"winRate": {
			required: true
		},
		"currency": {
			required: true
		},
		"contractType": {
			required: true
		},
		"customerName": {
			required: true
		},
		"shipCondition": {
			required: true
		},
		"contractTempMoney_v": {
			required: true
		},
		"contractMoney_v": {
			required: true
		},
		"areaCode": {
			required: true
		},
		"areaName": {
			required: true
		},
		"areaPrincipal": {
			required: true
		}
	});
});


function paymentControl(obj) {
	var flag = $("#" + obj).attr('checked');
	if (obj == 'otherpaymentterm') {
		otherpaymenttermList(flag);
	} else if (obj == 'progresspayment') {
		paymentList(flag);
	} else {
		var objHide = obj + "Hide";
		var objA = obj + "A";
		if (flag == true) {
			$("#" + objHide).show();
		} else {
			$("#" + objHide).hide();
			if (objHide == 'otherpaymenttermHide') {
				$("#otherpaymenttermA").val("");
				$("#otherpaymentA").val("");
			} else {
				$("#" + objA + "").val("");
			}
		}
	}
}
//�����ȸ����б�
function paymentList(flag) {
	var listObj = $("#progresspaymentList");
	var str = '<tr id="paymentTR1">' +
		'<td ><input class="rimless_text_normal" id="protem1"  name="contract[progresspaymentterm][1]"/></td>' +
		'<td><input class="rimless_text_short realNum" id="progresspaymentPro1" name="contract[progresspayment][1]" />%</td>' +
		'<td><img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_paymentlist();"/></td>' +
		'</tr>';
	if (flag == true) {
		listObj.html(str);
	} else {
		listObj.html("");
	}

}
function add_paymentlist() {
	var listObj = $("#progresspaymentList");
	var listLength = $("#progresspaymentList tr").length;
	var i = listLength + 1;
	var str = '<tr id="paymentTR' + i + '">' +
		'<td><input class="rimless_text_normal" id="protem' + i + '"  name="contract[progresspaymentterm][' + i + ']"/></td>' +
		'<td><input class="rimless_text_short realNum" id="progresspaymentPro' + i + '" name="contract[progresspayment][' + i + ']"/>%</td>' +
		'<td><img style="cursor:pointer;" src="images/removeline.png" title="ɾ����" onclick="delete_paymentlist(this);"/></td>' +
		'</tr>';
	listObj.append(str);

}
//������������
function otherpaymenttermList(flag) {
	var listObj = $("#otherpaymenttermList");
	var str = '<tr id="otherTR1">' +
		'<td><input class="rimless_text_normal" id="ohterprotem1"  name="contract[otherpaymentterm][1]"/></td>' +
		'<td><input class="rimless_text_short realNum" id="otherpaymentPro1" name="contract[otherpayment][1]"/>%</td>' +
		'<td><img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_otherpaymenttermList();"/></td>' +
		'</tr>';
	if (flag == true) {
		listObj.html(str);
	} else {
		listObj.html("");
	}
}
function add_otherpaymenttermList() {
	var listObj = $("#otherpaymenttermList");
	var listLength = $("#otherpaymenttermList tr").length;
	var i = listLength + 1;
	var str = '<tr id="otherTR' + i + '">' +
		'<td><input class="rimless_text_normal" id="ohterprotem' + i + '"  name="contract[otherpaymentterm][' + i + ']"/></td>' +
		'<td><input class="rimless_text_short realNum" id="otherpaymentPro' + i + '" name="contract[otherpayment][' + i + ']"/>%</td>' +
		'<td><img style="cursor:pointer;" src="images/removeline.png" title="ɾ����" onclick="delete_paymentlist(this);"/></td>' +
		'</tr>';
	listObj.append(str);

}
//ɾ����
function delete_paymentlist(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		$(obj).parent().parent().remove();
	}
}
function sub() {
	$("form").bind("submit", function() {
		// ��֤�˻����������Ƿ�Ϊ100%
		var advance = $("#advanceA").val();
		var delivery = $("#deliveryA").val();
		var initialpayment = $("#initialpaymentA").val();
		var finalpayment = $("#finalpaymentA").val();

		var addArr = [advance, delivery, initialpayment, finalpayment]
		//xxxxxxxxxxxxx
		var progressNum = $("input[id^='progresspaymentPro']").length;
		var otherNum = $("input[id^='otherpaymentPro']").length;
		if (progressNum > '0') {
			for (i = 0; i <= progressNum; i++) {
				addArr.push($('#progresspaymentPro' + i).val());
			}
		}
		if (otherNum > '0') {
			for (i = 0; i <= otherNum; i++) {
				addArr.push($('#otherpaymentPro' + i).val());
			}
		}
		//xxxxxx end xxxxxxx
		var dataCode = $("#dataCode").val();
		var itemArr = dataCode.split(',');
		var itemLength = itemArr.length;
		var contractMoney = $("#contractMoney").val();
		var contractMoneyCur = $("#contractMoneyCur").val();
		var currency = $("#currency").val();
		var allCost = 0;
		var j = 0;
		for (var i = 0; i < itemLength; i++) {
			if ($("#" + itemArr[i]).is(":checked")) {
				if ($("#" + itemArr[i] + "Money_v").val() == "") {
					$("#" + itemArr[i] + "Money_v").addClass("validate[required]");
				} else {
					allCost = accAdd(allCost, $("#" + itemArr[i] + "Money_v").val(), 2);
					$("#" + itemArr[i] + "Money_v").removeClass("validate[required]");
				}
			} else {
				j++;
			}
		}
		var productInfoObj = $("#productInfo");
		var rowNum = productInfoObj.productInfoGrid('getCurShowRowNum');
		if (rowNum == '0') {
			alert("��Ʒ�嵥����Ϊ��");
			return false;
		}else {
        	// ��Ʒ�ߴ���
        	var newProLineArr = [];
            var proLineAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function(){
                if ($(this).val() == "") {
                    alert("��ѡ���Ʒ�Ĳ�Ʒ�ߣ�");
                    proLineAllSelected = false;
                    return false;
                } else {
                    var rowNum = $(this).data('rowNum');
                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'newProLineName').
                        val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), newProLineArr) == -1) {
                    	newProLineArr.push($(this).val());
                    }
                }
            });
            if (proLineAllSelected == false) {
                return false;
            }
            // ִ�в��Ŵ���
            var exeDeptArr = [];
            var exeDeptAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId").each(function(){
                if ($(this).val() == "") {
                    alert("��ѡ���Ʒ��ִ������");
                    exeDeptAllSelected = false;
                    return false;
                } else {
                    var rowNum = $(this).data('rowNum');
                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').
                        val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), exeDeptArr) == -1) {
                        exeDeptArr.push($(this).val());
                    }
                }
            });
            if (exeDeptAllSelected == false) {
                return false;
            }
        }
		return true;
	});

	// �������ж�
	if ($("#isChange").val() == 1) {
		isFormChange();
	}
}

/**
 *�¿�Ʊ���Ϳ���
 * @param {} obj
 */
function isCheckType(obj) {
	var isChecked = $("#" + obj).is(':checked');
	if (isChecked) {
		$("#" + obj + "Hide").show();
	} else {
		$("#" + obj + "Hide").hide();
		$("#" + obj + "Money").val("");
		$("#" + obj + "Money_v").val("");
	}
}

/**
 * �ж��¿�Ʊ����ѡ��������
 * @param {} obj
 */
function checkConMoney() {
	var dataCode = $("#dataCode").val();
	var itemArr = dataCode.split(',');
	var itemLength = itemArr.length;
	var rate = $("#rate").val();
	var currency = $("#currency").val();
	var allCost = 0;
	var contractMoney = $("#contractMoney_v").val();

	for (i = 0; i < itemLength; i++) {
		if ($("#" + itemArr[i]).is(":checked")) {
			if ($("#" + itemArr[i] + "Money_v").val() == "") {
				$("#" + itemArr[i] + "Money_v").addClass("validate[required]");
			} else {
				allCost = accAdd(allCost, $("#" + itemArr[i] + "Money_v").val(), 2);
				$("#" + itemArr[i] + "Money_v").removeClass("validate[required]");
			}
		}
	}
	if (currency == '�����') {
		setMoney("contractMoney", allCost);
	} else {
		setMoney("contractMoneyCur", allCost);
	}
	conversion();
}

//��ȡ��Ʒִ������
function getProExeDept(){
    var province = $("#province").val();
    var module = $("#module").val();
    if (province != '' && module != '') {
        var returnValue = $.ajax({
            type: 'POST',
            url: "?model=engineering_officeinfo_officeinfo&action=ajaxConProExeDept",
            data: {
                province: province,
                module: module
            },
            async: false
        }).responseText;
        returnValue = eval("(" + returnValue + ")");
        if (returnValue) {
        	return returnValue;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// �������в�Ʒִ������
function setProExeDept() {
	var returnValue = getProExeDept();
    if (returnValue) {
    	var productInfoObj = $("#productInfo");
    	var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
    	if(exeDeptObj.length > 0){
        	var productLine = returnValue[0].productLine;
        	var productLineName = returnValue[0].productLineName;
    		exeDeptObj.each(function(){
    			$(this).find("option:[text='"+ productLineName + "']").attr("selected",true);
                var rowNum = $(this).data('rowNum');
                productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(productLineName);
            });
    	}
    } else {
        return false;
    }
}

//��������ĳ����Ʒִ������
function setProExeDeptByRow(i) {
	var returnValue = getProExeDept();
    if (returnValue) {
    	var productLine = returnValue[0].productLine;
    	var productLineName = returnValue[0].productLineName;
    	var productInfoObj = $("#productInfo");
    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptId')
    		.find("option:[text='"+ productLineName + "']").attr("selected",true);
    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(productLineName);
    } else {
        return false;
    }
}