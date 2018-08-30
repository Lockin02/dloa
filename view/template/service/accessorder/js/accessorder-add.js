$(document).ready(function() {
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserCode',
		formCode : 'accessorder'
	});	
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"chargeUserName" : {
			required : true

		},
		"docDate" : {
			custom : ['date']
		},
		"customerName" : {
			required : true
		},
		"prov" : {
			required : true
		}
	});
	//��ȡʡ����Ϣ
    var responseText = $.ajax({
        url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
        type : "POST",
        async : false
    }).responseText;
    var provArr = eval("(" + responseText + ")");
    var str = "";
    for (var i = 0; i < provArr.length; i++) {
        str += "<option title='" + provArr[i].text + "' value='" + provArr[i].text + "'>" + provArr[i].text + "</option>";
    }
    $("#prov").append(str);
})
/**
 * ��̬��Ӵӱ�����
 */
function addItems() {
	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow(i);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell(0);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="ɾ����">';
	var oTL1 = oTR.insertCell(1);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell(2);
	oTL2.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" />'
			+ '<input type="hidden" name="accessorder[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][pattern]" readOnly id="pattern' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][unitName]" readOnly id="unitName' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][warranty]" value="'+$("#warranty").val()+'" id="warranty' + mycount
			+ '" class="txtshort" />';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][proNum]" id="proNum' + mycount
			+ '" class="txtshort" onblur="FloatMul(\'proNum' + mycount
			+ '\',\'price' + mycount + '\',\'subCost' + mycount + '\')" />';
	var oTL8 = oTR.insertCell(8);
	oTL8.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][price]" id="price' + mycount
			+ '" class="txtshort formatMoney" onblur="FloatMul(\'price'
			+ mycount + '\',\'proNum' + mycount + '\',\'subCost' + mycount
			+ '\')" />';
	var oTL9 = oTR.insertCell(9);
	oTL9.innerHTML = '<input type="text" name="accessorder[items][' + mycount
			+ '][subCost]" readOnly id="subCost' + mycount
			+ '" class="readOnlyTxtShort formatMoney" />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	reloadItemProduct();
	// ��� ǧ��λ����
	formateMoney();
	reloadItemCount();
}

/**
 * ɾ����
 */
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="accessorder[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
		reloadItemCount();
	}
}
/**
 * ��ʼ�������嵥
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

/**
 * ���¼����嵥���к�
 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(1).text(i);
					i++;

				}
			})
}

/**
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {// �����ϱ��
		$("#productCode" + i).yxcombogrid_product("remove");
		$("#productCode" + i).yxcombogrid_product({
					hiddenId : 'productId' + i,
					nameCol : 'productCode',
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(i) {
								return function(e, row, data) {
									$('#productName' + i).val(data.productName);
									$("#productType" + i).val(data.proType);
									$("#pattern" + i).val(data.pattern);
									$("#unitName" + i).val(data.unitName);
//									$("#warranty" + i).val(data.warranty);

								}
							}(i)
						}
					}
				})

		$("#productName" + i).yxcombogrid_product("remove");
		$("#productName" + i).yxcombogrid_product({// ����������
			hiddenId : 'productId' + i,
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#productCode' + i).val(data.productCode);
							$("#productType" + i).val(data.proType);
							$("#pattern" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
//							$("#warranty" + i).val(data.warranty);
						}
					}(i)
				}
			}
		})
	}
}
/**
 * ��֤��
 */
function checkForm() {
	var itemscount = parseInt($("#itemscount").val());
	var orderAmount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("������Ϣ����Ϊ�գ���ѡ��...");
			return false;
		}
		if ($("#proNum" + i).val() != "" && $("#isDelTag" + i).val() != 1) {
			var num = $("#proNum" + i).val();
			if (!isNum(num)) {
				alert("����������һ����������");
				return false;
			}
		}
		if ($("#subCost" + i).val() != "" && $("#isDelTag" + i).val() != 1) {
			orderAmount = accAdd($("#subCost" + i).val(), orderAmount, 2);
		}

	}

	$("#saleAmount").val(orderAmount);
	return true;
}

/**
 * �ύ���
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		if (checkForm()) {
			$("#form1")
					.attr("action",
							"?model=service_accessorder_accessorder&action=add&actType=audit");
			$("#form1").submit();
		}
	} else {
		return false;
	}
}