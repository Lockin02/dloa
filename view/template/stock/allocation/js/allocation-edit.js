function viewInTime() {
	showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
}
function confirmAudit() {// 审核确认
	var auditDate = $("#auditDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
			$("#form1")
					.attr("action",
							"?model=stock_allocation_allocation&action=edit&actType=audit");
			if (checkForm(true)) {
				if (checkProNumIntime()) {
					$("#docStatus").val("YSH");
					$("#form1").submit();
				}
			}
		}
	}
}

/**
 * 控制出库用途下拉信息
 */
function controlUseOption() {
	var toUseArr = new Array("CHUKUJY", "CHUKUSY", "CHUKUTK", "CHUKUGUIH",
			"CHUKUWX");

	$("#toUse option").each(function() {
				if (!toUseArr.in_array($(this).val())) {
					$(this).remove();
				}
			});
}

/**
 * 根据出库用途设置默认仓库
 */
function reSetStock() {
	var toUseVal = $("#toUse").val();
	var itemscount = $("#itemscount").val();

	var outStockId = "";
	var outStockCode = "";
	var outStockName = "";
	var borrowStockId="";
	var borrowStockCode="";
	var borrowStockName="";
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=stock_stockinfo_systeminfo&action=getDefaultStock",
				data : {
					id : "1"
				},
				dataType : "json",
				success : function(result) {
					if (result != 0) {
						outStockId = result['outStockId'];
						outStockCode = result['outStockCode'];
						outStockName = result['outStockName'];
						borrowStockId=result['borrowStockId'];
						borrowStockCode=result['borrowStockCode'];
						borrowStockName=result['borrowStockName'];
					} else {
						alert("默认仓库未设置,请先设置默认仓库!");
						return;
					}
				}
			})

	if (toUseVal == "CHUKUJY" || toUseVal == "CHUKUSY" || toUseVal == "CHUKUWX") {
		// 借用 试用 维修
		$("#exportStockName").val(outStockName);
		$("#exportStockCode").val(outStockCode);
		$("#exportStockId").val(outStockId);

		$("#importStockName").val(borrowStockName);
		$("#importStockCode").val(borrowStockCode);
		$("#importStockId").val(borrowStockId);
		for (var i = 0; i < itemscount; i++) {
			$("#exportStockName" + i).val(outStockName);
			$("#exportStockCode" + i).val(outStockCode);
			$("#exportStockId" + i).val(outStockId);
			$("#importStockName" + i).val(borrowStockName);
			$("#importStockCode" + i).val(borrowStockCode);
			$("#importStockId" + i).val(borrowStockId);
		}
	}

	if (toUseVal == "CHUKUGUIH") {// 归还
		$("#exportStockName").val(borrowStockName);
		$("#exportStockCode").val(borrowStockCode);
		$("#exportStockId").val(borrowStockId);

		$("#importStockName").val(outStockName);
		$("#importStockCode").val(outStockCode);
		$("#importStockId").val(outStockId);

		for (var i = 0; i < itemscount; i++) {
			$("#exportStockName" + i).val(borrowStockName);
			$("#exportStockCode" + i).val(borrowStockCode);
			$("#exportStockId" + i).val(borrowStockId);

			$("#importStockName" + i).val(outStockName);
			$("#importStockCode" + i).val(outStockCode);
			$("#importStockId" + i).val(outStockId);
		}
	}
	if (toUseVal == "CHUKUTK") {// 调库
		$("#exportStockName").val("");
		$("#exportStockCode").val("");
		$("#exportStockId").val("");

		$("#importStockName").val("");
		$("#importStockCode").val("");
		$("#importStockId").val("");

		for (var i = 0; i < itemscount; i++) {
			$("#exportStockName" + i).val("");
			$("#exportStockCode" + i).val("");
			$("#exportStockId" + i).val("");

			$("#importStockName" + i).val("");
			$("#importStockCode" + i).val("");
			$("#importStockId" + i).val("");
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

function subTotalPrice1(el) {// 计算物料金额_数量
	var cost = parseInt($(el).parent().next().children().eq(0).val());
	if (cost >= 0) {
		$(el).parent().next().next().children().eq(0).val($(el).val() * cost);
	}
}
function subTotalPrice2(el) {// 计算物料金额 _单位成本
	var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
	if (proNumVal >= 0) {
		$(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
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
			})
}
/**
 * 渲染物料清单收料仓库combogrid
 */
function reloadItemStock() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#exportStockName" + i).yxcombogrid_stockinfo("remove");
		$("#exportStockName" + i).yxcombogrid_stockinfo({// 调出仓库
			hiddenId : 'exportStockId' + i,
			nameCol : 'stockName',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#exportStockCode' + i).val(data.stockCode);
						}
					}(i)
				}
			}
		})
		$("#importStockName" + i).yxcombogrid_stockinfo("remove");
		$("#importStockName" + i).yxcombogrid_stockinfo({// 调入仓库
			hiddenId : 'importStockId' + i,
			nameCol : 'stockName',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#importStockCode' + i).val(data.stockCode);
						}
					}(i)
				}
			}
		})
	}
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		if ($("#relDocId" + i).val() == "" || $("#relDocId" + i).val() == "0") {
			// 绑定物料编号
			$("#productCode" + i).yxcombogrid_product("remove");
			$("#productCode" + i).yxcombogrid_product({
				hiddenId : 'productId' + i,
				nameCol : 'productCode',
				isDown : true,
				gridOptions : {
					// isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i) {
							return function(e, row, data) {
								$('#productName' + i).val(data.productName);
								$('#k3Code' + i).val(data.ext2);
								$("#pattern" + i).val(data.pattern);
								$("#unitName" + i).val(data.unitName);
							}
						}(i)
					}
				}
			})
			// 绑定物料名称
			$("#productName" + i).yxcombogrid_product("remove");
			$("#productName" + i).yxcombogrid_product({
				hiddenId : 'productId' + i,
				nameCol : 'productName',
				isDown : true,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i) {
							return function(e, row, data) {
								$('#productCode' + i).val(data.productCode);
								$('#k3Code' + i).val(data.ext2);
								$("#pattern" + i).val(data.pattern);
								$("#unitName" + i).val(data.unitName);
							}
						}(i)
					}
				}
			})
		}else{
			$("#productCode" + i).attr("readonly","readonly");
			$("#productName" + i).attr("readonly","readonly");
		}
	}
}

/**
 * 初始化物料清单表单
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#productName" + i).yxcombogrid_product("remove");
		$("#productCode" + i).yxcombogrid_product("remove");
		$("#exportStockName" + i).yxcombogrid_stockinfo("remove");
		$("#importStockName" + i).yxcombogrid_stockinfo("remove");
	}
	$("#itembody").html("");
	$("#itemscount").val(0);
	addItems();
}

/**
 * 选择序列号
 */
function chooseSerialNo(seNum) {
	var productIdVal = $("#productId" + seNum).val();
	var serialnoId = $("#serialnoId" + seNum).val();
	var serialnoName = $("#serialnoName" + seNum).val();
	var stockId = $("#exportStockId" + seNum).val();

	if (productIdVal != "") {
		showThickboxWin(
				"?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
						+ serialnoId
						+ "&serialnoName="
						+ serialnoName
						+ "&productId="
						+ productIdVal
						+ "&elNum="
						+ seNum
						+ "&stockId="
						+ stockId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600",
				"选择序列号")
	} else {
		alert("请先选择物料!");
	}
}
/**
 * 动态添加从表数据
 */
function addItems() {
	var inStockId = $("#importStockId").val();
	var inStockCode = $("#importStockCode").val();
	var inStockName = $("#importStockName").val();

	var outStockId = $("#exportStockId").val();
	var outStockCode = $("#exportStockCode").val();
	var outStockName = $("#exportStockName").val();

	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = ' <input type="text" name="allocation[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="txtshort" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="allocation[items][' + mycount
    		+ '][k3Code]" id="k3Code' + mycount + '" class="readOnlyTxtItem" readonly="readonly"/>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="txt" />';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtItem" />';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = ' <input type="text" name="allocation[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtItem" />';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][allocatNum]" id="allocatNum' + mycount
			+ '" class="txtshort" onfocus="exploreProTipInfo('+mycount+')" ondblclick="chooseSerialNo(' + mycount
			+ ')" onblur="FloatMul(\'allocatNum' + mycount + '\',\'cost'
			+ mycount + '\',\'subCost' + mycount + '\')" />';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][cost]" id="cost' + mycount
			+ '" class="txtshort formatMoneySix" onblur="FloatMul(\'cost'
			+ mycount + '\',\'allocatNum' + mycount + '\',\'subCost' + mycount
			+ '\')"  />'
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][subCost]" id="subCost' + mycount
			+ '" class="formatMoney readOnlyTxtItem" />'
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][exportStockName]" id="exportStockName' + mycount
			+ '" class="txtshort" value="' + outStockName + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][exportStockId]" id="exportStockId' + mycount + '" value="'
			+ outStockId + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][exportStockCode]" id="exportStockCode' + mycount + '" value="'
			+ outStockCode + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][relDocId]" id="relDocId' + mycount + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][relDocName]" id="relDocName' + mycount + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][relDocCode]" id="relDocCode' + mycount + '" />';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][importStockName]" id="importStockName' + mycount
			+ '" class="txtshort" value="' + inStockName + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][importStockId]" id="importStockId' + mycount + '" value="'
			+ inStockId + '" />'
			+ '<input type="hidden" name="allocation[items][' + mycount
			+ '][importStockCode]" id="importStockCode' + mycount + '" value="'
			+ inStockCode + '" />';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('
			+ mycount
			+ ');" title="选择序列号">'
			+ '<input type="hidden" name="allocation[items]['
			+ mycount
			+ '][serialnoId]" id="serialnoId'
			+ mycount
			+ '"  />'
			+ '<input type="text" name="allocation[items]['
			+ mycount
			+ '][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName'
			+ mycount + '"  />';
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][remark]" id="remark' + mycount + '" class="txtshort" />';
	var oTL14 = oTR.insertCell([14]);
	oTL14.innerHTML = '<input type="text" name="allocation[items][' + mycount
			+ '][validDate]" id="validDate' + mycount
			+ '" class="txtshort"  onfocus="WdatePicker()" />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	// 金额 千分位处理
	formateMoney();

	reloadItemStock();
	reloadItemProduct();
	reloadItemCount();

}

/** ********************删除动态表单************************ */
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="allocation[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
	}
	reloadItemCount();
	// if (confirm('确定要删除该行？')) {
	// var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
	// var mytable = document.getElementById(mytable);
	// mytable.deleteRow(rowNo);
	// var myrows = mytable.rows.length;
	// for (i = 1; i < myrows; i++) {
	// mytable.rows[i].childNodes[0].innerHTML = i;
	// }
	// }
}

function checkForm(audit) {// 表单校验
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
				if ($("#productId" + i).val() == "") {
					alert("物料信息不能为空，请选择...");
					return false;
				}
				if ($("#importStockId" + i).val() == ""
						|| parseInt($("#importStockId" + i).val()) == 0) {
					alert("调入仓库不能为空，请选择...");
					return false;
				}

				if ($("#exportStockId" + i).val() == ""
						|| parseInt($("#exportStockId" + i).val()) == 0) {
					alert("调出仓库不能为空，请选择...");
					return false;
				}
				if(audit){
					// 检查出库数量是否合法
					if (!checkRelDocNum($("#relDocType").val(), $("#relDocId")
									.val(), $("#relDocId" + i).val(),
							$("#allocatNum" + i).val(), $("#productCode" + i).val())) {
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
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() != ""
				&& $("#exportStockId" + i).val() != ""
				&& parseInt($("#exportStockId" + i).val()) != "0"
				&& $("#isDelTag" + i).val() != 1) {
			$.ajax({// 获取对应库存信息
				type : "POST",
				dataType : "json",
				async : false,
				url : "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
				data : {
					"productId" : $("#productId" + i).val(),
					"stockId" : $("#exportStockId" + i).val(),
					"objType" : $("#contractType").val(),
					"objId" : $("#contractId").val()
				},
				success : function(result) {
					if (isNum($("#allocatNum" + i).val())) {
						// alert(result)
						if (result != "0") {
							if (result['exeNum'] < parseInt($("#allocatNum" + i)
									.val())) {
								alert("库存不足! "
										+ $("#exportStockName" + i).val()
										+ "中编号为\""
										+ $("#productCode" + i).val()
										+ "\"的物料可执行数量是" + result['exeNum']);
								checkResult = false;
							}

						} else {
							alert("库存不足!" + $("#exportStockName" + i).val()
									+ "中不存在编号为\"" + $("#productCode" + i).val()
									+ "\"的物料");
							checkResult = false;
						}
					} else {
						alert("数量填写有误!");
						checkResult = false;
					}
				}
			})
			if (!checkResult) {
				return checkResult;
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
 * 源单已锁定序列号信息
 */
function relDocSerilno(win, productId, stockId, isRed) {
	if ($("#relDocType").val() == "DBDYDLXFH") {
		var relDocType = "";
		var relDocId = "";
		$.ajax({
			type : "POST",
			async : false,
			dataType : "json",
			url : "?model=stock_outplan_outplan&action=getPlanRel",
			data : {
				"planId" : $("#relDocId").val()
			},
			success : function(result) {
				if (result) {
					win.location = "?model=stock_serialno_serialno&action=toChoose&productId="
							+ productId
							+ "&stockId="
							+ stockId
							+ "&isRed="
							+ isRed
							+ "&relDocId="
							+ result['docId']
							+ "&relDocType=" + result['docType'];
				} else {
					alert("发货计划没有相关源单信息!")
				}

			}
		})

	} else {
		alert("源单类型不是发货计划");
	}

}
/**
 * 检查物料对应源单对应数量
 *
 * @param {}
 *            $relDocType
 * @param {}
 *            $relDocId
 * @param {}
 *            $relDocItemId
 * @param {}
 *            $proNum
 */
function checkRelDocNum(relDocType, relDocId, relDocItemId, proNum, producCode) {
	var checkResult = true;
	if ($("#toUse").val() == "CHUKUJY") {
		$.ajax({
			type : "POST",
			async : false,
			url : "?model=stock_allocation_allocation&action=findRelDocNotExeNum",
			data : {
				"relDocType" : relDocType,
				"relDocId" : relDocId,
				"relDocItemId" : relDocItemId
			},
			success : function(notExeNum) {
				if (parseInt(notExeNum) < parseInt(proNum)) {
					alert("物料<" + producCode + ">出库数量已大于源单的未执行数量!");
					checkResult = false;
				}
			}
		})
	}
	return checkResult;
}
/**
 * 解释物料库存情况
 *
 * @param productId
 */
function exploreProTipInfo(mycount){
	if($("#productId"+mycount).val()!=""&&$("#productId"+mycount).val()!="0"){
		$.ajax({// 缓存序列号
			type : "POST",
			async : false,
			url : "?model=stock_inventoryinfo_inventoryinfo&action=getJsonByProductId",
			dataType: "json",
			data : {
				"productId" : $("#productId"+mycount).val()
			},
			success : function(result) {
				var tipStr="<"+$("#productName"+mycount).val()+">  :  ";
				for(var i=0;i<result.length;i++){
					tipStr+=result[i]['stockName']+"("+result[i]['actNum']+")   ";
				}
				$("#proTipInfo").text(tipStr);
			}
		})
	}
}
