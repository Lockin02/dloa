//���ó�ʼ������
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

/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var packinglist = document.getElementById(packinglist);
	i=packinglist.rows.length + 1;
	oTR =packinglist.insertRow([packinglist.rows.length]);
	oTL0=oTR.insertCell([0]);
    oTL0.innerHTML="<img src='images/removeline.png' onclick='mydel(this,\""+packinglist.id+"\")' title='ɾ����'>";
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

    //������ͳ���¼�
    $("#number" + mycount).bind("blur",function(){
		FloatMul('number'+mycount, 'price'+mycount, 'amount'+mycount , 2);
		countAll();
    });

    //���۰�ͳ���¼�
    $("#price" + mycount + "_v").bind("blur",function(){
		countAll('price');
    });

    //��˰���۰�
    $("#taxPrice" + mycount + "_v").bind("blur",function(){
		countAll('taxPrice');
    });

    //��˰���۰�
    $("#amount" + mycount + "_v").bind("blur",function(){
		countAll('countForm');
    });

    //��˰���۰�
    $("#assessment" + mycount + "_v").bind("blur",function(){
		countAll('countForm');
    });

    //��˰���۰�
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
/**********************ɾ����̬��*************************/
function mydel(obj,mytable){
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo - 2);
   		//���¶��кŸ���
   		$.each($("tbody#invbody tr td:nth-child(2)"),function(i,n){
	   		$(this).html( i + 1 );
   		});
	}
	countAll();
}


/*************************Դ�����ֳ�ʼ��******************/

//Դ�����ͳ�ʼ��
//��Ʊ��ʼ��ѡ��
$thisInitCode = 'menuNo';
$thisInitId = 'menuId';
$thisInitType = 'sourceType';

//��ʼ�����
function initGrid(thisVal){
	$("#" + $thisInitCode).yxcombogrid_purchcontract('remove');
	$("#" + $thisInitCode).yxcombogrid_stockin('remove');

	$("#" + $thisInitCode).val('');
	$("#" + $thisInitId).val('');

	initGridNoEmpty(thisVal);
}

//��ʼ�����,�����
function initGridNoEmpty(thisVal){
	removeRowsNotEqu(thisVal);
	switch(thisVal){
		case 'CGFPYD-01' : initPurchcontract(thisVal);break;
		case 'CGFPYD-02' : initStockIn(thisVal);break;
		default : break;
	}
}

/**
 * �Ƴ��ӱ������Ͳ�ΪobjTypeCode����
 * @param {objTypeCode} ����
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

//��ʼ���ɹ�����
function initPurchcontract(objTypeCode){
	$("#" + $thisInitCode).yxcombogrid_purchcontract({
		hiddenId : $thisInitId,
		nameCol : 'hwapplyNumb',
		searchName : 'hwapplyNumb',
		height : 300,
		gridOptions : {
			param : {"csuppId":$("#supplierId").val(),'ExaStatus' : '���'},
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
								alert("�ö�������û��������Ϣ��������ѡ��")
							}
						}
					);
				}
			}
		}
	});
}

//��ʼ���⹺��ⵥ
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
								alert("�ö�������û��������Ϣ��������ѡ��")
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

    //������ͳ���¼�
    $("#number" + mycount).bind("blur",function(){
		FloatMul('number'+mycount, 'price'+mycount, 'amount'+mycount , 2);
		countAll();
    });

    //���۰�ͳ���¼�
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

//������
function countAll(thisId){
	//�жϴ���id,���û�д��룬��thisIdת�ɿ��ַ���
	if(thisId == undefined){
		thisId = "";
	}
	//�����嵥��¼��
	var $invnumber = $('#invnumber').val();
	//��ǰ���ܽ��
	var thisAmount = 0;
	//��ǰ��˰��
	var thisAssessment = 0;
	//��ǰ�н��(����˰)
	var thisAllCount = 0;
	//��ǰ��˰����
	var thisTaxPrice = 0;
	//��ǰ����
	var thisPrice = 0;

	//�����ܽ��(����˰)
	var allAmount = 0;
	//����˰�ܽ��
	var allCountAll = 0;
	//������˰��
	var allAssessment = 0;
	//����������
	var allNumber = 0;
	//˰��
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
				//��˰���
				thisPrice = $("#price" + i).val()*1;
				thisTaxPrice = accMul(thisPrice , accAdd(1,taxRate,2) ,6);

				//����ɹ���Ʊ��� - ��С�����6λԼΪ999999��ʱ��,����0.000001
				thisTaxPriceStr = thisTaxPrice.toFixed(6);
				endNum = thisTaxPriceStr.split(".");
				if(endNum[1] == '999999'){
					thisTaxPrice = accAdd(thisTaxPrice,0.000001,6);
				}

				//���ú�˰����
				setMoney('taxPrice'+ i,thisTaxPrice,6);
				//����˰�ϼ�
				thisAmount = $("#amount" + i).val()*1;
				//��˰�ϼ�
				thisAllCount = accMul(thisTaxPrice,thisNumber,2);
				//��˰�ϼ�
				setMoney('allCount'+ i,thisAllCount,2);

				//˰���
				thisAssessment = accSub(thisAllCount , thisAmount ,2);
				setMoney('assessment'+ i,thisAssessment,2);

			}else if(thisId == 'taxPrice'){
				thisTaxPrice = $("#taxPrice" + i).val()*1;
				thisPrice = accDiv(thisTaxPrice , accAdd(1,taxRate,6) ,6);

				//���õ���
				setMoney('price'+ i,thisPrice,6);

				thisAmount = accMul(thisNumber,thisPrice,2);

				thisAllCount = accMul(thisNumber,thisTaxPrice,2);

				//���ý��
				setMoney('amount'+ i,thisAmount,2);

				//��˰�ϼ�
				setMoney('allCount'+ i,thisAllCount,2);

				//˰���
				thisAssessment = accSub(thisAllCount , thisAmount ,2);
				setMoney('assessment'+ i,thisAssessment,2);

			}else if(thisId == 'countForm'){//��¼�����ܽ������㵥�ۡ�����
				//���ݽ���˰������˰�ϼ�
				thisAssessment = accAdd(thisAssessment,$("#assessment" + i).val()*1,2);
				thisAmount = accAdd(thisAmount,$("#amount" + i).val()*1,2);

				thisAllCount = accAdd(thisAssessment,thisAmount,2);
				setMoney('allCount'+ i,thisAllCount,2);
			}else{
				thisTaxPrice = $("#taxPrice" + i).val()*1;
				thisPrice = $("#price" + i).val()*1;
				if(thisTaxPrice != "" && thisTaxPrice != 0){
					thisTaxPrice = accMul(thisPrice , accAdd(1,taxRate,6) ,6);

					//����ɹ���Ʊ��� - ��С�����6λԼΪ999999��ʱ��,����0.000001
					thisTaxPriceStr = thisTaxPrice.toFixed(6);
					endNum = thisTaxPriceStr.split(".");
					if(endNum[1] == '999999'){
						thisTaxPrice = accAdd(thisTaxPrice,0.000001,6);
					}

					//���ú�˰����
					setMoney('taxPrice'+ i,thisTaxPrice,6);

					thisAmount = $("#amount" + i).val()*1;

					//��˰�ϼ�
					thisAllCount = accMul(thisTaxPrice,thisNumber,2);
					setMoney('allCount'+ i,thisAllCount,2);

					//˰���
					thisAssessment = accSub(thisAllCount , thisAmount ,2);
					setMoney('assessment'+ i,thisAssessment,2);

				}else if(thisPrice != "" && thisPrice != 0){
					//���õ���
					thisPrice = accDiv(thisTaxPrice , accAdd(1,taxRate,6) ,6);
					setMoney('price'+ i,thisPrice,6);

					//���ý��
					thisAmount = accMul(thisNumber,thisPrice,2);
					setMoney('amount'+ i,thisAmount,2);

					//˰���
					thisAssessment = accSub($("#allCount"+i).val()*1,thisAmount,2);
					setMoney('assessment'+ i,thisAssessment,2);

					//��˰�ϼ�
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
	//���ݽ��
	setMoney('allAmount',allAmount,2);
//	$('#allAmount').val(allAmount);
//	$('#allAmount_v').val(moneyFormat2(allAmount));


	//����˰��
	setMoney('formAssessment',allAssessment,2);
//	$('#formAssessment').val(allAssessment);
//	$('#formAssessment_v').val(moneyFormat2(allAssessment));

	//��˰�ϼ�
	setMoney('formCount',allCountAll,2);
//	$('#formCount').val(allCountAll);
//	$('#formCount_v').val(moneyFormat2(allCountAll));

	//���ݲ���˰�ܽ��
	$('#amountAll').html(moneyFormat2(allAmount));
	//���ݺ�˰�ܽ��
	$('#allCountAll').html(moneyFormat2(allCountAll));

	//����˰��
	$('#assessmentAll').html(moneyFormat2(allAssessment));

	//����
	$('#numberAll').html(moneyFormat2(allNumber,2));


	//����
	$('#formNumber').val(allNumber);
}

//��ʼ������ - �����⹺��ⵥ���Ʋɹ���Ʊ
function countInit(){
	var countNum = $("#invnumber").val()*1;
	for(var i = 1;i<= countNum ;i++){
		FloatMul('price' + i,'number' + i,'amount' + i);
	}
	countAll('print');
}

//ת��˰��
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

//ת��˰��
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

//����������֤
function checkProNum(thisI){
	var thisNumberObj = $("#number" + thisI);
	var thisOrgNumObj = $("#orgNumber" + thisI);
	if(thisNumberObj.val()*1 > thisOrgNumObj.val()*1){
		alert('��Ʊ�������ܴ���ʣ���¼����');
		thisNumberObj.val(thisOrgNumObj.val()*1)
		return false;
	}
}