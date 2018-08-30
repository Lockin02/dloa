//公用初始化部分
$(function(){
	$("#tableDiv").width(document.documentElement.clientWidth - 10);

	$.post(
		"?model=finance_invpurchase_invpurchase&action=hasLimitToAudit","",
		function(result) {
			if(result == 1){
				$("#auditBtn").show();
			}
		}
	);

	if($("#TO_NAME").length != 0){
		$("#TO_NAME").yxselect_user({
			hiddenId : 'TO_ID',
			mode : 'check'
		});
	}
})

function changeTitle(thisVal){
	if(thisVal == 'red'){
		$("#formTitle").attr('style','color:red');
	}else{
		$("#formTitle").attr('style','');
	}
}

/**********************条目列表*************************/
function dynamic_add(packinglist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var packinglist = document.getElementById(packinglist);
	i=packinglist.rows.length + 1;
	oTR =packinglist.insertRow([packinglist.rows.length]);
	oTL0=oTR.insertCell([0]);
    oTL0.innerHTML="<img src='images/removeline.png' onclick='mydel(this,\""+packinglist.id+"\")' title='删除行'>";
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML=i;
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][productNo]' id='productNo"+mycount+"' class='txtmiddle'/>";
	oTL3=oTR.insertCell([3]);
	oTL3.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][productName]' id='productName"+mycount+"' class='txt'/><input type='hidden' name='invpurchase[invpurdetail]["+mycount+"][productId]' id='productId"+mycount+"'>";
	oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][productModel]' id='productModel"+mycount+"' class='readOnlyTxtItem' readonly='readonly'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][unit]' id='unit"+mycount+"' class='readOnlyTxtShort' readonly='readonly'/>";
    oTL6=oTR.insertCell([6]);
    oTL6.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][number]' id='number"+mycount+"' class='txtshort'/>";
    oTL7=oTR.insertCell([7]);
    oTL7.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][price]' id='price"+mycount+"' class='txtshort'/>";
    oTL8=oTR.insertCell([8]);
    oTL8.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][taxPrice]' id='taxPrice"+mycount+"' class='txtshort'/>";
    oTL9=oTR.insertCell([9]);
    oTL9.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][amount]' id='amount"+mycount+"' class='txtshort'/>";
    oTL10=oTR.insertCell([10]);
    oTL10.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][assessment]' id='assessment"+mycount+"' class='txtshort'/>";
    oTL11=oTR.insertCell([11]);
    oTL11.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][allCount]' id='allCount"+mycount+"' class='txtshort'/>";
	oTL12=oTR.insertCell([12]);
    oTL12.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][objCode]' id='objCode"+mycount+"' class='readOnlyTxtNormal' readonly='readonly'/><input type='hidden' name='invpurchase[invpurdetail]["+mycount+"][objId]' id='objId"+mycount+"'><input type='hidden' name='invpurchase[invpurdetail]["+mycount+"][objType]' id='objType"+mycount+"'>";
    oTL13=oTR.insertCell([13]);
    oTL13.innerHTML="<input type='text' name='invpurchase[invpurdetail]["+mycount+"][contractCode]' id='contractCode"+mycount+"' class='readOnlyTxtNormal' readonly='readonly'/><input type='hidden' name='invpurchase[invpurdetail]["+mycount+"][contractId]' id='contractId"+mycount+"'>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    createFormatOnClick('amount'+mycount);
    createFormatOnClick('price'+mycount,'number'+mycount,'price'+mycount,'amount'+mycount,6);
    createFormatOnClick('assessment'+mycount);
    createFormatOnClick('allCount'+mycount);
    createFormatOnClick('taxPrice'+mycount,'number'+mycount,'taxPrice'+mycount,'allCount'+mycount,6);

    //数量绑定统计事件
    $("#number" + mycount).bind("blur",function(){
		FloatMul('number'+mycount, 'price'+mycount, 'amount'+mycount , 2);
		countAll();
    });

    //单价绑定统计事件
    $("#price" + mycount + "_v").bind("blur",function(){
		countAll('price');
    });

    //含税单价绑定
    $("#taxPrice" + mycount + "_v").bind("blur",function(){
		countAll('taxPrice');
    });

    //含税单价绑定
    $("#amount" + mycount + "_v").bind("blur",function(){
		countAll('countForm');
    });

    //含税单价绑定
    $("#assessment" + mycount + "_v").bind("blur",function(){
		countAll('countForm');
    });

    //含税单价绑定
    $("#allCount" + mycount + "_v").bind("blur",function(){
		countAll('countForm');
    });



    $("#productNo"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unit"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });

    $("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNo"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unit"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });
}
/**********************删除动态表单*************************/
function mydel(obj,mytable){
	if(confirm('确定要删除该行？')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo - 2);
   		//重新对行号复制
   		$.each($("tbody#invbody tr td:nth-child(2)"),function(i,n){
	   		$(this).html( i + 1 );
   		});
	}
	countAll();
}


/*************************源单部分初始化******************/

//源单类型初始化
//发票初始化选择
$thisInitCode = 'menuNo';
$thisInitId = 'menuId';
$thisInitType = 'sourceType';

//初始化表格
function initGrid(thisVal){
	$("#" + $thisInitCode).yxcombogrid_purchcontract('remove');
	$("#" + $thisInitCode).yxcombogrid_stockin('remove');

	$("#" + $thisInitCode).val('');
	$("#" + $thisInitId).val('');

	initGridNoEmpty(thisVal);
}

//初始化表格,不清空
function initGridNoEmpty(thisVal){
	removeRowsNotEqu(thisVal);
	switch(thisVal){
		case 'CGFPYD-01' : initPurchcontract(thisVal);break;
		case 'CGFPYD-02' : initStockIn(thisVal);break;
		default : break;
	}
}

/**
 * 移除从表中类型不为objTypeCode的行
 * @param {objTypeCode} 类型
 */
function removeRowsNotEqu(objTypeCode){
	var tempVal = "";
	$.each($("#invbody tr"),function(i,n){
		tempVal = $(this).attr('class');
		if(tempVal != ""){
			if(tempVal != objTypeCode){
				$(this).remove();
			}
		}
	});
}

//初始化采购订单
function initPurchcontract(objTypeCode){
	$("#" + $thisInitCode).yxcombogrid_purchcontract({
		hiddenId : $thisInitId,
		nameCol : 'hwapplyNumb',
		searchName : 'hwapplyNumb',
		height : 300,
		gridOptions : {
			param : {"csuppId":$("#supplierId").val(),'ExaStatus' : '完成'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					var rowNumber = $("input[id^='productId']").length;
					var thisInvnumber = $("#invnumber").val()*1;
					for(rowNumber; rowNumber > 0;rowNumber--){
						if($("#productId" + rowNumber ).val()!= "" ){
							break;
						}
					}
					$.post(
						"?model=purchase_contract_purchasecontract&action=getDetail",
						{
							objId : data.id,
							objCode : data.hwapplyNumb,
							objType : objTypeCode,
							invnumber : thisInvnumber,
							pronumber : rowNumber
						}, function(result) {
							if (result != "") {
								$("#menuNo").val("");
								$("#menuId").val("");
								$.each($("input[id^='productId']"),function(i,n){
									if(i >= rowNumber ){
										$(this).parent().parent().remove();
									}
								});
								$("#invbody").append(result);
								var newNumber = $("input[id^='productId']").length;
								var newInvnumber = newNumber - rowNumber + thisInvnumber;
								$("#invnumber").val(newInvnumber);
								for(i = rowNumber + 1; newInvnumber >= i;i++){
									initFunction(i);
								}
								countAll('taxPrice');
							} else {
								alert("该订单单中没有物料信息，请重新选择！")
							}
						}
					);
				}
			}
		}
	});
}

//初始化外购入库单
function initStockIn(objTypeCode){
	$("#" + $thisInitCode).yxcombogrid_stockin({
		hiddenId : $thisInitId,
		height : 300,
		gridOptions : {
			param : {"supplierId":$("#supplierId").val(), 'docStatus' : 'YSH','docType' : 'RKPURCHASE'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					var rowNumber = $("input[id^='productId']").length;
					var thisInvnumber = $("#invnumber").val()*1;
					for(rowNumber; rowNumber > 0;rowNumber--){
						if($("#productId" + rowNumber ).val()!= "" ){
							break;
						}
					}
					$.post(
						"?model=stock_instock_stockin&action=showItemAtInp",
						{
							objId : data.id,
							objCode : data.docCode,
							objType : objTypeCode,
							invnumber : thisInvnumber,
							pronumber : rowNumber
						}, function(result) {
							if (result != "") {
								$("#menuNo").val("");
								$("#menuId").val("");
								$.each($("input[id^='productId']"),function(i,n){
									if(i >= rowNumber ){
										$(this).parent().parent().remove();
									}
								});
								$("#invbody").append(result);
								var newNumber = $("input[id^='productId']").length;
								var newInvnumber = newNumber - rowNumber + thisInvnumber;
								$("#invnumber").val(newInvnumber);
								for(i = rowNumber + 1; newInvnumber >= i;i++){
									initFunction(i);
								}
								countAll('taxPrice');
							} else {
								alert("该订单单中没有物料信息，请重新选择！")
							}
						}
					);
				}
			}
		}
	});
}

function initFunction(mycount){
	createFormatOnClick('amount'+mycount,'number'+mycount,'price'+mycount,'amount'+mycount);
    createFormatOnClick('price'+mycount,'number'+mycount,'price'+mycount,'amount'+mycount);
    createFormatOnClick('assessment'+mycount);
    createFormatOnClick('allCount'+mycount);
    createFormatOnClick('taxPrice'+mycount,'number'+mycount,'taxPrice'+mycount,'allCount'+mycount);

    //数量绑定统计事件
    $("#number" + mycount).bind("blur",function(){
		FloatMul('number'+mycount, 'price'+mycount, 'amount'+mycount , 2);
		countAll();
    });

    //单价绑定统计事件
    $("#price" + mycount + "_v").bind("blur",function(){
		countAll('price');
    });

    $("#productNo"+mycount).yxcombogrid_product('remove').yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unit"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });

    $("#productName"+mycount).yxcombogrid_product('remove').yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNo"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unit"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });
}

//金额计算
function countAll(thisId){
	//判断传入id,如果没有传入，则将thisId转成空字符串
	if(thisId == undefined){
		thisId = "";
	}
	//物料清单记录数
	var $invnumber = $('#invnumber').val();
	//当前行总金额
	var thisAmount = 0;
	//当前行税金
	var thisAssessment = 0;
	//当前行金额(不含税)
	var thisAllCount = 0;
	//当前含税单价
	var thisTaxPrice = 0;
	//当前单价
	var thisPrice = 0;

	//表单内总金额(不含税)
	var allAmount = 0;
	//表单含税总金额
	var allCountAll = 0;
	//表单内总税额
	var allAssessment = 0;
	//表单内总数量
	var allNumber = 0;
	//税率
	var taxRate = 0;

	if($('#taxRate').val() != "" && $('#taxRate').val() != 0){
		taxRate = accDiv($('#taxRate').val()*1 , 100,2);
	}

	for(var i = 1;i <= $invnumber;i++){
		thisAssessment = 0 ;
		thisAllCount = 0 ;
		thisAmount = 0 ;
		if($("#number" + i).length == 0){
			continue;
		}
		thisNumber = $("#number" + i).val()*1;
		if(thisNumber != 0 && thisNumber != ""){
			if(thisId == 'price'){
				//含税金额
				thisPrice = $("#price" + i).val()*1;
				thisTaxPrice = accMul(thisPrice , accAdd(1,taxRate,2) ,6);

				//处理采购发票金额 - 若小数点后6位约为999999的时候,加上0.000001
				thisTaxPriceStr = thisTaxPrice.toFixed(6);
				endNum = thisTaxPriceStr.split(".");
				if(endNum[1] == '999999'){
					thisTaxPrice = accAdd(thisTaxPrice,0.000001,6);
				}

				//设置含税单价
				setMoney('taxPrice'+ i,thisTaxPrice,6);
				//不含税合计
				thisAmount = $("#amount" + i).val()*1;
				//含税合计
				thisAllCount = accMul(thisTaxPrice,thisNumber,2);
				//价税合计
				setMoney('allCount'+ i,thisAllCount,2);

				//税额部分
				thisAssessment = accSub(thisAllCount , thisAmount ,2);
				setMoney('assessment'+ i,thisAssessment,2);

			}else if(thisId == 'taxPrice'){
				thisTaxPrice = $("#taxPrice" + i).val()*1;
				thisPrice = accDiv(thisTaxPrice , accAdd(1,taxRate,6) ,6);

				//设置单价
				setMoney('price'+ i,thisPrice,6);

				thisAmount = accMul(thisNumber,thisPrice,2);

				thisAllCount = accMul(thisNumber,thisTaxPrice,2);

				//设置金额
				setMoney('amount'+ i,thisAmount,2);

				//价税合计
				setMoney('allCount'+ i,thisAllCount,2);

				//税额部分
				thisAssessment = accSub(thisAllCount , thisAmount ,2);
				setMoney('assessment'+ i,thisAssessment,2);

			}else if(thisId == 'countForm'){//记录单据总金额，不计算单价、数量
				//根据金额和税额计算价税合计
				thisAssessment = accAdd(thisAssessment,$("#assessment" + i).val()*1,2);
				thisAmount = accAdd(thisAmount,$("#amount" + i).val()*1,2);

				thisAllCount = accAdd(thisAssessment,thisAmount,2);
				setMoney('allCount'+ i,thisAllCount,2);
			}else{
				thisTaxPrice = $("#taxPrice" + i).val()*1;
				thisPrice = $("#price" + i).val()*1;
				if(thisTaxPrice != "" && thisTaxPrice != 0){
					thisTaxPrice = accMul(thisPrice , accAdd(1,taxRate,6) ,6);

					//处理采购发票金额 - 若小数点后6位约为999999的时候,加上0.000001
					thisTaxPriceStr = thisTaxPrice.toFixed(6);
					endNum = thisTaxPriceStr.split(".");
					if(endNum[1] == '999999'){
						thisTaxPrice = accAdd(thisTaxPrice,0.000001,6);
					}

					//设置含税单价
					setMoney('taxPrice'+ i,thisTaxPrice,6);

					thisAmount = $("#amount" + i).val()*1;

					//价税合计
					thisAllCount = accMul(thisTaxPrice,thisNumber,2);
					setMoney('allCount'+ i,thisAllCount,2);

					//税额部分
					thisAssessment = accSub(thisAllCount , thisAmount ,2);
					setMoney('assessment'+ i,thisAssessment,2);

				}else if(thisPrice != "" && thisPrice != 0){
					//设置单价
					thisPrice = accDiv(thisTaxPrice , accAdd(1,taxRate,6) ,6);
					setMoney('price'+ i,thisPrice,6);

					//设置金额
					thisAmount = accMul(thisNumber,thisPrice,2);
					setMoney('amount'+ i,thisAmount,2);

					//税额部分
					thisAssessment = accSub($("#allCount"+i).val()*1,thisAmount,2);
					setMoney('assessment'+ i,thisAssessment,2);

					//价税合计
					thisAllCount = accMul(thisTaxPrice,thisNumber,2);
					setMoney('allCount'+ i,thisAllCount,2);
				}
			}
			allAssessment = accAdd(allAssessment,thisAssessment,2) ;

			allCountAll = accAdd(allCountAll,thisAllCount,2);

			allAmount = accAdd(allAmount,thisAmount,2);

			allNumber = accAdd(allNumber,thisNumber);
		}
	}
	//单据金额
	setMoney('allAmount',allAmount,2);
//	$('#allAmount').val(allAmount);
//	$('#allAmount_v').val(moneyFormat2(allAmount));


	//单据税额
	setMoney('formAssessment',allAssessment,2);
//	$('#formAssessment').val(allAssessment);
//	$('#formAssessment_v').val(moneyFormat2(allAssessment));

	//价税合计
	setMoney('formCount',allCountAll,2);
//	$('#formCount').val(allCountAll);
//	$('#formCount_v').val(moneyFormat2(allCountAll));

	//单据不含税总金额
	$('#amountAll').html(moneyFormat2(allAmount));
	//单据含税总金额
	$('#allCountAll').html(moneyFormat2(allCountAll));

	//单据税额
	$('#assessmentAll').html(moneyFormat2(allAssessment));

	//数量
	$('#numberAll').html(moneyFormat2(allNumber,2));


	//数量
	$('#formNumber').val(allNumber);
}

//初始化计算 - 用于外购入库单下推采购发票
function countInit(){
	var countNum = $("#invnumber").val()*1;
	for(var i = 1;i<= countNum ;i++){
		FloatMul('price' + i,'number' + i,'amount' + i);
	}
	countAll('print');
}

//转换税率
function changeTaxRate(thisVal,countId){
	taxRateObj = $("#taxRate");
	var taxRate = 0;
	if($("#" + thisVal).find("option:selected").attr("e1") != ""){
		taxRate = $("#" + thisVal).find("option:selected").attr("e1");
	}
	taxRateObj.val(taxRate);
	if( countId == undefined){
		countId = 'taxPrice';
	}
	countAll(countId);
}

//转换税率
function changeTaxRateClear(thisVal){
	taxRateObj = $("#taxRate");
	var taxRate = 0;
	if($("#" + thisVal).find("option:selected").attr("e1") != ""){
		taxRate = $("#" + thisVal).find("option:selected").attr("e1");
	}
	taxRateObj.val(taxRate);
}

function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_invpurchase_invpurchase&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_invpurchase_invpurchase&action=add";
	}
}

function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_invpurchase_invpurchase&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_invpurchase_invpurchase&action=edit";
	}
}

//物料数量验证
function checkProNum(thisI){
	var thisNumberObj = $("#number" + thisI);
	var thisOrgNumObj = $("#orgNumber" + thisI);
	if(thisNumberObj.val()*1 > thisOrgNumObj.val()*1){
		alert('发票数量不能大于剩余可录数量');
		thisNumberObj.val(thisOrgNumObj.val()*1)
		return false;
	}
}