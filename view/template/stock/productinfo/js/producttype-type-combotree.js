/**
 * 动态添加配件数据
 */
function addAccess() {
	var mycount = parseInt($("#accessCount").val());
//	alert(mycount)
	var itemtable = document.getElementById("itemAccessTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([i]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delAccessItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<input type="text" class="txtshort" id="aConfigCode'
			+ mycount + '" name="producttype[accessItem][' + mycount
			+ '][configCode]" /><input type="hidden" id="aConfigId' + mycount
			+ '" value="" name="producttype[accessItem][' + mycount
			+ '][configId]" /><input type="hidden" id="aConfigType' + mycount
			+ '" value="typeaccess" name="producttype[accessItem][' + mycount
			+ '][configType]" />';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="txt" id="aConfigName'
			+ mycount + '" name="producttype[accessItem][' + mycount
			+ '][configName]" />';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem" id="aConfigPattern'
			+ mycount
			+ '" name="producttype[accessItem]['
			+ mycount
			+ '][configPattern]" readonly />';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="txtshort" name="producttype[accessItem]['
			+ mycount + '][configNum]" id="configNum'+ mycount + '" />';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="txt" name="producttype[accessItem]['
			+ mycount + '][explains]" id="explains'+ mycount + '" />';
	document.getElementById("accessCount").value = document
			.getElementById("accessCount").value
			* 1 + 1;
	reloadItemProduct();
	reloadAccessCount();
}
/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#accessCount').val();
	for (var i = 0; i < itemscount; i++) {// 绑定物料编码
		if($("#aConfigCode" + i).attr('class') != 'readOnlyTxtShort'){//只读的不做渲染
			$("#aConfigCode" + i).yxcombogrid_product("remove");
			$("#aConfigCode" + i).yxcombogrid_product({
				hiddenId : 'aConfigId' + i,
				nameCol : 'productCode',
				// height : 250,
				// width : 730,
				gridOptions : {
					// isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i) {
							return function(e, row, data) {
								$('#aConfigName' + i).val(data.productName);
								$("#aConfigPattern" + i).val(data.pattern);
							}
						}(i)
					}
				}
			})
		}
	}
	for (var i = 0; i < itemscount; i++) {// 绑定物料名称
		if($("#aConfigName" + i).attr('class') != 'readOnlyTxtNormal'){//只读的不做渲染
			$("#aConfigName" + i).yxcombogrid_product("remove");
			$("#aConfigName" + i).yxcombogrid_product({
				hiddenId : 'aConfigId' + i,
				nameCol : 'productName',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i) {
							return function(e, row, data) {
								$('#aConfigCode' + i).val(data.productCode);
								$("#aConfigPattern" + i).val(data.pattern);
							}
						}(i)
					}
				}
			})
		}
	}
}

/**
 * 重新配件清单序列号
 */
function reloadAccessCount() {
	var i = 1;
	$("#itemAccessBody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;

		}
	})
}
function delAccessItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent()
				.append('<input type="hidden" name="producttype[accessItem]['
						+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
						+ '" />');
	}
	reloadAccessCount();
}
$(function() {
	// 新增分类信息 选择物料类型
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#submitDay").val(treeNode.submitDay);
				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=stock_productinfo_producttype&action=getTreeDataByParentId"
		}
	});

	//工程可用设置
	if ($("#esmCanUse1").val() == "1") {
		$("#esmCanUse").attr("checked", 'checked');
	}

});