
/**
 * 渲染物料清单收料仓库combogrid
 */
function reloadItemStock() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#inStockName" + i).yxcombogrid_stockinfo("remove");
		$("#inStockName" + i).yxcombogrid_stockinfo({
			hiddenId : 'inStockId' + i,
			nameCol : 'stockName',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#inStockId' + i).val(data.id);
							$('#inStockCode' + i).val(data.stockCode);
							//清空物料信息
							$('#productId' + i).val("");
							$('#productCode' + i).val("");
							$('#productName' + i).val("");
							$("#pattern" + i).val("");
							$("#unitName" + i).val("");
							//重新渲染物料下拉combogrid
							reloadItemInventory(i);
						}
					}(i)
				}
			}
		})
	}
}
//判断仓库是否为空
function hasStock(mycount){
	if( !$('#inStockId'+mycount).val() ){
		alert('选择物料之前必须先选择仓库！');
	}
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemInventory(i) {
	var stockId = $('#inStockId'+i).val();
	if(!stockId){
		stockId=-1;
	}
	var itemscount = $('#itemscount').val();
	$("#productCode" + i).yxcombogrid_inventory("remove");
	$("#productCode" + i).yxcombogrid_inventory({
		hiddenId : 'productId' + i,
		nameCol : 'productCode',
		event : {
			'show_combo' : function() {
					hasStock(i);
				}
		},
		gridOptions : {
			param : {
				'stockId' : parseInt(stockId)
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
						$('#productName' + i).val(data.productName);
						$("#pattern" + i).val(data.pattern);
						$("#unitName" + i).val(data.unitName);
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
		$("#inStockName" + i).yxcombogrid_stockinfo("remove");
	}
	$("#itembody").html("");
	$('#itemscount').val(0);
	addItems();
}

/**
 * 动态添加从表数据
 */
function addItems() {
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
			+ '" class="txtshort"/>'
			+ '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell([2]);
	oTL3.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][productName]" id="productName' + mycount
			+ '" class="readOnlyTxtItem" />';
	var oTL4 = oTR.insertCell([3]);
	oTL4.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtItem" />';
	var oTL5 = oTR.insertCell([4]);
	oTL5.innerHTML = ' <input type="text" name="checkinfo[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" />';
	var oTL6 = oTR.insertCell([5]);
	oTL6.innerHTML = ' <input type="text" name="checkinfo[items][' + mycount
			+ '][batchNo]" id="batchNo' + mycount + '" class="readOnlyTxtShort" />';
	var oTL7 = oTR.insertCell([6]);
	oTL7.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][billNum]" id="billNum' + mycount
			+ '" class="readOnlyTxtShort" onblur="countFun(\'billNum' + mycount
			+ '\',\'actNum' + mycount + '\',\'adjustNum' + mycount + '\')"/>';
	var oTL8 = oTR.insertCell([7]);
	oTL8.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][actNum]" id="actNum' + mycount
			+ '" class="txtshort" onblur="countFun(\'billNum' + mycount
			+ '\',\'actNum' + mycount + '\',\'adjustNum' + mycount
			+ '\');FloatMul(\'actNum' + mycount + '\',\'price' + mycount
			+ '\',\'subPrice' + mycount + '\')" />'
			+ '<input type="hidden"/>';
	var oTL9 = oTR.insertCell([8]);
	oTL9.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][adjustNum]" id="adjustNum' + mycount
			+ '" class="txtshort"/>';
	var oTL10 = oTR.insertCell([9]);
	oTL10.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][price]" id="price' + mycount
			+ '" class="txtshort formatMoney" onblur="FloatMul(\'price'
			+ mycount + '\',\'actNum' + mycount + '\',\'subPrice' + mycount
			+ '\')" />';
	var oTL11 = oTR.insertCell([10]);
	oTL11.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][subPrice]" id="subPrice' + mycount
			+ '" class="readOnlyTxtShort formatMoney" />';
	var oTL12 = oTR.insertCell([11]);
	oTL12.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][inStockName]" id="inStockName' + mycount
			+ '" class="txtshort" />'
			+ '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][inStockId]" id="inStockId' + mycount + '" />'
			+ '<input type="hidden" name="checkinfo[items][' + mycount
			+ '][inStockCode]" id="inStockCode' + mycount + '" />';
	var oTL13 = oTR.insertCell([12]);
	oTL13.innerHTML = '<input type="text" name="checkinfo[items][' + mycount
			+ '][remark]" id="remark' + mycount
			+ '" class="txtshort" />';
	var oTL14 = oTR.insertCell([13]);
	oTL14.innerHTML = '<img src="images/closeDiv.gif" onclick="delItem(this);" title="删除行">';

	// 金额 千分位处理
	formateMoney();
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
	reloadItemCount()
	reloadItemStock();
	reloadItemInventory(mycount);
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
// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 1;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="checkinfo[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
	}
	reloadItemCount();
}
/**
 * 计算从表::调整数量
 * @param
 */
function countFun( billNum,actNum,adjustNum ){
	var thisBillNum = $('#' + billNum).val().replace(/,|\s/g,'')*1;
	var thisActNum = $('#' + actNum).val().replace(/,|\s/g,'')*1;
	$('#' + adjustNum).val(thisActNum+thisBillNum);
}
