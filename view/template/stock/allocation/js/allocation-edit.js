function viewInTime() {
	showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
}
function confirmAudit() {// ���ȷ��
	var auditDate = $("#auditDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
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
 * ���Ƴ�����;������Ϣ
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
 * ���ݳ�����;����Ĭ�ϲֿ�
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
						alert("Ĭ�ϲֿ�δ����,��������Ĭ�ϲֿ�!");
						return;
					}
				}
			})

	if (toUseVal == "CHUKUJY" || toUseVal == "CHUKUSY" || toUseVal == "CHUKUWX") {
		// ���� ���� ά��
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

	if (toUseVal == "CHUKUGUIH") {// �黹
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
	if (toUseVal == "CHUKUTK") {// ����
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

function couldAudit(auditDate) {// �����Ƿ��ѹ���,�������ڲ��������Ƿ��ѽ����ж�
	var resultTrue = true;
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=finance_period_period&action=isClosed",
				data : {},
				success : function(result) {
					if (result == 1) {
						alert("�����ѹ��ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
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
						alert("�������ڲ��������Ѿ����ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
						resultTrue = false;
					}
				}
			})
	return resultTrue;
}

function subTotalPrice1(el) {// �������Ͻ��_����
	var cost = parseInt($(el).parent().next().children().eq(0).val());
	if (cost >= 0) {
		$(el).parent().next().next().children().eq(0).val($(el).val() * cost);
	}
}
function subTotalPrice2(el) {// �������Ͻ�� _��λ�ɱ�
	var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
	if (proNumVal >= 0) {
		$(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
	}

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
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		$("#exportStockName" + i).yxcombogrid_stockinfo("remove");
		$("#exportStockName" + i).yxcombogrid_stockinfo({// �����ֿ�
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
		$("#importStockName" + i).yxcombogrid_stockinfo({// ����ֿ�
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
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemProduct() {
	var itemscount = $('#itemscount').val();
	for (var i = 0; i < itemscount; i++) {
		if ($("#relDocId" + i).val() == "" || $("#relDocId" + i).val() == "0") {
			// �����ϱ��
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
			// ����������
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
 * ��ʼ�������嵥��
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
 * ѡ�����к�
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
				"ѡ�����к�")
	} else {
		alert("����ѡ������!");
	}
}
/**
 * ��̬��Ӵӱ�����
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
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����">';
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
			+ ');" title="ѡ�����к�">'
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

	// ��� ǧ��λ����
	formateMoney();

	reloadItemStock();
	reloadItemProduct();
	reloadItemCount();

}

/** ********************ɾ����̬��************************ */
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="allocation[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
	}
	reloadItemCount();
	// if (confirm('ȷ��Ҫɾ�����У�')) {
	// var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
	// var mytable = document.getElementById(mytable);
	// mytable.deleteRow(rowNo);
	// var myrows = mytable.rows.length;
	// for (i = 1; i < myrows; i++) {
	// mytable.rows[i].childNodes[0].innerHTML = i;
	// }
	// }
}

function checkForm(audit) {// ��У��
	var itemscount = $("#itemscount").val();
	var deleteCount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() == 1) {
			deleteCount = deleteCount + 1;
		}

	}
	if (deleteCount == itemscount) {
		alert("������������Ϣ...");
		return false;
	}
	if (itemscount < 1) {
		alert("��ѡ��������Ϣ...");
		return false;
	} else {
		for (var i = 0; i < itemscount; i++) {
			if ($("#isDelTag" + i).val() != 1) {
				if ($("#productId" + i).val() == "") {
					alert("������Ϣ����Ϊ�գ���ѡ��...");
					return false;
				}
				if ($("#importStockId" + i).val() == ""
						|| parseInt($("#importStockId" + i).val()) == 0) {
					alert("����ֿⲻ��Ϊ�գ���ѡ��...");
					return false;
				}

				if ($("#exportStockId" + i).val() == ""
						|| parseInt($("#exportStockId" + i).val()) == 0) {
					alert("�����ֿⲻ��Ϊ�գ���ѡ��...");
					return false;
				}
				if(audit){
					// �����������Ƿ�Ϸ�
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
 * У�鼴ʱ���
 */
function checkProNumIntime() {
	var checkResult = true;
	var itemscount = $("#itemscount").val();
	for (var i = 0; i < itemscount; i++) {
		if ($("#productId" + i).val() != ""
				&& $("#exportStockId" + i).val() != ""
				&& parseInt($("#exportStockId" + i).val()) != "0"
				&& $("#isDelTag" + i).val() != 1) {
			$.ajax({// ��ȡ��Ӧ�����Ϣ
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
								alert("��治��! "
										+ $("#exportStockName" + i).val()
										+ "�б��Ϊ\""
										+ $("#productCode" + i).val()
										+ "\"�����Ͽ�ִ��������" + result['exeNum']);
								checkResult = false;
							}

						} else {
							alert("��治��!" + $("#exportStockName" + i).val()
									+ "�в����ڱ��Ϊ\"" + $("#productCode" + i).val()
									+ "\"������");
							checkResult = false;
						}
					} else {
						alert("������д����!");
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
 * �鿴��ͬ�������
 */
function viewContracAudit() {
	if ($("#contractId").val() == "") {
		alert("����ѡ����Ҫ�鿴�ĺ�ͬ");
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

		} else {// ($("#contractType").val() == "oa_sale_order") ���ۺ�ͬ
			showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
					+ $("#contractId").val()
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

		}
	}
}
/**
 * Դ�����������к���Ϣ
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
					alert("�����ƻ�û�����Դ����Ϣ!")
				}

			}
		})

	} else {
		alert("Դ�����Ͳ��Ƿ����ƻ�");
	}

}
/**
 * ������϶�ӦԴ����Ӧ����
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
					alert("����<" + producCode + ">���������Ѵ���Դ����δִ������!");
					checkResult = false;
				}
			}
		})
	}
	return checkResult;
}
/**
 * �������Ͽ�����
 *
 * @param productId
 */
function exploreProTipInfo(mycount){
	if($("#productId"+mycount).val()!=""&&$("#productId"+mycount).val()!="0"){
		$.ajax({// �������к�
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
