$(document).ready(function() {
	// 渲染仓库信息下拉表格
	$("#stockName").yxcombogrid_stockinfo({
		nameCol : 'stockName',
		hiddenId : 'stockId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#stockCode").val(data.stockCode);
				}
			}
		}
	});

	window.parent.$("#itembody").children("tr").each(function() {
		if ($(this).find("input[type='checkbox']").attr("checked")) {
			var seNum = $(this).find("input[type='checkbox']").attr("seNum");
			addItem(seNum);
		}
	})

})

/**
 * 动态添加从表数据
 */
function addItem(seNum) {
	$pWin = window.parent;

	var mycount = parseInt($("#coutNumb").val() * 1);
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([ i ]);
	var oTL0 = oTR.insertCell([ 0 ]);
	oTL0.innerHTML = i;
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL1 = oTR.insertCell([ 1 ]);
	oTL1.innerHTML = '<input type="text" class="readOnlyTxtItem" name="fillup[products]['
			+ mycount
			+ '][sequence]" value="'
			+ $pWin.$("#productCode" + seNum).val()
			+ '"  id="sequence'
			+ mycount + '"/>';
	var oTL2 = oTR.insertCell([ 2 ]);
	oTL2.innerHTML = '<input type="text"  name="fillup[products][' + mycount
			+ '][productName]" value="' + $pWin.$("#productName" + seNum).val()
			+ '"  class="readOnlyTxtNormal"  id="productName' + mycount + '" />'
			+ ' <input type="hidden" value="'
			+ $pWin.$("#productId" + seNum).val()
			+ '"  name="fillup[products][' + mycount
			+ '][productId]" id="productId' + mycount + '" readonly/>';
	var oTL3 = oTR.insertCell([ 3 ]);
	oTL3.innerHTML = '<input type="text"  name="fillup[products][' + mycount
			+ '][pattern]" value="' + $pWin.$("#pattern" + seNum).val()
			+ '" class="readOnlyTxtItem" id="pattern' + mycount
			+ '" readonly/>';
	var oTL4 = oTR.insertCell([ 4 ]);
	oTL4.innerHTML = '<input type="text"   name="fillup[products][' + mycount
			+ '][unitName]" value="' + $pWin.$("#unitName" + seNum).val()
			+ '"  class="readOnlyTxtItem" id="unitName' + mycount
			+ '" readonly/>';
	var oTL5 = oTR.insertCell([ 5 ]);
	oTL5.innerHTML = '<input type="text" class="txtshort" name="fillup[products]['
			+ mycount
			+ '][stockNum]" value="'
			+ $pWin.$("#fillNum" + seNum).val()
			+ '" id="stockNum'
			+ mycount
			+ '"  onblur="return checkIsNum(this);" />';
	var oTL6 = oTR.insertCell([ 6 ]);
	oTL6.innerHTML = '<input type="text" class="txtshort" size="10" value="" name="fillup[products]['
			+ mycount + '][intentArrTime]" onfocus="WdatePicker()" />';
	oTL7 = oTR.insertCell([ 7 ]);
	oTL7.innerHTML = '<img src="images/closeDiv.gif" onclick="mydel(this);" title="删除行">';
	$("#coutNumb").val(parseInt($("#coutNumb").val()) + 1);
	// reloadInventoryGrid();
	 reloadItemCount();
}
/** ********************删除动态表单************************ */
function mydel(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
				'<input type="hidden" name="fillup[products][' + rowNo
						+ '][isDelTag]" value="1" id="isDelTag' + rowNo
						+ '" />');
		reloadItemCount();
	}

}
/**
 * 重新计算序列号
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
/** ***************保证补库数量为数字****************** */
function checkIsNum(obj) {
	if (isNaN(obj.value.replace(/,|\s/g, ''))) {
		alert('请输入数字');
		obj.value = "";
		obj.focus();
	}

}
/**
 * 渲染库存信息
 */
function reloadInventoryGrid() {
	var stockId = $('#stockId').val();
	if (stockId == '') {
		stockId = -1;
	}
	var itemscount = document.getElementById("coutNumb").value;

	// 绑定物料编号
	for ( var i = 0; i < itemscount; i++) {
		$("#sequence" + i).yxcombogrid_product('remove');
		$("#sequence" + i)
				.yxcombogrid_product(
						{
							valueCol : 'id',
							nameCol : 'productCode',
							hiddenId : "productId" + i,
							closeCheck : true,
							// checkParam : {
							// 'stockId' : stockId
							// },
							event : {
								'clear' : function(i) {
									return function() {
										$("#productName" + i)
												.yxcombogrid_product(
														'clearValue');
										// $("#productName" + i).val("");
									}
								}(i)
							},
							gridOptions : {
								showcheckbox : false,
								// param : {
								// "stockId" : stockId
								// },
								event : {
									'row_dblclick' : function(i) {
										return function(e, row, data) {
											$("#productName" + i).val(
													data.productName);
											$("#sequence" + i).val(
													data.productCode);
											$("#productId" + i).val(
													data.productId);
											$("#pattern" + i).val(data.pattern);
											$("#unitName" + i).val(
													data.unitName);
											$("#actNum" + i).val(data.actNum);
										}
									}(i)
								}
							}
						});
	}
	// 绑定物料名称
	for ( var i = 0; i < itemscount; i++) {
		$("#productName" + i).yxcombogrid_product('remove');
		$("#productName" + i).yxcombogrid_product(
				{
					valueCol : 'id',
					nameCol : 'productName',
					hiddenId : "productId" + i,
					closeCheck : true,
					// checkParam : {
					// 'stockId' : stockId
					// },
					event : {
						'clear' : function(i) {
							return function() {
								$("#productCode" + i).yxcombogrid_product(
										'clearValue');
							}
						}(i)
					},
					gridOptions : {
						showcheckbox : false,
						// param : {
						// "stockId" : stockId
						// },
						event : {
							'row_dblclick' : function(i) {
								return function(e, row, data) {
									$("#sequence" + i).val(data.productCode);
									$("#productId" + i).val(data.productId);
									$("#pattern" + i).val(data.pattern);
									$("#unitName" + i).val(data.unitName);
									$("#actNum" + i).val(data.actNum);
								}
							}(i)
						}
					}
				});
	}
}

function checkForm() {

	if ($('#stockId').val() == "") {
		alert("请选择仓库");
		return false;
	}
	var itemscount = document.getElementById("coutNumb").value;
	var deleteCount = 0;
	for ( var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() != 1) {
			if ($("#sequence" + i).val() == "") {
				alert("物料信息不能为空，请选择...");
				return false;
				break;
			}
			if ($("#stockNum" + i).val() == "" || $("#stockNum" + i).val() <= 0) {
				alert("补库数量为空或小于0，请重新填写...")
				return false;
				break;
			}
		}
	}

	for ( var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() == 1) {
			deleteCount = deleteCount + 1;
		}
	}
	if (deleteCount == itemscount) {
		alert("请新增物料信息...");
		return false;
	}
	return true;
}
/**
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		if (checkForm()) {
			$("#auditStatus").val("已提交");
			$("#form1").attr("action",
					"?model=stock_fillup_fillup&action=add&actType=audit");
			$("#form1").submit();
		}

	} else {
		return false;
	}
}
