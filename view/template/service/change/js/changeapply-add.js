
/**
 * 通过判断下拉字段值来渲染维修申请单yxcombogrid
 */
function reloadRelDocType() {

	$("#relDocCode").yxcombogrid_reduce("remove");
	$("#relDocCode").val("");
	$("#relDocId").val("");
	reloadItems();

	// 没有源单编号显示清单详细可添加按钮
	if ($("#relDocType").val() == "") {
		$("#absmiddle").show();
		reloadCustomerCombo();
	} else {

		$("#absmiddle").hide();
	}

	if ($("#relDocType").val() == "WXSQD") {
		reloadChangeCombo();
	}
}

$(document).ready(function() {
			// reloadCustomerCombo();

			/**
			 * 验证信息
			 */
			validate({
						"applyUserName" : {
							required : true

						}
					});

			$("#relDocType").bind("change", function() {
						reloadRelDocType();
					})
		})

/**
 * 动态添加从表数据
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
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell(1);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell(2);
	oTL2.innerHTML = '<input type="text" name="changeapply[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" readOnly/>'
			+ '<input type="hidden" name="changeapply[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="changeapply[items][' + mycount
			+ '][productName]" id="productName' + mycount
			+ '" class="txt" readOnly/>';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="readOnlyText" name="changeapply[items]['
			+ mycount + '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" name="changeapply[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" name="changeapply[items][' + mycount
			+ '][serilnoName]" id="serilnoName' + mycount + '" class="txt" />';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" name="changeapply[items][' + mycount
			+ '][remark]" id="remark' + mycount + '" class="txt " />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	reloadItemProduct();
	reloadItemCount();
}

/**
 * 删除行
 */
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="changeapply[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}
/**
 * 初始化设置清单
 */
function reloadItems() {

	$("#customerName").yxcombogrid_customer("remove");
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#productCode" + i).yxcombogrid_reduce("remove");
		$("#productName" + i).yxcombogrid_reduce("remove");
		$("#pattern" + i).yxcombogrid_reduce("remove");
		$("#unitName" + i).yxcombogrid_reduce("remove");
		$("#serilnoName" + i).yxcombogrid_reduce("remove");
		$("#remark" + i).yxcombogrid_reduce("remove");
	}
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

/**
 * 重新计算清单序列号
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
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {// 绑定物料编号
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
									$("#warranty" + i).val(data.warranty);

								}
							}(i)
						}
					}
				})

		$("#productName" + i).yxcombogrid_product("remove");
		$("#productName" + i).yxcombogrid_product({// 绑定物料名称
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
							$("#warranty" + i).val(data.warranty);
						}
					}(i)
				}
			}
		})
	}
}

/**
 * 验证表单
 */
function checkForm() {
	var itembody = $("#itembody tr").size();
	for (var i = 0; i < itembody; i++) {
		if ($("#productCode" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("物料信息不能为空，请选择...");
			return false;
		}

	}
	return true;
}

/**
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		if (checkForm()) {
			$("#form1").attr("action","?model=service_change_changeapply&action=add&actType=audit");
			$("#form1").submit();
		}

	} else {
		return false;
	}
}