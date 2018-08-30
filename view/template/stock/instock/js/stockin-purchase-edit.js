// 审核确认
function confirmAudit() {
	var relDocType = $("#relDocType").val();
	var auditDate = $("#auditDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
			$("#form1").attr("action",
				"?model=stock_instock_stockin&action=edit&actType=audit");
			if (checkForm()) {
				if(relDocType == "RHHRK"){
					if (checkProNumIntime() && checkSerioNum() && chkAvailableEquNum()) {
						$("#docStatus").val("YSH");
						$("#form1").submit();
					}
				}else{
					if (checkProNumIntime() && checkSerioNum()) {
						$("#docStatus").val("YSH");
						$("#form1").submit();
					}
				}
			}
		}
	}
}

// 检查可下推的入库数量
function chkAvailableEquNum(){
	var relDocId = $("#relDocId").val();
	var relDocCode = $("#relDocCode").val();
	var mainId = $("#mainId").val();


	var itemscount = $("#itemscount").val();
	var products = {};
	for (var i = 0; i < itemscount; i++) {
		var actNum = $("#actNum" + i).val();
		var equId = $("#relDocId" + i).val();
		if(products[$("#productCode"+i).val()] == undefined){
			products[equId] = {
				"productCode" : $("#productCode"+i).val(),
				"num" : 0
			};
		}

		products[equId].num += parseInt(actNum);
	}

	var responseText = $.ajax({
		url:'index1.php?model=stock_instock_stockin&action=ajaxChkAvailableEquNum',
		data : {
			"notInIds" : mainId,
			"relDocId" : relDocId,
			"relDocCode" : relDocCode,
			"products" : products
		},
		type : "POST",
		async : false
	}).responseText;
	var resultObj = eval("("+responseText+")");

	if(resultObj.result != "ok" && resultObj.msg != ""){
		var alertMsg = "下推的物料: "+resultObj.msg+" 已超出可下推数量,请检查所有源单 "+relDocCode+" 关联的入库单。";
		alert(alertMsg);
		return false;
	}else{
		return true;
	}
}

/**
 * 校验需要输入序列号的个数
 */
function checkSerioNum() {
	var checkResult = true;
	// 校验需要输入序列号的物料不能为一部分
	var itemscount = $("#itemscount").val();
	for (var i = 0; i < itemscount; i++) {
		var serialnoNameStr = $("#serialSequence" + i).val();
		if ($("#isDelTag" + i).val() != 1 && serialnoNameStr != "") {
			if (serialnoNameStr.split(",").length < $("#actNum" + i).val()) {
				alert($("#productName" + i).val() + "已录入的序列号小于实收数量！")
				checkResult = false;
				break;
			}
		}
	}
	return checkResult;
}

function couldAudit(auditDate) {// 财务是否已关账,单据所在财务周期是否已结账判断
	var resultTrue = true;
	$.ajax({
		type: "POST",
		async: false,
		url: "?model=finance_period_period&action=isClosed",
		data: {},
		success: function(result) {
			if (result == 1) {
				alert("财务已关账，不能进行审核，请联系财务人员进行反关账！")
				resultTrue = false;
			}

		}
	});
	$.ajax({
		type: "POST",
		async: false,
		url: "?model=finance_period_period&action=isLaterPeriod",
		data: {
			thisDate: auditDate
		},
		success: function(result) {
			if (result == 0) {
				alert("单据所在财务周期已经结账，不能进行审核，请联系财务人员进行反结账！")
				resultTrue = false;
			}
		}
	});
	return resultTrue;
}
function subTotalPrice1(el) {// 计算物料金额
	var proNumVal = parseInt($(el).parent().next().children().eq(0).val());
	if (proNumVal >= 0) {
		$(el).parent().next().next().children().eq(0).val($(el).val()
		* proNumVal);
	}
}

function subTotalPrice2(el) {// 计算物料金额
	var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
	if (proNumVal >= 0) {
		$(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
	}
}

// 根据红篮色单控制源单类型
function checkRelDocType() {
	if ($("#isRed").val() == 0) {
		$("#itembody").css("color", "blue");
		$(".main_head_title").html('<font color="blue">外购入库单</font>');
		var slOption = false;
		$("#relDocType option").each(function() {
			if ($(this).val() == "RTLTZD") {
				$(this).remove();
			}
			if ($(this).val() == "RSLTZD") {
				slOption = true;
			}
		});
		if (!slOption) {
			$("#relDocType").append("<option value='RCGJYSQD'>采购检验申请单</option>").append("<option value='RSLTZD'>收料通知单</option>");
		}
	} else {
		$("#itembody").css("color", "red");
		$(".main_head_title").html('<font color="red">外购入库单</font>');
		var tlOption = false;
		$("#relDocType option").each(function() {
			if ($(this).val() == "RSLTZD"
				|| $(this).val() == "RCGJYSQD") {
				$(this).remove();
			}
			if ($(this).val() == "RTLTZD") {
				tlOption = true;
			}
		});
		if (!tlOption) {
			$("#relDocType").append("<option value='RTLTZD'>退料通知单</option>");
		}
	}
}

/**
 * 初始化物料清单表单
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#productCode" + i).yxcombogrid_product("remove");
		$("#productName" + i).yxcombogrid_product("remove");
		$("#inStockName" + i).yxcombogrid_stockinfo("remove");
	}
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

/**
 * 渲染物料清单收料仓库combogrid
 */
function reloadItemStock() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#inStockName" + i).yxcombogrid_stockinfo("remove").yxcombogrid_stockinfo({
			hiddenId: 'inStockId' + i,
			nameCol: 'stockName',
			gridOptions: {
				showcheckbox: false,
				model: 'stock_stockinfo_stockinfo',
				action: 'pageJson',
				event: {
					'row_dblclick': function(i) {
						return function(e, row, data) {
							$('#inStockCode' + i).val(data.stockCode);
						}
					}(i)
				}
			}
		});
	}
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		if ($("#relDocId" + i).val() == "" || $("#relDocId" + i).val() == "0") {
			$("#productCode" + i).yxcombogrid_product("remove").yxcombogrid_product({// 绑定物料编码
				hiddenId: 'productId' + i,
				nameCol: 'productCode',
				// height : 250,
				// width : 730,
				gridOptions: {
					// isTitle : true,
					showcheckbox: false,
					event: {
						'row_dblclick': function(i) {
							return function(e, row, data) {
                                var proType=getParentProType(data.proTypeId);
                                $('#proType' + i).val(proType);
								$('#productName' + i).val(data.productName);
								$("#k3Code" + i).val(data.ext2);
								$("#pattern" + i).val(data.pattern);
								$("#unitName" + i).val(data.unitName);
								$("#warranty" + i).val(data.warranty);
							}
						}(i)
					}
				}
			});

			$("#productName" + i).yxcombogrid_product("remove").yxcombogrid_product({
				hiddenId: 'productId' + i,
				nameCol: 'productName',
				gridOptions: {
					showcheckbox: false,
					event: {
						'row_dblclick': function(i) {
							return function(e, row, data) {
                                var proType=getParentProType(data.proTypeId);
                                $('#proType' + i).val(proType);
								$('#productCode' + i).val(data.productCode);
								$("#k3Code" + i).val(data.ext2);
								$("#pattern" + i).val(data.pattern);
								$("#unitName" + i).val(data.unitName);
								$("#warranty" + i).val(data.warranty);
							}
						}(i)
					}
				}
			});
		}
	}
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
	});
}

/**
 * 动态添加从表数据
 */
function addItems() {
	var mStockId = $("#inStockId").val();
	var mStockCode = $("#inStockCode").val();
	var mStockName = $("#inStockName").val();

	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<input type="hidden" name="stockin[items][' + mycount
	+ '][serialSequence]" id="serialSequence' + mycount
	+ '" /><input type="hidden" name="stockin[items][' + mycount
	+ '][serialRemark]" id="serialRemark' + mycount + '" />'
	+ '<input type="text" name="stockin[items][' + mycount
	+ '][productCode]" id="productCode' + mycount
	+ '" class="txtshort" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][productId]" id="productId' + mycount + '"  />';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="stockin[items][' + mycount
        + '][proType]" id="proType' + mycount
        + '" class="readOnlyTxtShort" readonly="readonly"/>'
        + '<input type="hidden" name="stockin[items][' + mycount
        + '][proTypeId]" id="proTypeId' + mycount + '"/>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][k3Code]" id="k3Code' + mycount + '" class="readOnlyTxtShort" readonly="readonly"/>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][pattern]" id="pattern' + mycount
	+ '" class="readOnlyTxtShort" readonly="readonly"/>';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = ' <input type="text" name="stockin[items][' + mycount
	+ '][unitName]" id="unitName' + mycount
	+ '" class="readOnlyTxtMin" readonly="readonly"/>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = ' <input type="text" name="stockin[items][' + mycount
	+ '][batchNum]" id="batchNum' + mycount + '" class="txtshort" />';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][storageNum]" id="storageNum' + mycount
	+ '" class="readOnlyTxtShort" readonly="readonly"/>';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][actNum]" id="actNum' + mycount
	+ '" class="txtshort" onfocus="exploreProTipInfo(' + mycount + ')" ondblclick="serialNoDeal(this,' + mycount
	+ ')" onblur="FloatMul(\'actNum' + mycount + '\',\'price' + mycount
	+ '\',\'subPrice' + mycount + '\')" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][serialnoId]" id="serialnoId' + mycount + '"  />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][serialnoName]" id="serialnoName' + mycount + '"  />';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][inStockName]" id="inStockName' + mycount
	+ '" class="txtshort" value="' + mStockName + '" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][inStockId]" id="inStockId' + mycount + '" value="' + mStockId
	+ '" />' + '<input type="hidden" name="stockin[items][' + mycount
	+ '][inStockCode]" id="inStockCode' + mycount + '" value="'
	+ mStockCode + '" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][relDocId]" id="relDocId' + mycount + '" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][relDocName]" id="relDocName' + mycount + '" />'
	+ '<input type="hidden" name="stockin[items][' + mycount
	+ '][relDocCode]" id="relDocCode' + mycount + '" />';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][price]" id="price' + mycount
	+ '" class="txtshort formatMoneySix" onblur="FloatMul(\'price'
	+ mycount + '\',\'actNum' + mycount + '\',\'subPrice' + mycount
	+ '\')" />';
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][subPrice]" id="subPrice' + mycount
	+ '" class="readOnlyTxtShort formatMoney" readonly="readonly"/>';
	var oTL14 = oTR.insertCell([14]);
	oTL14.innerHTML = '<input type="text" name="stockin[items][' + mycount
	+ '][warranty]" id="warranty' + mycount
	+ '" class="readOnlyTxtShort" readonly="readonly"/>';

	// 金额 千分位处理
	formateMoney();
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	reloadItemStock();
	reloadItemProduct();
	reloadItemCount();

}
// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		// $("#itembody")
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="stockin[items]['
		+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
		+ '" />');
		reloadItemCount();
	}
}

// 序列号处理
function serialNoDeal(el, elNum) {
	if ($("#isRed").val() == 0) {
		toAddSerialNo(el, elNum);
	} else {
		chooseSerialNo(elNum);
	}
}

// 蓝色单录入序列号
function toAddSerialNo(el, elNum) {
	var productNum = $(el).val();
	var productId = $("#productId" + elNum).val();
	var productCode = $("#productCode" + elNum).val();
	var productName = $("#productName" + elNum).val();

	var inStockId = $("#inStockId" + elNum).val();
	var inStockCode = $("#inStockCode" + elNum).val();
	var inStockName = $("#inStockName" + elNum).val();
	var pattern = $("#pattern" + elNum).val();
	var serialSequence = $("#serialSequence" + elNum).val();
	var serialRemark = $("#serialRemark" + elNum).val();
	var serialId = $("#serialnoId" + elNum).val();

	var cacheResult = false;
	var productCodeSeNum = productCode + "_" + elNum;
	$.ajax({// 缓存序列号
		type: "POST",
		async: false,
		url: "?model=stock_serialno_serialno&action=cacheSerialno",
		data: {
			"serialSequence": serialSequence,
			"productCodeSeNum": productCodeSeNum
		},
		success: function(result) {

			if (result == 1) {
				cacheResult = true;
			}

		}
	});
	if (cacheResult) {
		var url = "index1.php?model=stock_serialno_serialno&action=toAdd&productId="
			+ productId
			+ "&productCode="
			+ productCode
			+ "&productName="
			+ productName
			+ "&inStockId="
			+ inStockId
			+ "&inStockCode="
			+ inStockCode
			+ "&inStockName="
			+ inStockName
			+ "&productNum="
			+ productNum
			+ "&pattern="
			+ pattern
			+ "&elNum="
			+ elNum
			+ "&serialRemark="
			+ serialRemark
			+ "&serialId="
			+ serialId
			+ "&productCodeSeNum=" + productCodeSeNum;
		if (productId != "" && inStockId != "")
			showThickboxWin(url
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
		else
			alert("请先输入物料信息及收料仓库信息!");
	}
}

/**
 * 红色单选择序列号
 */
function chooseSerialNo(seNum) {
	var productIdVal = $("#productId" + seNum).val();
	var serialnoId = $("#serialnoId" + seNum).val();
	var serialnoName = $("#serialnoName" + seNum).val();

	var cacheResult = false;
	var productCodeSeNum = $("#productCode" + seNum).val() + "_" + seNum;
	$.ajax({// 缓存序列号
		type: "POST",
		async: false,
		url: "?model=stock_serialno_serialno&action=cacheSerialno",
		data: {
			"serialSequence": serialnoName,
			"productCodeSeNum": productCodeSeNum
		},
		success: function(result) {
			if (result == 1) {
				cacheResult = true;
			}
		}
	});

	if (cacheResult) {
		if (productIdVal != "") {
			showThickboxWin("?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
			+ serialnoId
			+ "&productId="
			+ productIdVal
			+ "&elNum="
			+ seNum
			+ "&productCodeSeNum="
			+ productCodeSeNum
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=650")
		} else {
			alert("请先选择物料!");
		}
	}
}

function checkForm() {// 表单校验
    var itemscount = $("#itemscount").val();
    var deleteCount = 0;
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
                var productIdObj = $("#productId" + i);
                if (productIdObj.length == 0) continue;
                if (productIdObj.val() == "") {
                    alert("物料信息不能为空，请选择...");
                    return false;
                }
                if ($("#inStockId" + i).val() == ""
                    || parseInt($("#inStockId" + i).val()) == 0) {
                    alert("收料仓库不能为空，请选择...");
                    return false;
                }
                // 检查入库数量是否合法
                var actNum = $("#actNum" + i);
                if (!isNum(actNum.val())) {// 判断数量
                    alert("实收数量" + "<" + actNum.val() + ">" + "填写有误!");
                    actNum.focus();
                    return false;
                }
                if($("#relDocType").val() == "RZCRK" && $("#relDocId" + i).val() != '' && $("#isRed").val() == "0" && isNum($("#relDocId" + i).val())){//用于资产蓝色入库,新OA单据不做校验
                	var num = 0;
                    $.ajax({//获取该物料实际可入库数量
                        type: "POST",
                        async: false,
                        url: "?model=asset_require_requireoutitem&action=getNumAtInStock",
                        data: {
                            "id": $("#relDocId" + i).val()
                        },
                        success: function (data) {
                        	num = data;
                        }
                    });
                    if(num <= 0){
                        alert("名称为" + "<" + $("#productName" + i).val() + ">" + "的物料已入库完毕，无须再次提交");
                        return false;
                    }
                    if (actNum.val()*1 > num) {
                        alert("名称为" + "<" + $("#productName" + i).val() + ">" + "的物料的实收数量不能超过" + num);
                        actNum.focus();
                        return false;
                    }
                }else if($("#relDocType").val() == "RSCJHD" && $("#relDocId" + i).val() != '' && $("#productId" + i).val() != '' && $("#isRed").val() == "0"){//用于生产计划单蓝色入库
                	var num = 0;
                    $.ajax({//获取该物料实际可入库数量
                        type: "POST",
                        async: false,
                        url: "?model=stock_withdraw_noticeequ&action=getNumAtInStock",
                        data: {
                            "mainId": $("#relDocId" + i).val(),
                            "productId": $("#productId" + i).val()
                        },
                        success: function (data) {
                        	num = data;
                        }
                    });
                    if(num <= 0){
                        alert("名称为" + "<" + $("#productName" + i).val() + ">" + "的物料已入库完毕，无须再次提交");
                        return false;
                    }
                    if (actNum.val()*1 > num) {
                        alert("名称为" + "<" + $("#productName" + i).val() + ">" + "的物料的实收数量不能超过" + num);
                        actNum.focus();
                        return false;
                    }
                }
            }
        }
    }
    return true;
}

/**
 * 校验即时库存
 */
function checkProNumIntime() {
	var checkResult = true;
	var itemscount = $("#itemscount").val();

	if ($("#isRed").val() == "1") {
		for (var i = 0; i < itemscount; i++) {
			if ($("#productId" + i).val() != ""
				&& $("#inStockId" + i).val() != ""
				&& parseInt($("#inStockId" + i).val()) != "0"
				&& $("#isDelTag" + i).val() != 1) {
				$.ajax({// 获取对应库存信息
					type: "POST",
					dataType: "json",
					async: false,
					url: "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
					data: {
						"productId": $("#productId" + i).val(),
						"stockId": $("#inStockId" + i).val()
					},
					success: function(result) {
						if (isNum($("#actNum" + i).val())) {
							if (result != "0") {
								if (result['exeNum'] < parseInt($("#actNum" + i)
										.val())) {
									alert("库存不足! "
									+ $("#inStockName" + i).val()
									+ "中编号为\""
									+ $("#productCode" + i).val()
									+ "\"的物料可执行数量是" + result['exeNum']);
									checkResult = false;
								}

							} else {
								alert("库存不足!" + $("#inStockName" + i).val()
								+ "中不存在编号为\""
								+ $("#productCode" + i).val() + "\"的物料");
								checkResult = false;
							}
						} else {
							alert("实收数量填写有误!");
							checkResult = false;
						}
					}
				});
				if (!checkResult) {
					return checkResult;
				}
			}

		}
	}
	return true;
}
/**
 * 查看合同审批情况
 */
function viewContracAudit() {
	if ($("#contractId").val() == "") {
		alert("请先选择需要查看的合同");
	} else {
		if ($("#contractType").val() == "oa_sale_service") {
			showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

		} else if ($("#contractType").val() == "oa_sale_lease") {
			showThickboxWin('controller/contract/rental/readview.php?itemtype=oa_sale_lease&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

		} else if ($("#contractType").val() == "oa_sale_rdproject") {
			showThickboxWin('controller/rdproject/yxrdproject/readview.php?itemtype=oa_sale_rdproject&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

		} else {// ($("#contractType").val() == "oa_sale_order") 销售合同
			showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

		}
	}
}

/**
 * 解释物料库存情况
 * @param mycount
 */
function exploreProTipInfo(mycount) {
	if ($("#productId" + mycount).val() != "" && $("#productId" + mycount).val() != "0") {
		$.ajax({// 缓存序列号
			type: "POST",
			async: false,
			url: "?model=stock_inventoryinfo_inventoryinfo&action=getJsonByProductId",
			dataType: "json",
			data: {
				"productId": $("#productId" + mycount).val()
			},
			success: function(result) {
				var tipStr = "<" + $("#productName" + mycount).val() + ">  :  ";
				for (var i = 0; i < result.length; i++) {
					tipStr += result[i]['stockName'] + "(" + result[i]['actNum'] + ")   ";
				}
				$("#proTipInfo").text(tipStr);
			}
		})
	}
}
