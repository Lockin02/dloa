

$(document).ready(function() {
			var itemscount = $('#itemscount').val();
			$("#stockName").yxcombogrid_stockinfo({// ����ֿ�combogrid��Ⱦ
				hiddenId : 'stockId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$('#stockId').val(data.id);
							$('#stockCode').val(data.stockCode);
							$('#stockName').val(data.stockName);
							for (var i = 0; i < itemscount; i++) {
								if (!$('#stockName' + i).val()) {
									$('#stockId' + i).val(data.id);
									$('#stockCode' + i).val(data.stockCode);
									$('#stockName' + i).val(data.stockName);
								}
							}
						}
					}
				}
			})

			for (var i = 0; i < itemscount; i++) {
				reloadItemStock();
				reloadItemInventory(i);
			}

			// ��Աѡ�����--������
			$("#dealUserName").yxselect_user({
						hiddenId : 'dealUserId'
					});
		})

/**
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#stockName" + i).yxcombogrid_stockinfo("remove");
		$("#stockName" + i).yxcombogrid_stockinfo({
					hiddenId : 'stockId' + i,
					nameCol : 'stockName',
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(i) {
								return function(e, row, data) {
									$('#stockId' + i).val(data.id);
									$('#stockCode' + i).val(data.stockCode);
									// ���������Ϣ
									$('#productId' + i).val("");
									$('#productCode' + i).val("");
									$('#productName' + i).val("");
									$("#pattern" + i).val("");
									$("#unitName" + i).val("");
									$("#billNum" + i).val("");
									$("#actNum" + i).val("");
									$("#adjustNum" + i).val("");
									$("#price" + i).val("");
									$("#price" + i + "_v").val("");
									$("#subPrice" + i).val("");
									$("#subPrice" + i + "_v").val("");

									// ������Ⱦ��������combogrid
									reloadItemInventory(i);
								}
							}(i)
						}
					}
				})
	}
}
// �жϲֿ��Ƿ�Ϊ��
function hasStock() {
	if ($('#stockId').val() == "") {
		var itemscount = $("#itemscount").val();
		for (var i = 0; i < itemscount; i++) {
			if ($("#stockName" + i).val() == ""
					&& $("#isDelTag" + i).val() != 1) {
				alert('����ѡ��ֿ⣡');
				return false;
			}
		}
	}
}

/**
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemInventory(i) {
	var stockId = $('#stockId' + i).val();
	if (!stockId) {
		stockId = -1;
	}
	var itemscount = $('#itemscount').val();

	// �����ϱ��
	$("#productCode" + i).yxcombogrid_inventory("remove");
	$("#productCode" + i).yxcombogrid_inventory({
		hiddenId : 'productId' + i,
		nameCol : 'productCode',
		valueCol : 'productId',
		checkParam : {
			'stockId' : parseInt(stockId)
		},
		event : {
			'show_combo' : function() {
				hasStock(i);
			},
			'clear' : function(i) {
				return function() {
					if ($("#productName" + i).val() != "") {
						$("#productName" + i)
								.yxcombogrid_inventory('clearValue');
					}

				}
			}(i)
		},
		gridOptions : {
			param : {
				'stockId' : parseInt(stockId)
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#productId' + i).val(data.productId);
					$('#productName' + i).val(data.productName);
					$("#pattern" + i).val(data.pattern);
					$("#unitName" + i).val(data.unitName);
					$("#billNum" + i).val(data.actNum);

				}
			}
		}
	})
	// ����������
	$("#productName" + i).yxcombogrid_inventory("remove");
	$("#productName" + i).yxcombogrid_inventory({
		hiddenId : 'productId' + i,
		nameCol : 'productName',
		valueCol : 'productId',
		checkParam : {
			'stockId' : parseInt(stockId)
		},
		event : {
			'show_combo' : function() {
				hasStock(i);
			},
			'clear' : function(i) {
				return function() {
					if ($("#productCode" + i).val() != "") {
						$("#productCode" + i)
								.yxcombogrid_inventory('clearValue');

					}
				}
			}(i)
		},
		gridOptions : {
			param : {
				'stockId' : parseInt(stockId)
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#productId' + i).val(data.productId);
					$('#productCode' + i).val(data.productCode);
					$("#pattern" + i).val(data.pattern);
					$("#unitName" + i).val(data.unitName);
					$("#billNum" + i).val(data.actNum);

				}
			}
		}
	})
}
/**
 * ��ʼ�������嵥��
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#productCode" + i).yxcombogrid_product("remove");
		$("#productName" + i).yxcombogrid_product("remove");
		$("#stockName" + i).yxcombogrid_stockinfo("remove");
	}
	$("#itembody").html("");
	$('#itemscount').val(0);
	addItems();
}

/**
 * ���¼��������嵥���к�
 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(0).text(i);
					i++;

				}
			})
}

/**
 * ��̬��Ӵӱ�����
 */
function addItems() {
	var mStockId = $("#stockId").val();
	var mStockCode = $("#stockCode").val();
	var mStockName = $("#stockName").val();

	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL1 = oTR.insertCell([0]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([1]);
	oTL2.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" />'
			+ '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell([2]);
	oTL3.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell([3]);
	oTL4.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtItem" readOnly />';
	var oTL5 = oTR.insertCell([4]);
	oTL5.innerHTML = ' <input type="text" name="checkinfo[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtItem"  readOnly/>';
	var oTL6 = oTR.insertCell([5]);
	oTL6.innerHTML = ' <input type="text" name="checkinfo[items][' + mycount
			+ '][batchNo]" id="batchNo' + mycount
			+ '" class="readOnlyTxtItem" readOnly/>';
	var oTL7 = oTR.insertCell([6]);
	oTL7.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][billNum]" id="billNum' + mycount
			+ '" class="readOnlyTxtItem" readOnly onblur="countFun(\'billNum'
			+ mycount + '\',\'actNum' + mycount + '\',\'adjustNum' + mycount
			+ '\')"/>';
	var oTL8 = oTR.insertCell([7]);
	oTL8.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][actNum]" id="actNum' + mycount
			+ '" class="txtshort" onblur="countFun(\'billNum' + mycount
			+ '\',\'actNum' + mycount + '\',\'adjustNum' + mycount
			+ '\');FloatMul(\'adjustNum' + mycount + '\',\'price' + mycount
			+ '\',\'subPrice' + mycount + '\')" />' + '<input type="hidden"/>';
	var oTL9 = oTR.insertCell([8]);
	oTL9.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][adjustNum]" id="adjustNum' + mycount
			+ '" class="readOnlyTxtItem" readOnly/>';
	var oTL10 = oTR.insertCell([9]);
	oTL10.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][price]" id="price' + mycount
			+ '" class="txtshort formatMoney" onblur="FloatMul(\'price'
			+ mycount + '\',\'adjustNum' + mycount + '\',\'subPrice' + mycount
			+ '\')" />';
	var oTL11 = oTR.insertCell([10]);
	oTL11.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][subPrice]" id="subPrice' + mycount
			+ '" class="readOnlyTxtItem formatMoney" readOnly />';
	var oTL12 = oTR.insertCell([11]);
	oTL12.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][stockName]" id="stockName' + mycount
			+ '" class="txtshort" value="' + mStockName + '" />'
			+ '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][stockId]" id="stockId' + mycount + '" value="' + mStockId
			+ '" />' + '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][stockCode]" id="stockCode' + mycount + '" value="'
			+ mStockCode + '" />';
	var oTL13 = oTR.insertCell([12]);
	oTL13.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][remark]" id="remark' + mycount + '" class="txtshort" />';
	var oTL14 = oTR.insertCell([13]);
	oTL14.innerHTML = '<img src="images/closeDiv.gif" onclick="delItem(this);" title="ɾ����">';

	// ��� ǧ��λ����
	formateMoney();
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
	reloadItemStock();
	reloadItemInventory(mycount);
	reloadItemCount();
}

// ɾ��
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="checkinfo[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
	}
	reloadItemCount();
}
/**
 * ����ӱ�::��������
 * 
 * @param
 */
function countFun(billNum, actNum, adjustNum) {

	var thisBillNum = $('#' + billNum).val().replace(/,|\s/g, '') * 1;
	var thisActNum = $('#' + actNum).val().replace(/,|\s/g, '') * 1;

	if (thisBillNum >= thisActNum) {
		alert("����ʵ����������С�ڻ�����ʴ�����! ");
	} else {
		$('#' + adjustNum).val(thisActNum - thisBillNum);
	}
}
// ��ʾѡ��ֿ�
function checkForm() {

	var itemscount = document.getElementById("itemscount").value;
	var deleteCount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("������Ϣ����Ϊ�գ���ѡ��...");
			return false;
			break;
		}
	}

	for (var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() == 1) {
			deleteCount = deleteCount + 1;

		}

	}
	if (deleteCount == itemscount) {
		alert("������������Ϣ...");
		return false;
	}
	if (itemscount < 1) {
		alert("��ѡ��������Ϣ...");
		return false;
	} else {
		for (var i = 0; i < itemscount; i++) {
			if ($("#isDelTag" + i).val() != 1) {
				if ($("#actNum" + i).val() == "") {
					alert("ʵ����������Ϊ�գ�����д");
					return false;
					break;
				}

				if (parseInt($("#billNum" + i).val()) >= parseInt($("#actNum"
						+ i).val())) {
					alert("ʵ����������С�ڻ�����ʴ�����������д��ȷ����...");
					return false;
					break;
				}
			}
		}
	}
	return true;
}

function confirmAudit() {// ���ȷ��
	var auditDate = $("#docDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {

			if (checkForm()) {
				$("#auditStatus").val("YPD");
				$("#form1")
						.attr("action",
								"?model=stock_check_checkinfo&action=edit&actType=audit");
				$("#form1").submit();
			}
		}
	}
}

function couldAudit(auditDate) {// �����Ƿ��ѹ���,�������ڲ��������Ƿ��ѽ����ж�
	var resultTrue = true;
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=finance_period_period&action=isClosed",
				data : {},
				success : function(result) {
					if (result == 1) {
						alert("�����ѹ��ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
						resultTrue = false;
					}

				}
			})
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=finance_period_period&action=isLaterPeriod",
				data : {
					thisDate : auditDate
				},
				success : function(result) {
					if (result == 0) {
						alert("�������ڲ��������Ѿ����ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
						resultTrue = false;
					}
				}
			})
	return resultTrue;
}