//��Ʊ��ʼ��ѡ��
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitName = 'objName';
$thisInitType = 'objType';

/*
 * ��������ֵ����ݵ�����ѡ��
 */
function addDataToSelect1(data, selectId) {
    var str = "";
    for (var i = 0, l = data.length; i < l; i++) {
        str += "<option title='" + data[i].text
            + "' value='" + data[i].text + "'>" + data[i].text
            + "</option>";
    }
    $("#" + selectId).append(str);
}

//��ʼ�����
function initGrid(thisVal){
	//����һֻ
	thisObj = $("#" + $thisInitCode);

	thisObj.yxcombogrid_allcontract('remove');

	thisObj.val('');
	$("#" + $thisInitName).val('');
	$("#" + $thisInitId).val('');
    $("#" + $thisInitType).val('');
    $("#rObjCode").val('');

	initGridNoEmpty(thisVal);
}

//��ʼ�����,�����
function initGridNoEmpty(thisVal){
	switch(thisVal){
		case 'KPRK-12' : initContract();break;
		default : break;
	}
}

//��ʼ���������ͱ��
function initContract(){
	$("#" + $thisInitCode).yxcombogrid_allcontract({
		hiddenId : $thisInitId,
		height : 250,
		width : 550,
		nameCol : 'contractCode',
		searchName : 'contractCode',
		gridOptions : {
			param : {"customerId":$("#contractUnitId").val()  , 'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + $thisInitType).val('KPRK-12');
					if(data.orderCode == ""){
						$("#" + $thisInitCode).yxcombogrid_allcontract('setText',data.oldContractCode);
					}
					$("#" + $thisInitId).val(data.id);
					$("#rObjCode").val(data.objCode);

					$("#managerId").val(data.areaPrincipalId);
					$("#managerName").val(data.areaPrincipal);
					//������˾����
					$("#businessBelongName").val(data.businessBelongName);
					$("#businessBelong").val(data.businessBelong);

                    // ��Ʊ���ֲ��ִ���
                    $("#currency").val(data.currency);
                    $("#rate").val(data.rate);
                    setCurrencyShowTips();
				}
			}
		},
        event: {
            clear: function () {
                $("#" + $thisInitType).val('');
                $("#rObjCode").val('');
            }
        }
	});
}

/****************************���㷽��***************************/
//ͳ�ƽ��
function countDetail(){
	var detailRows = $('#invnumber').val();
	var amount = 0;
	var allMoney = 0;
	var softMoney=0;
	var hardMoney=0;
	var repairMoney=0;
	var serviceMoney=0;
	var equRentalMoney=0;
	var spaceRentalMoney=0;
	var otherMoney=0;
	var dsEnergyCharge = 0;
	var dsWaterRateMoney = 0;
	var houseRentalFee = 0;
	var installationCost = 0;
	for(var i = 1;i <= detailRows ;i++){
		if($('#softMoney'+i).length != 0){

			var thisAmount = $('#amount'+i).val();
			var thisSoftMoney = $('#softMoney'+i).val();
			var thisHardMoney = $('#hardMoney'+i).val();
			var thisRepairMoney = $('#repairMoney'+i).val();
			var thisServiceMoney = $('#serviceMoney'+i).val();
			var thisEquRentalMoney = $('#equRentalMoney'+i).val();
			var thisSpaceRentalMoney = $('#spaceRentalMoney'+i).val();
			var thisOtherMoney = $('#otherMoney'+i).val();
			var thisDsEnergyCharge = $('#dsEnergyCharge'+i).val();
			var thisDsWaterRateMoney = $('#dsWaterRateMoney'+i).val();
			var thisHouseRentalFee = $('#houseRentalFee'+i).val();
			var thisInstallationCost = $('#installationCost'+i).val();

			if( thisAmount != undefined &&thisAmount!= ""){
				amount = accAdd(thisAmount,amount,2);
			}
			if( thisSoftMoney != undefined && thisSoftMoney != ""){
				allMoney = accAdd(thisSoftMoney,allMoney,2);
				softMoney = accAdd(thisSoftMoney,softMoney,2);
			}
			if( thisHardMoney != undefined && thisHardMoney != ""){
				allMoney = accAdd(thisHardMoney,allMoney,2);
				hardMoney = accAdd(thisHardMoney,hardMoney,2);
			}
			if(  thisRepairMoney != undefined && thisRepairMoney != ""){
				allMoney = accAdd(thisRepairMoney,allMoney,2);
				repairMoney = accAdd(thisRepairMoney,repairMoney,2);
			}
			if(  thisServiceMoney != undefined && thisServiceMoney != ""){
				allMoney = accAdd(thisServiceMoney,allMoney,2);
				serviceMoney = accAdd(thisServiceMoney,serviceMoney,2);
			}
			if(  thisEquRentalMoney != undefined && thisEquRentalMoney != ""){
				allMoney = accAdd(thisEquRentalMoney,allMoney,2);
				equRentalMoney = accAdd(thisEquRentalMoney,equRentalMoney,2);
			}
			if(  thisSpaceRentalMoney != undefined && thisSpaceRentalMoney != ""){
				allMoney = accAdd(thisSpaceRentalMoney,allMoney,2);
				spaceRentalMoney = accAdd(thisSpaceRentalMoney,spaceRentalMoney,2);
			}
			if(  thisOtherMoney != undefined && thisOtherMoney != ""){
				allMoney = accAdd(thisOtherMoney,allMoney,2);
				otherMoney = accAdd(thisOtherMoney,otherMoney,2);
			}
			if(  thisDsEnergyCharge != undefined && thisDsEnergyCharge != ""){
				allMoney = accAdd(thisDsEnergyCharge,allMoney,2);
				dsEnergyCharge = accAdd(thisDsEnergyCharge,dsEnergyCharge,2);
			}
			if(  thisDsWaterRateMoney != undefined && thisDsWaterRateMoney != ""){
				allMoney = accAdd(thisDsWaterRateMoney,allMoney,2);
				dsWaterRateMoney = accAdd(thisDsWaterRateMoney,dsWaterRateMoney,2);
			}
			if(  thisHouseRentalFee != undefined && thisHouseRentalFee != ""){
				allMoney = accAdd(thisHouseRentalFee,allMoney,2);
				houseRentalFee = accAdd(thisHouseRentalFee,houseRentalFee,2);
			}
			if(  thisInstallationCost != undefined && thisInstallationCost != ""){
				allMoney = accAdd(thisInstallationCost,allMoney,2);
				installationCost = accAdd(thisInstallationCost,installationCost,2);
			}
		}
	}
	$('#allAmount').val(amount);

	$('#invoiceMoney').val(allMoney);
	$('#invoiceMoney_v').val(moneyFormat2(allMoney));

	$('#softMoney').val(softMoney);
	$('#softMoney_v').val(moneyFormat2(softMoney));

	$('#hardMoney').val(hardMoney);
	$('#hardMoney_v').val(moneyFormat2(hardMoney));

	$('#serviceMoney').val(serviceMoney);
	$('#serviceMoney_v').val(moneyFormat2(serviceMoney));

	$('#repairMoney').val(repairMoney);
	$('#repairMoney_v').val(moneyFormat2(repairMoney));

	setMoney('equRentalMoney',equRentalMoney);
	setMoney('spaceRentalMoney',spaceRentalMoney);
	setMoney('otherMoney',otherMoney);

	$('#dsEnergyCharge').val(dsEnergyCharge);
	$('#dsEnergyCharge_v').val(moneyFormat2(dsEnergyCharge));
	$('#dsWaterRateMoney').val(dsWaterRateMoney);
	$('#dsWaterRateMoney_v').val(moneyFormat2(dsWaterRateMoney));
	$('#houseRentalFee').val(houseRentalFee);
	$('#houseRentalFee_v').val(moneyFormat2(houseRentalFee));
	$('#installationCost').val(installationCost);
	$('#installationCost_v').val(moneyFormat2(installationCost));
}

/****************************** �ӱ�ͨ�� **********************************/
//�ӱ����
function detailAdd(tablelist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var tablelist = document.getElementById(tablelist);
	i=tablelist.rows.length;
	oTR =tablelist.insertRow([i]);
	oTR.align="center";
	oTR.height="30px";
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML=i;
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][productName]' id='invoiceEquName"+mycount+"' onblur='isEmpty(this,\"" + mycount +"\")' readonly='readonly' class='txtmiddle'/><input type='hidden' name='invoice[invoiceDetail]["+mycount+"][productId]' id='invoiceEquId"+mycount+"'/>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][amount]' style='width:50px;' id='amount"+mycount+"' class='txtshort'/>";
    oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][softMoney]' id='softMoney"+mycount+"' class='txtshort'/>";
    oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][hardMoney]' id='hardMoney"+mycount+"' class='txtshort'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][repairMoney]' id='repairMoney"+mycount+"' class='txtshort'/>";
    oTL6=oTR.insertCell([6]);
    oTL6.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][serviceMoney]' id='serviceMoney"+mycount+"' class='txtshort'/>";
    oTL7=oTR.insertCell([7]);
    oTL7.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][equRentalMoney]' id='equRentalMoney"+mycount+"' class='txtshort'/>";
    oTL8=oTR.insertCell([8]);
    oTL8.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][spaceRentalMoney]' id='spaceRentalMoney"+mycount+"' class='txtshort'/>";
    oTL9=oTR.insertCell([9]);
    oTL9.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][otherMoney]' id='otherMoney"+mycount+"' class='txtshort'/>";
    oTL10=oTR.insertCell([10]);

	oTL10.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][dsEnergyCharge]' id='dsEnergyCharge"+mycount+"' class='txtshort'/>";
	oTL11=oTR.insertCell([11]);
	oTL11.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][dsWaterRateMoney]' id='dsWaterRateMoney"+mycount+"' class='txtshort'/>";
	oTL12=oTR.insertCell([12]);
	oTL12.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][houseRentalFee]' id='houseRentalFee"+mycount+"' class='txtshort'/>";
	oTL13=oTR.insertCell([13]);
	oTL13.innerHTML="<input type='text' name='invoice[invoiceDetail]["+mycount+"][installationCost]' id='installationCost"+mycount+"' class='txtshort'/>";
	oTL14=oTR.insertCell([14]);
	oTL14.innerHTML="<select id='psType"+mycount+"' name='invoice[invoiceDetail]["+mycount+"][psType]' style='width:70px;' class='txt-auto'></select>";
	oTL15=oTR.insertCell([15]);
    oTL15.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='ɾ����'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    addDataToSelect(invoiceTypeArr, 'psType'+mycount);

    createFormatOnClick('amount'+mycount);
    //��ͳ���¼�
    $("#amount" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('softMoney'+mycount);
    //��ͳ���¼�
    $("#softMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('hardMoney'+mycount);
    //��ͳ���¼�
    $("#hardMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('repairMoney'+mycount);
    //��ͳ���¼�
    $("#repairMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('serviceMoney'+mycount);
    //��ͳ���¼�
    $("#serviceMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('equRentalMoney'+mycount);
    //��ͳ���¼�
    $("#equRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('spaceRentalMoney'+mycount);
    //��ͳ���¼�
    $("#spaceRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
    createFormatOnClick('otherMoney'+mycount);
    //��ͳ���¼�
    $("#otherMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
    });

	createFormatOnClick('dsEnergyCharge'+mycount);
	//��ͳ���¼�
	$("#dsEnergyCharge" + mycount + "_v").bind("blur",function(){
		countDetail();
	});
	createFormatOnClick('dsWaterRateMoney'+mycount);
	//��ͳ���¼�
	$("#dsWaterRateMoney" + mycount + "_v").bind("blur",function(){
		countDetail();
	});
	createFormatOnClick('houseRentalFee'+mycount);
	//��ͳ���¼�
	$("#houseRentalFee" + mycount + "_v").bind("blur",function(){
		countDetail();
	});
	createFormatOnClick('installationCost'+mycount);
	//��ͳ���¼�
	$("#installationCost" + mycount + "_v").bind("blur",function(){
		countDetail();
	});

	$("#invoiceEquName"+ mycount).yxcombogrid_datadict({
		hiddenId :  'invoiceEquId'+ mycount,
		gridOptions : {
			param : {"parentCode":"KPXM"},
			showcheckbox : false,
			isTitle : true,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						var thisObj = $("#invoiceEquName"+ mycount);
						$("#invoiceEquId" + mycount).val(data.dataCode);
						$("#invoiceEquName" + mycount).val(data.dataName);
						thisObj.attr('readonly',"readonly");
						if(data.dataCode == "QT"){
							thisObj.attr('readonly',"");
							thisObj.val("");
							thisObj.focus();
						}
					};
				}(mycount)
			}
		}
	});
}
/**********************ɾ����̬��*************************/
function mydel(obj,mytable)
{
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytable = document.getElementById(mytable);

   		mytable.deleteRow(rowNo);
   		var myrows=mytable.rows.length;

   		for(var i=1;i<myrows;i++){
			mytable.rows[i].childNodes[0].innerHTML=i;
		}
	}
	countDetail();
}

//�ж�������״̬,�ǵĻ��������ֵ����productId��
function isEmpty(obj,thisKey){
	var underObj = $("#invoiceEquId" + thisKey);
	if(obj.value != "" && underObj.val() == "QT"){
		underObj.val(obj.value);
	}
}

/****************************** ����ͨ�� **********************************/

//ѡ���ͬ��������
function selectPayCon(){
	var objId = $("#objId").val();
	if(objId != '0' && objId != ''){
		showOpenWin("?model=contract_contract_receiptplan"
				+ "&action=selectPayCon"
				+ "&contractId="
				+ objId ,1,500,1000,objId);
	}else{
		alert('����ѡ��Ʊ�ĺ�ͬ');
	}
}

/**
 * ���ø�������
 * @return {Boolean}
 */
function setDatas(rows){
	var objGrid = $("#checkTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϸ��������Ƿ��Ѵ���
		var payConIdArr = objGrid.yxeditgrid("getCmpByCol","payConId");
		var isExist = false;
		if(payConIdArr.length > 0){
			payConIdArr.each(function(){
				if(this.value == rows[i].id){
					isExist = true;
					alert('��ͬ��'+ rows[i].contractCode + "���ĸ���������"+ rows[i].paymentterm +"("+ rows[i].payDT +" "+ moneyFormat2(rows[i].money) +")���Ѿ������ں����б���,�����ظ�ѡ��" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractId",rows[i].contractId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractCode",rows[i].contractCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractName",rows[i].contractName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConName",rows[i].paymentterm);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"checkDate",$("#invoiceTime").val());
	}
}

// ��ʾ�ǿ�Ʊ������ʾ
function setCurrencyShowTips(){
    if($("#currency").val() != '�����'){
        $("#currencyShowTips").show();
    }else{
        $("#currencyShowTips").hide();
    }
}

//��Ʊ���Ͷ�Ӧ�ֶ�
function changeInvType(thisVal){
	innerInvType = $("#" + thisVal).find("option:selected").attr("e2");
	switch(innerInvType){
		case 'ZLHTFP'://���޺�ͬ��ֵ��Ʊ	
			$("#rentBeginDateNeed").html("[*]");
			$("#rentEndDateNeed").html("[*]");
			$(".rentInfo").show();
			break;
		case 'ZLHTPT'://���޺�ͬ��ͨ��Ʊ
			$("#rentBeginDateNeed").html("");
			$("#rentEndDateNeed").html("");
			$(".rentInfo").show();
			break;
		default :
			$(".rentInfo").hide();
			$("#rentBeginDate").val("");
			$("#rentEndDate").val("");
			$("#rentDays").val("");
			break;
	}
}

//������������
function rentDaysCount(){
	var rentBeginDate = $("#rentBeginDate").val();
	var rentEndDate = $("#rentEndDate").val();
	if(rentBeginDate != "" && rentEndDate != ""){
		if(rentBeginDate > rentEndDate){
			alert("���޽������ڲ����������޿�ʼ����");
		}else{
			$("#rentDays").val(DateDiff(rentBeginDate,rentEndDate) + 1);
		}
	}
}