$(document).ready(function() {

			// $.formValidator.initConfig({
			// theme : "Default",
			// submitOnce : true,
			// formID : "form1",
			// onError : function(msg, obj, errorlist) {
			// alert(msg);
			// }
			// });
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
	oTL2.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][properties]" id="properties' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][useNum]" id="useNum' + mycount + '" class="txtshort" />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
	reloadItemProduct();
}

// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="bom[items][' + rowNo
				+ '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}
/**
 * 初始化设置清单
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
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
 * 渲染物料信息combogrid
 */
function reloadProduct() {
	$("#productCode").yxcombogrid_product({
				hiddenId : 'productId',
				nameCol : 'productCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$('#productName').val(data.productName);
							$("#pattern").val(data.pattern);
							$("#unitName").val(data.unitName);
							$("#properties").val(data.properties);
						}
					}
				}
			})

	$("#productName").yxcombogrid_product({// 绑定物料名称
		hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#productCode').val(data.productCode);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);
					$("#properties").val(data.properties);
				}
			}
		}
	})
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {// 绑定物料编码
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
									$("#pattern" + i).val(data.pattern);
									$("#unitName" + i).val(data.unitName);
									$("#properties" + i).val(data.properties);

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
							$("#pattern" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#properties" + i).val(data.properties);
						}
					}(i)
				}
			}
		})
	}
}