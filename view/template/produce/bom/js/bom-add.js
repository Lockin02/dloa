$(document).ready(function() {

			/**
			 * 验证信息
			 */
			validate({
						"productCode" : {
							required : true

						},
						"productName" : {
							required : true

						},
						"version" : {
							required : true

						}
					});
			reloadProduct();
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
			+ '" class="txtshort" /><input type="hidden" name="bom[items]['
			+ mycount + '][productId]" id="productId' + mycount + '" />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="bom[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text"  id="propertiesName' + mycount
			+ '" class="readOnlyTxtShort" />'
			+ '<input type="hidden" name="bom[items][' + mycount
			+ '][properties]" id="properties' + mycount + '" />';
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
							$("#propertiesName")
									.val(getDataByCode(data.properties));
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
					$("#propertiesName").val(getDataByCode(data.properties));
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
							$('#productId' + i).val(data.id);
							$('#productName' + i).val(data.productName);
							$("#pattern" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#properties" + i).val(data.properties);
							$("#propertiesName" + i)
									.val(getDataByCode(data.properties));

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
							$('#productId' + i).val(data.id);
							$('#productCode' + i).val(data.productCode);
							$("#pattern" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#properties" + i).val(data.properties);
							$("#propertiesName" + i)
									.val(getDataByCode(data.properties));
						}
					}(i)
				}
			}
		})
	}
}

Array.prototype.in_array = function(e) { // 判断一个元素是否在数组里面
	for (i = 0; i < this.length; i++) {
		if (this[i] == e)
			return true;
	}
	return false;
}
/**
 * 验证表单
 */
function checkForm() {

	var itembody = $("#itembody tr").size();
	var productCode = $("#productCode").val();// 待设置物料编码
	var productCodeArr = new Array();
	for (var i = 0; i < itembody; i++) {
		if ($("#productCode" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("物料信息不能为空，请选择...");
			return false;
		} else if ($("#isDelTag" + i).val() != 1) {
			if (productCode == $("#productCode" + i).val()) {
				alert("<" + $("#productCode" + i).val() + ">"
						+ "bom材料不能跟你要设置的物料一样!");
				return false;
			} else {
				if (productCodeArr.in_array($("#productCode" + i).val())) {
					alert("bom清单编号为" + $("#productCode" + i).val() + "存在重复!");
					return false;
				} else {
					productCodeArr.push($("#productCode" + i).val());
				}
			}
		}
	}

	/* s:根据物料编码及物料版本号判断是否重复 */
	var bomRepeat = true;
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=produce_bom_bom&action=checkBomReat",
				data : {
					productCode : $("#productCode").val(),
					version : $("#version").val()
				},
				success : function(result) {
					if (result == 0)
						bomRepeat = false;
				}
			})
	if (!bomRepeat) {
		alert("该物料对应版本号的bom设置已经存在!")
		return false;
	}
	/* e:根据物料编码及物料版本号判断是否重复 */

	return true;
}