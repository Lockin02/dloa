$(document).ready(function() {

	// $.formValidator.initConfig({
	// theme : "Default",
	// submitOnce : true,
	// formID : "form1",
	// onError : function(msg, obj, errorlist) {
	// alert(msg);
	// }
	// });
	addItems();
	$("#tableDiv").width(document.documentElement.clientWidth - 30);
	/**
	 * 验证信息
	 */
	 validate({
		 "docDate" : {
			 required : true
		 },
//		 "customerName" : {
//			 required : true
//		 },
		 "applyUserName" : {
			 required : true
		 },
		 "prov" : {
			 required : true
		 }
	 });
	 //关联合同
	$("#contractCode").yxcombogrid_allcontract({
		hiddenId : 'contractId',
		valueCol : 'id',
		isFocusoutCheck : false,
		isDown : false,
		gridOptions : {
			param : {
				ExaStatus : '完成'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#contractName").val(data.contractName);
				}
			}
		}
	});
	//获取省份信息
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
									$("#productTypeCode" + i).val(data.properties);
									//根据物料属性编码获取物料属性名称
									$("#productType" + i).val(getDataByCode(data.properties));
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
							$("#productTypeCode" + i).val(data.properties);
							//根据物料属性编码获取物料属性名称
							$("#productType" + i).val(getDataByCode(data.properties));
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
	oTL2.innerHTML = '<img align="absmiddle" src="images/icon/icon105.gif"  onclick="copyItem(this);" title="复制行">';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" />'
			+ '<input type="hidden" name="repairapply[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][productType]" id="productType' + mycount
			+ '" class="readOnlyTxtShort" readOnly />'
			+ '<input type="hidden" name="repairapply[items][' + mycount
			+ '][productTypeCode]" id="productTypeCode' + mycount + '"  />';;
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort" readOnly />';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" readOnly />';
	var oTL8 = oTR.insertCell(8);
	oTL8.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][serilnoName]" id="serilnoName' + mycount
			+ '" class="txt"  />';
	var oTL9 = oTR.insertCell(9);
	oTL9.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][fittings]" id="fittings' + mycount + '" class="txt "  />';
	var oTL10 = oTR.insertCell(10);
	oTL10.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][troubleInfo]" id="troubleInfo' + mycount + '" class="txt " />';
	var oTL11 = oTR.insertCell(11);
	oTL11.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][place]" id="place' + mycount + '" class="txt " />';
	var oTL12 = oTR.insertCell(12);
	oTL12.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][checkInfo]" id="checkInfo' + mycount + '" class="txt " />';
	var oTL13 = oTR.insertCell(13);
	oTL13.innerHTML = '<select name="repairapply[items][' + mycount
			+ '][isGurantee]" id="isGurantee' + mycount
			+ '" class="txtshort" ><option value="0">是</option><option value="1" selected>否</option></select>';
	var oTL14 = oTR.insertCell(14);
	oTL14.innerHTML = '<select  name="repairapply[items][' + mycount
			+ '][repairType]" id="repairType' + mycount
			+ '" class="txtshort " ><option value="0">收费维修</option><option value="1" selected>保内维修</option><option value="2">内部维修</option></select>';
	var oTL15 = oTR.insertCell(15);
	oTL15.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][repairCost]" id="repairCost' + mycount
			+ '" class="txtshort formatMoney" />';
	var oTL16 = oTR.insertCell(16);
	oTL16.innerHTML = '<input type="text" name="repairapply[items][' + mycount
			+ '][cost]" id="cost' + mycount + '" class="txtshort formatMoney" />';

	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
    formateMoney();
	reloadItemProduct();
	reloadItemCount();
}
// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="repairapply[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}
//复制
function copyItem(obj) {
	addItems();
	reloadItemCount();
	var rowNo = obj.parentNode.parentNode.rowIndex - 2;
	var newRowNo = parseInt($("#itemscount").val()) - 1;
	$("#productCode"+newRowNo).val($("#productCode"+rowNo).val());
	$("#productId"+newRowNo).val($("#productId"+rowNo).val());
	$("#productName"+newRowNo).val($("#productName"+rowNo).val());
	$("#productType"+newRowNo).val($("#productType"+rowNo).val());
	$("#productTypeCode"+newRowNo).val($("#productTypeCode"+rowNo).val());
	$("#pattern"+newRowNo).val($("#pattern"+rowNo).val());
	$("#unitName"+newRowNo).val($("#unitName"+rowNo).val());
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

//验证表单并根据从表的收取费用动态计算维修费用
function checkForm() {
	//客户跟部门须至少填写一个
	if($("#customerName").val() == "" && $("#applyDeptName").val() == ""){
		alert("客户名称跟申请部门请至少填写一个");
		return false;
	}
	var itemscount = parseInt($("#itemscount").val());
	var orderAmount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("物料信息不能为空，请选择...");
			return false;
		}

		if ($("#cost" + i).val() != "" && $("#isDelTag" + i).val() != 1) {
			orderAmount += parseInt($("#cost" + i).val());
		}

	}

	$("#subCost").val(orderAmount);
	return true;
}
