$(document).ready(function() {

			/**
			 * ��֤��Ϣ
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

// ɾ��
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="bom[items][' + rowNo
				+ '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
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
 * ��Ⱦ������Ϣcombogrid
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

	$("#productName").yxcombogrid_product({// ����������
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
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {// �����ϱ���
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
		$("#productName" + i).yxcombogrid_product({// ����������
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

Array.prototype.in_array = function(e) { // �ж�һ��Ԫ���Ƿ�����������
	for (i = 0; i < this.length; i++) {
		if (this[i] == e)
			return true;
	}
	return false;
}
/**
 * ��֤��
 */
function checkForm() {

	var itembody = $("#itembody tr").size();
	var productCode = $("#productCode").val();// ���������ϱ���
	var productCodeArr = new Array();
	for (var i = 0; i < itembody; i++) {
		if ($("#productCode" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("������Ϣ����Ϊ�գ���ѡ��...");
			return false;
		} else if ($("#isDelTag" + i).val() != 1) {
			if (productCode == $("#productCode" + i).val()) {
				alert("<" + $("#productCode" + i).val() + ">"
						+ "bom���ϲ��ܸ���Ҫ���õ�����һ��!");
				return false;
			} else {
				if (productCodeArr.in_array($("#productCode" + i).val())) {
					alert("bom�嵥���Ϊ" + $("#productCode" + i).val() + "�����ظ�!");
					return false;
				} else {
					productCodeArr.push($("#productCode" + i).val());
				}
			}
		}
	}

	/* s:�������ϱ��뼰���ϰ汾���ж��Ƿ��ظ� */
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
		alert("�����϶�Ӧ�汾�ŵ�bom�����Ѿ�����!")
		return false;
	}
	/* e:�������ϱ��뼰���ϰ汾���ж��Ƿ��ظ� */

	return true;
}