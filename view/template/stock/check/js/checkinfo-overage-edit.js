

$(document).ready(function() {
			var itemscount = $('#itemscount').val();
			$("#stockName").yxcombogrid_stockinfo({// 主表仓库combogrid渲染
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

			// 人员选择组件--经办人
			$("#dealUserName").yxselect_user({
						hiddenId : 'dealUserId'
					});
		})

/**
 * 渲染物料清单收料仓库combogrid
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
									// 清空物料信息
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

									// 重新渲染物料下拉combogrid
									reloadItemInventory(i);
								}
							}(i)
						}
					}
				})
	}
}
// 判断仓库是否为空
function hasStock() {
	if ($('#stockId').val() == "") {
		var itemscount = $("#itemscount").val();
		for (var i = 0; i < itemscount; i++) {
			if ($("#stockName" + i).val() == ""
					&& $("#isDelTag" + i).val() != 1) {
				alert('请先选择仓库！');
				return false;
			}
		}
	}
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemInventory(i) {
	var stockId = $('#stockId' + i).val();
	if (!stockId) {
		stockId = -1;
	}
	var itemscount = $('#itemscount').val();

	// 绑定物料编号
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
	// 绑定物料名称
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
 * 初始化物料清单表单
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
 * 重新计算物料清单序列号
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
 * 动态添加从表数据
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
	oTL14.innerHTML = '<img src="images/closeDiv.gif" onclick="delItem(this);" title="删除行">';

	// 金额 千分位处理
	formateMoney();
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
	reloadItemStock();
	reloadItemInventory(mycount);
	reloadItemCount();
}

// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="checkinfo[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
	}
	reloadItemCount();
}
/**
 * 计算从表::调整数量
 * 
 * @param
 */
function countFun(billNum, actNum, adjustNum) {

	var thisBillNum = $('#' + billNum).val().replace(/,|\s/g, '') * 1;
	var thisActNum = $('#' + actNum).val().replace(/,|\s/g, '') * 1;

	if (thisBillNum >= thisActNum) {
		alert("错误！实存数量不能小于或等于帐存数量! ");
	} else {
		$('#' + adjustNum).val(thisActNum - thisBillNum);
	}
}
// 提示选择仓库
function checkForm() {

	var itemscount = document.getElementById("itemscount").value;
	var deleteCount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("物料信息不能为空，请选择...");
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
		alert("请新增物料信息...");
		return false;
	}
	if (itemscount < 1) {
		alert("请选择物料信息...");
		return false;
	} else {
		for (var i = 0; i < itemscount; i++) {
			if ($("#isDelTag" + i).val() != 1) {
				if ($("#actNum" + i).val() == "") {
					alert("实存数量不能为空，请填写");
					return false;
					break;
				}

				if (parseInt($("#billNum" + i).val()) >= parseInt($("#actNum"
						+ i).val())) {
					alert("实存数量不能小于或等于帐存数量，请填写正确数据...");
					return false;
					break;
				}
			}
		}
	}
	return true;
}

function confirmAudit() {// 审核确认
	var auditDate = $("#docDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("审核后单据将不可修改，你确定要审核吗?")) {

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

function couldAudit(auditDate) {// 财务是否已关账,单据所在财务周期是否已结账判断
	var resultTrue = true;
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=finance_period_period&action=isClosed",
				data : {},
				success : function(result) {
					if (result == 1) {
						alert("财务已关账，不能进行审核，请联系财务人员进行反关账！")
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
						alert("单据所在财务周期已经结账，不能进行审核，请联系财务人员进行反结账！")
						resultTrue = false;
					}
				}
			})
	return resultTrue;
}