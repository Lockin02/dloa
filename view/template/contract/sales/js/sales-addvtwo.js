//ajax




// 选择license
$(function() {
	$("#licenseType1").yxcombogrid_licenseType({
		hiddenId : 'licenseinput1',
		gridOptions : {
			showcheckbox : true
		}
	});
    //选择订单
	$("#temporaryNo").yxcombogrid_order({

		gridOptions : {
			showcheckbox : false,
			param : { 'ExaStatus'  : '完成'},
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#temporaryNo').val(data.orderCode);
					$('#contName').val(data.orderName);
					$('#money').val(data.orderMoney);
					$('#invoiceType').val(data.invoiceType);
					$('#deliveryDate').val(data.deliveryDate);
					$('#customerName').val(data.customerName);
					$('#customerType').val(data.customerType);
                    $('#provincecity').val(data.customerProvince);
                    $('#orderId').val(data.id);

                    //提取从表数据
					$.post("?model=projectmanagent_order_order&action=ajaxList",{
						"id" : data.id
					}, function(data) {
						var obj = eval("(" + data +")");
//						alert(data);
						//设备
						if(obj.orderequ){
							$("#myequ").html("");
							initEqu(obj.orderequ,'myequ');
//							$("#myequ").append(initEqu(obj.orderequ));
						}
						//自定义清单
						if(obj.customizelist){
						    $("#mycustom").html("");
						    custlist(obj.customizelist,'mycustom');
						}
						//开票计划
						if(obj.invoice){
						    $("#myinv").html("");
						    invlist(obj.invoice,'myinv');
						}
						//收款计划
						if(obj.receiptplan){
						    $("#mypay").html("");
						    mypaylist(obj.receiptplan,'mypay');
						}
						//培训计划
						if(obj.trainingplan){
						   $("#mytra").html("");
						   traininglist(obj.trainingplan,'mytra');
						}

					});
				}
			}
		}
	});
});

//设置设备
function initEqu(thisObj,tableId){
	var tableObj = $('#' + tableId );
	var objLength = thisObj.length;
	var inStr = "";
	var j = 0;
	for(var i = 1;i<=objLength ;i++ ){
		inStr ="<tr><td>"+ i + "</td>" +
					"<td><select class='txtshort'  name='sales[equipment]["+ i +"][productLine]' id='productLine"+ i +"'><option>"+thisObj[j].productLine+"</option></select></td>" +
					"<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][productNumber]' id='EquId" + i + "' value='"+ thisObj[j].productNo + "'/></td>"+
					"<td><input class='txtmiddle' type='text' name='sales[equipment][" + i + "][productName]' id='EquName"+ i + "' value='" + thisObj[j].productName + "'>" +
					    "<input class='txtmiddle' type='hidden' name='sales[equipment][" + i + "][productId]' id='productId"+ i + "' value='" + thisObj[j].productId + "'></td>"+
					"<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][productModel]' id='EquModel"+ i + "' value='" + thisObj[j].productModel + "'></td>"+
				    "<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][amount]' id='EquAmount"+ i + "' value='" + thisObj[j].number + "'></td>"+
					"<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][price]' id='EquPrice"+ i + "' value='" + thisObj[j].price + "'></td>"+
				    "<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][countMoney]' id='EquAllMoney"+ i + "' value='" + thisObj[j].money + "'></td>"+
					"<td><input class='txtshort' type='text' name='sales[equipment][" + i + "][projArraDate]' id='EquDeliveryDT"+ i + "' value='" + thisObj[j].projArraDate + "' onfocus='WdatePicker()'></td>"+
				    "<td><select class='txtshort'  name='sales[equipment]["+ i +"][warrantyPeriod]' id='warrantyPeriod"+ i +"'>" +
				    		"<option value='半年'>半年</option>" +
				    		"<option value='一年'>一年</option>" +
				    		"<option value='两年'>两年</option>" +
				    		"<option value='三年'>三年</option></select></td>" +
					"<td><input type='checkbox'  name='sales[equipment]["+ i +"][isSell]' checked='checked'></td>" +
					"<td><img src='images/closeDiv.gif' onclick='equdel(this,\"" + tableId + "\")' title='删除行'></td>"
				    "</tr>";
		//插入元素
		tableObj.append(inStr);
		addDataToSelect(productLineArr, 'productLine' + i);
		$('#productLine' +i).val(thisObj[j].productLine);
		j = i ;
	}
	$('#EquNum').val(j);
//	return inStr;
}
//设置自定义清单
function custlist(thisObj,tableId){
	var tableObj = $('#' + tableId );
	var objLength = thisObj.length;
	var inStr = "";
	var j = 0;
	for(var i = 1;i<=objLength ;i++ ){
		inStr ="<tr><td>"+ i + "</td>" +
					"<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][productLine]' id='cproductLine" + i+ "' value='" + thisObj[j].productLine + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][productnumber]' id='PequID" + i+ "' value='" + thisObj[j].productCode + "'/></td>" +
					"<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][name]' id='PequName" + i+ "' value='" + thisObj[j].productName + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][prodectmodel]' id='PreModel" + i+ "' value='" + thisObj[j].productModel + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][amount]' id='PreAmount" + i+ "' value='" + thisObj[j].amount + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][price]' id='PrePrice" + i+ "' value='" + thisObj[j].price + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][countMoney]' id='CountMoney" + i+ "' value='" + thisObj[j].money + "'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[customizelist][" + i+ "][projArraDT]' id='PreDeliveryDT" + i+ "' value='" + thisObj[j].projArraDT + "'/></td>" +
                    "<td><input class='txt' type='text' name='sales[customizelist][" + i+ "][remark]' id='PRemark" + i+ "' value='" + thisObj[j].remark + "'/></td>" +
                    "<td><input type='checkbox'  name='sales[equipment]["+ i +"][isSell]' checked='checked'></td>" +
					"<td><img src='images/closeDiv.gif' onclick='equdel(this,\"" + tableId + "\")' title='删除行'></td>"
				    "</tr>";
		//插入元素
		tableObj.append(inStr);
        j = i ;
	}
	$('#PreNum').val(j);

}
//设置开票计划
function invlist(thisObj,tableId){
	var tableObj = $('#' + tableId );
	var objLength = thisObj.length;
	var inStr = "";
	var j = 0;
	for(var i = 1;i<=objLength ;i++ ){
		inStr ="<tr><td>"+ i + "</td>" +
					"<td><input class='txtshort' type='text' name='sales[invoice][" + i + "][money]' id='InvMoney" + i + "' value='" + thisObj[j].money +"'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[invoice][" + i + "][softM]' id='InvSoftM" + i + "' value='" + thisObj[j].softM +"'/></td>" +
                    "<td><select class='txtmiddle' name='sales[invoice][" + i + "][iType]' id='invoiceListType"+ i +"'></select></td>" +
                    "<td><input class='txtshort' type='text' name='sales[invoice][" + i + "][invDT]' id='InvDT" + i + "' value='" + thisObj[j].invDT +"'/></td>" +
                    "<td><input class='txtlong' type='text' name='sales[invoice][" + i + "][remark]' id='InvRemark" + i + "' value='" + thisObj[j].remark +"'/></td>" +
					"<td><img src='images/closeDiv.gif' onclick='equdel(this,\"" + tableId + "\")' title='删除行'></td>"
				    "</tr>";
		//插入元素
		tableObj.append(inStr);

        addDataToSelect(invoiceTypeArr, 'invoiceListType' + i);
        $('#invoiceListType' + i ).val(thisObj[j].iType);
        j = i ;
	}
	$('#InvNum').val(j);

}
//设置收款计划
function mypaylist(thisObj,tableId){
	var tableObj = $('#' + tableId );
	var objLength = thisObj.length;
	var inStr = "";
	var j = 0;
	for(var i = 1;i<=objLength ;i++ ){
		inStr ="<tr><td>"+ i + "</td>" +
					"<td><input class='txtshort' type='text' name='sales[receiptplan][" + i + "][money]' id='PayMoney" + i + "' value='" + thisObj[j].money +"'/></td>" +
                    "<td><input class='txtshort' type='text' name='sales[receiptplan][" + i + "][payDT]' id='PayDT" + i + "' value='" + thisObj[j].payDT +"'/></td>" +
					"<td><select class='txtshort' name='sales[receiptplan][" + i + "][pType]' id='pType"+i+"'>" +

							"<option value='电汇'>电汇</option>" +
							"<option value='现金'>现金</option>" +
							"<option value='银行汇票'>银行汇票</option>" +
							"<option value='商业汇票'>商业汇票</option></select></td>"+
                    "<td><input class='txtlong' type='text' name='sales[receiptplan][" + i + "][collectionTerms]' id='collectionTerms" + i + "' value='" + thisObj[j].collectionTerms +"'/></td>" +
					"<td><img src='images/closeDiv.gif' onclick='equdel(this,\"" + tableId + "\")' title='删除行'></td>"
				    "</tr>";
		//插入元素
		tableObj.append(inStr);
		$("#pType" + i).val(thisObj[j].pType)
        j = i ;
	}
	$('#PayNum').val(j);

}
//设置培训计划
function traininglist(thisObj,tableId){
	var tableObj = $('#' + tableId );
	var objLength = thisObj.length;
	var inStr = "";
	var j = 0;
	for(var i = 1;i<=objLength ;i++ ){
		inStr ="<tr><td>"+ i + "</td>" +
					"<td><input class='txtshort' type='text' name='sales[trainingplan][" + i + "][beginDT]' id='TraDT" + i + "' onfocus='WdatePicker()' value='" + thisObj[j].beginDT +"'></td>" +
                    "<td><input class='txtshort' type='text' name='sales[trainingplan][" + i + "][endDT]' id='TraEndDT" + i + "' onfocus='WdatePicker()' value='" + thisObj[j].endDT +"'></td>" +
					"<td><input class='txtshort' type='text' name='sales[trainingplan][" + i + "][traNum]' value='" + thisObj[j].traNum +"'/></td>"+
                    "<td><textarea name='sales[trainingplan][" + i + "][adress]' rows='3' cols='15' style='width: 100%'>"+thisObj[j].adress+"</textarea></td>"+
                    "<td><textarea name='sales[trainingplan][" + i + "][content]' rows='3' cols='15' style='width: 100%'>"+thisObj[j].content+"</textarea></td>"+
                    "<td><textarea name='sales[trainingplan][" + i + "][trainerDemand]' rows='3' cols='15' style='width: 100%'>"+thisObj[j].trainer+"</textarea></td>"+
                    "<td><img src='images/closeDiv.gif' onclick='equdel(this,\"" + tableId + "\")' title='删除行'></td>"
				    "</tr>";
		//插入元素
		tableObj.append(inStr);
        j = i ;
	}
	$('#TraNumber').val(j);

}
// 选择省份
$(function() {
	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//选择付款单位--默认为客户
$(function() {

	$("#payUnit").yxcombogrid_customer({
		hiddenId : 'payUnitId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//客户与客户联系人联动
$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
//					alert(i);
					var getGrid = function() {
						return $("#linkman1")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#linkman1")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}
					$("#customerType").val(data.TypeOne);
					$("#provincecity").val(data.Prov);
					$("#customerId").val(data.id);
					$('#mylink input').val('');
				}
			}
		}
	});

	$("#linkman1").yxcombogrid_linkman({
		hiddenId : 'linkmanId1',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#telephone1").val(data.phone);
					$("#Email1").val(data.email);
				}
			}
		}
	});
//单选设备
	$("#EquName1").yxcombogrid_product({
		hiddenId : 'ProductId1',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					$("#EquId1").val(data.sequence);
					$("#EquModel1").val(data.pattern);
				}
			}
		}
	});
});

// 交货日期联动
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
		$.each($(':input[id^="EquDeliveryDT"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

	$('#invoiceType').bind('focusout', function() {
		var thisDate = $(this).val();
		$.each($(':input[id^="invoiceListType"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});
});

// 选择license类型促发
function lissel(t, count) {
	$.post(
	'index1.php?model=product_licensetype_licensetype&action=getRelateId',
	{
		id : t.value
	}, function(data) {
		$("#licenseinput" + count).val(data);
	});
}

// 金额渲染
equformalNo = true;
equtemporaryNo = true;
function CheckForm() {
	if (!equformalNo) {
		alert("鼎利合同号已存在！");
		$('#formalNo').focus();
		return (false);
	}
	if (!equtemporaryNo) {
		alert("临时合同号已存在！");
		$('#temporaryNo').focus();
		return (false);
	}
	if ($('#formalNo').val() == "" && $('#temporaryNo').val() == "") {
		alert("必须填写一个合同号！");
		$('#formalNo').focus();
		return (false);
	}
	if ($('#contName').val() == "") {
		alert("合同名称需要填写！");
		$('#contName').focus();
		return (false);
	}
	if ($('#money').val() == "") {
		alert("合同金额需要填写！");
		$('#money').focus();
		return (false);
	}
	if ($('#principalName').val() == "") {
		alert("合同负责人需要填写！");
		$('#principalName').focus();
		return (false);
	}
	if ($('#deliveryDate').val() == "") {
		alert("交货日期需要填写！");
		$('#deliveryDate').focus();
		return (false);
	}
	if ($('#customerName').val() == "") {
		alert("客户名称需要填写！");
		$('#customerName').focus();
		return (false);
	}
	isCorret = checkMoney($("#money").val());
	if (!isCorret) {
		alert("输入金额有误");
		$("#money").focus();
		return false;
	}

	deliveryDate = $('#deliveryDate').val();

	equNumber = $('#EquNum').val();
	for (i = 1; i <= equNumber; ++i) {
		if (!$('#EquId' + i)[0])
			continue;
		else {
			equAmount = $('#EquAmount' + i).val();
			if (equAmount != "") {
				if (parseInt(equAmount) != equAmount) {
					alert('请输入正确的数量');
					$('#EquAmount' + i).focus();
					return false;
				}
			}
			equPrice = $('#EquPrice' + i).val();
			if (equPrice != "") {
				if (!checkMoney(equPrice)) {
					alert('请输入正确的单价');
					$('#EquPrice' + i).focus();
					return false;
				}
			}
			equAllMoney = $('#EquAllMoney' + i).val();
			if (equAllMoney != "") {
				if (!checkMoney(equAllMoney)) {
					alert('请输入正确的单价');
					$('#EquAllMoney' + i).focus();
					return false;
				}
			}
		}
	}

	licenseNumber = $('#licenseNum').val();
	for (i = 1; i <= licenseNumber; i++) {
		if (!$('#softdogAmount' + i)[0])
			continue;
		else {
			softdogAmount = $('#softdogAmount' + i).val();
			if (softdogAmount != "") {
				if (parseInt(softdogAmount) != softdogAmount) {
					alert('请输入正确的数量');
					$('#softdogAmount' + i).focus();
					return false;
				}
			}
		}
	}

	preNum = $('#PreNum').val();
	for (i = 1; i <= preNum; i++) {
		if (!$('#PequID' + i)[0])
			continue;
		else {
			preAmount = $('#PreAmount' + i).val();
			if (preAmount != "") {
				if (parseInt(preAmount) != preAmount) {
					alert('请输入正确的数量');
					$('#PreAmount' + i).focus();
					return false;
				}
			}
			prePrice = $('#PrePrice' + i).val();
			if (prePrice != "") {
				if (!checkMoney(prePrice)) {
					alert('请输入正确的单价');
					$('#PrePrice' + i).focus();
					return false;
				}
			}
			countMoney = $('#CountMoney' + i).val();
			if (countMoney != "") {
				if (!checkMoney(countMoney)) {
					alert('请输入正确的单价');
					$('#CountMoney' + i).focus();
					return false;
				}
			}
			preDeliveryDT = $('#PreDeliveryDT' + i).val();
			if ($('#PequName' + i).val() != "" && preDeliveryDT == "") {
				$('#PreDeliveryDT' + i).val(deliveryDate);
			}
		}
	}

	payNumber = $('#PayNum').val();
	for (i = 1; i <= payNumber; i++) {
		if (!$('#PayMoney' + i)[0])
			continue;
		else {
			payMoney = $('#PayMoney' + i).val();
			if (payMoney != "") {
				if (!checkMoney(payMoney)) {
					alert('请输入正确的收款金额');
					$('#PayMoney' + i).focus();
					return false;
				}
			}
			payDT = $('#PayDT' + i).val();
			if ($('#PayMoney' + i).val() != "" && payDT == "") {
				$('#PayDT' + i).val(deliveryDate);
			}
		}
	}

	return true;
}

function check_code(code) {
	if (code != '') {
		$.get('index1.php', {
			model : 'contract_sales_sales',
			action : 'checkRepeat',
			equcontNumber : code
		}, function(data) {
			if (data != '') {
				$('#_contNumber').html('已存在的合同号！');
				equformalNo = false;
			} else {
				$('#_contNumber').html('合同号可用！');
				equformalNo = true;
			}
		})
	}
	return false;
}

function check_codeT(code) {
	if (code != '') {
		var rand = Math.random() * 100000;
		$.get('index1.php', {
			model : 'contract_sales_sales',
			action : 'checkRepeat',
			equtemporaryNo : code,
			rand : rand
		}, function(data) {
			if (data != '') {
				$('#_temporaryNo').html('已存在的合同号！');
				equtemporaryNo = false;
			} else {
				$('#_temporaryNo').html('合同号可用！');
				equtemporaryNo = true;
			}
		})
	}
	return false;
}

function saveContract() {
	document.getElementById('form1').action = "index1.php?model=contract_sales_sales&action=add&act=save";
}

function openDia(count) {
	var licensetypeid = "licenseinput" + count;
	var licensetype = $("#" + licensetypeid).val();
	if (!licensetype) {
		alert("请先选择license类型！");
	} else {
		var hideid = "licenseNodeId" + count;
		var textid = "licenseNodeName" + count;

		var hidevalue = $("#" + hideid).val();

		// var thisurl =
		// '?model=product_licensecheck_licensecheck&action=setInHtml&contractLicenseId=-1&hidevalue='+hidevalue+'&hideid='+hideid+'&textid='+textid;
		var thisurl = '?model=product_licensetype_licensetype&action=viewPages&ids='
				+ licensetype
				+ '&hidevalue='
				+ hidevalue
				+ '&hideid='
				+ hideid
				+ '&textid=' + textid;
		window.open(thisurl);
	}
}

var uploadfile;
$(function() {
	uploadfile = createSWFUpload({
		"serviceType" : "oa_contract_sales"
	});
});