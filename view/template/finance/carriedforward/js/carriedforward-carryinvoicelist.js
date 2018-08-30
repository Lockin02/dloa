hookedArr = new Array();
var thisYear ;
var thisMonth;
var sysYear ;
var sysMonth;
var isSame ;

$(function(){
	var ids = $("#ids").val();
	thisYear = $("#thisYear").val();
	thisMonth = $("#thisMonth").val();
	sysYear = $("#sysYear").val();
	sysMonth = $("#sysMonth").val();

	if(thisYear == sysYear && thisMonth == sysMonth){
		isSame = 1;
	}else{
		isSame = 0;
	}

	if(isSame == 0){
		msg = ' (ע �������һ�ڴ��ڹ�����¼�������޸Ĺ�����¼�����ɾ����һ�ڵĶ�Ӧ�Ĺ�����¼��)';
		$("#addMsg").html(msg);
	}

	if(ids != 'none'){
		hookedArr = ids.split(",");
		for(var i = 0 ;i < hookedArr.length ; i++){
			$("#" + hookedArr[i]).attr("checked",true);
		}
	}
})

//�첽��������
function ajaxSave(){
	var thisData = chooseFun();
//	$.showDump(thisData);
	if(thisData != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_carriedforward_carriedforward&action=carryInvoice",
			data : {
				"data" : thisData
			},
			success : function(msg) {
				msg = strTrim(msg);
				if (msg == 0) {
					alert("����ʧ��");
				}else if(msg != 'none'){
					alert('�����ɹ�');
					hookedArr = msg.split(",");
					updateCheckRate(thisData);
					$("#ids").val(msg);
				}else{
					alert('�����ɹ�');
					hookedArr = [];
					updateCheckRate(thisData);
					$("#ids").val('');
				}
			}
		});
	}else{
		alert('û��ѡ��ֵ');
		return false;
	}
}

/**
 * ѡ�񷽷������Ǳ��ڣ�ѡ�������ݻ�ȡ����getDataFun
 */
function chooseFun(){
	if(isSame == 1){
		return getDataFun();
	}else{
		return getDataChangeFun();
	}
}

//��ȡ�������json����,�����ڻ�ȡ������ʹ�÷���
function getDataFun(){
	//�����ַ���
	newDataStr = "";
	//����id�ַ���
	newDataIds = [];

	//�޸��ַ���
	dataStr = "";
	dataIds = [];

	//ɾ�������ַ���
	unDataStr = "";

	//��ʱ�洢����
	var tempKey = "";
	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();
		tempId = this.id;
		invoiceObj = $("#invoice" + tempId); //��Ӧ��Ʊ����
		saleObj = $("#sale" + tempId);  //��Ӧ���ۺ�ͬ����
		saleCodeObj = $("#saleCode" + tempId); //��Ӧ���ۺ�ͬ���
		saleTypeObj = $("#objType" + tempId); //��Ӧ��ͬ����
		rateObj = $("#rate" + tempId);  //��������
		hiddenRateObj = $("#hiddenRate" + tempId);  //�ѹ�������
		outStockIdObj = $("#outStockId" + tempId);  //���ⵥ��id����
		outStockCodeObj = $("#outStockCode" + tempId);  //���ⵥ�ݱ��

		if($(this).attr('checked') == true){
			if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey) == -1){//�¹����ַ�������
				if(newDataStr == "") newDataStr = {};
				newDataStr[i] = {
					'outStockDetaiId' : tempKey ,
					'invoiceId' : invoiceObj.val() ,
					'saleId' : saleObj.val() ,
					'saleCode' : saleCodeObj.val() ,
					'saleType' : saleTypeObj.val() ,
					'outStockId' : outStockIdObj.val() ,
					'outStockCode' : outStockCodeObj.val() ,
					'carryRate' : rateObj.val() * 1
				};
				newDataIds.push(tempId);
			}else{//�޸��ַ�������
				if(  hiddenRateObj.val()*1 !=  rateObj.val() * 1 ){
				if(dataStr == "") dataStr = {};
					dataStr[i] = {
						'outStockDetaiId' : tempKey,
						'invoiceId' : invoiceObj.val() ,
						'saleId' : saleObj.val() ,
						'saleCode' : saleCodeObj.val() ,
						'saleType' : saleTypeObj.val() ,
						'outStockId' : outStockIdObj.val() ,
						'outStockCode' : outStockCodeObj.val() ,
						'carryRate' : rateObj.val() * 1
					};
					dataIds.push(tempId);
				}
			}
		}else{//ɾ���ַ�������
			if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey ) != -1){
				if(unDataStr == "") unDataStr = {};
				unDataStr[i] = {
					'outStockDetaiId' : tempKey,
					'invoiceId' : invoiceObj.val(),
					'carryRate' : rateObj.val() * 1
				};
			}
		}
	});
	if(newDataStr.length == 0 && dataStr.length == 0 && unDataStr.length == 0 ){
		return false;
	}else{
		dataJson = {
			"updateStr" : dataStr ,
			"updateIds" : dataIds ,
			"newStr" : newDataStr ,
			"newIds" : newDataIds,
			"delStr" : unDataStr,
			"sysYear" : sysYear,
			"sysMonth" : sysMonth,
			"thisYear" : thisYear,
			"thisMonth" : thisMonth
		};
		return dataJson;
	}
}

//��ȡ�������json����,�Ǳ��ڻ�ȡ������ʹ�÷���
function getDataChangeFun(){
	//�����ַ���
	newDataStr = "";
	//����id�ַ���
	newDataIds = [];

	//�޸��ַ���
	dataStr = "";
	dataIds = [];

	//ɾ�������ַ���
	unDataStr = "";

	//��ʱ�洢����
	var tempKey = "";

	var rateDiffer;
	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();
		tempId = this.id;
		invoiceObj = $("#invoice" + tempId); //��Ӧ��Ʊ����
		saleObj = $("#sale" + tempId);  //��Ӧ���ۺ�ͬ����
		saleCodeObj = $("#saleCode" + tempId); //��Ӧ���ۺ�ͬ���
		saleTypeObj = $("#objType" + tempId); //��Ӧ��ͬ����
		rateObj = $("#rate" + tempId);  //��������
		hiddenRateObj = $("#hiddenRate" + tempId);  //�ѹ�������
		outStockIdObj = $("#outStockId" + tempId);  //���ⵥ��id����
		outStockCodeObj = $("#outStockCode" + tempId);  //���ⵥ�ݱ��
		thisCarryObj = $("#thisCarry" + tempId); //���¹������
		beforeCarryObj = $("#beforeCarry" + tempId); //ԭ�������
		rateDiffer = accSub(rateObj.val()*1, beforeCarryObj.val()*1);//�¹��������ѹ��������

		if($(this).attr('checked') == true){
			if(rateDiffer != 0){
				if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey) == -1 || thisCarryObj.val() == "" || thisCarryObj.val()*1 == 0){//�¹����ַ�������
					if(newDataStr == "") newDataStr = {};
					newDataStr[i] = {
						'outStockDetaiId' : tempKey ,
						'invoiceId' : invoiceObj.val() ,
						'saleId' : saleObj.val() ,
						'saleCode' : saleCodeObj.val() ,
						'saleType' : saleTypeObj.val() ,
						'outStockId' : outStockIdObj.val() ,
						'outStockCode' : outStockCodeObj.val() ,
						'carryRate' : rateDiffer
					};
					newDataIds.push(tempId);
				}else{//�޸��ַ�������
					if(  hiddenRateObj.val()*1 !=  rateObj.val() * 1 ){
					if(dataStr == "") dataStr = {};
						dataStr[i] = {
							'outStockDetaiId' : tempKey,
							'invoiceId' : invoiceObj.val() ,
							'saleId' : saleObj.val() ,
							'saleCode' : saleCodeObj.val() ,
							'saleType' : saleTypeObj.val() ,
							'outStockId' : outStockIdObj.val() ,
							'outStockCode' : outStockCodeObj.val() ,
							'carryRate' : rateDiffer
						};
						dataIds.push(tempId);
					}
				}
			}
		}else{//ɾ���ַ�������
			if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey ) != -1){
				if(unDataStr == "") unDataStr = {};
				unDataStr[i] = {
					'outStockDetaiId' : tempKey,
					'invoiceId' : invoiceObj.val(),
					'carryRate' : rateObj.val() * 1
				};
			}
		}
	});
	if(newDataStr.length == 0 && dataStr.length == 0 && unDataStr.length == 0 ){
		return false;
	}else{
		dataJson = {
			"updateStr" : dataStr ,
			"updateIds" : dataIds ,
			"newStr" : newDataStr ,
			"newIds" : newDataIds,
			"delStr" : unDataStr,
			"sysYear" : sysYear,
			"sysMonth" : sysMonth,
			"thisYear" : thisYear,
			"thisMonth" : thisMonth
		};
		return dataJson;
	}
}

function checkThisNum(thisObj,rowId){
	if(!isNaN(thisObj.value)){
		if(thisObj.value > 100 || thisObj.value < 0){
			alert('���ܴ���100����С��0');
			thisObj.value = 0;
		}

		if(isSame == 0){
			thisCarry = $("#thisCarry" + rowId).val()*1; //���¹������
			beforeCarry = $("#beforeCarry" + rowId).val()*1; //ԭ�������
			if( thisObj.value*1 <= beforeCarry ){
				alert('��������ֻ�ܴ����ѹ������');
				thisObj.value = beforeCarry + thisCarry;
				return false;
			}
		}
	}
}

/**
 * ���ѡ��󽫶�Ӧ�İٷֱȱ��100
 * @param {} objId
 */
function setRate(thisId){
	rateObj = $("#rate" + thisId);
	checkObk = $("#" + thisId);
	if(checkObk.attr("checked") == true){
		if(rateObj.val() == 0 ){
			rateObj.val(100);
		}
	}else{
		rateObj.val(0);
	}
}

/**
 * ��������
 * @param {�����ͬid} objId
 */
function countHooked(objId,mainId,thisType){
	//���������
	var allHooked = 0;
	checkObj = $(".countCheck"+ objId);
	allHooked = sumAllHookedMoney(checkObj);

	//�жϣ��������������С�ƽ�����лع�
	if( allHooked > $("#count" + objId).val()*1){
		if(thisType == 'input'){//�������������д�����ֵ
			alert('���������ڳ������������д');
			$("#rate" + mainId).val($("#hiddenRate" + mainId).val());
		}else{//����ȡ��ѡ��
			alert('���������ڳ����������д���������������ͬ�ڵ�����������¼');
			$("#"+ mainId).attr('checked',false);
			setRate(mainId);
		}
		return false;
	}

	$("#hooked" + objId).val(allHooked);
	$("#hookedView" + objId).html(moneyFormat2(allHooked));
}


/**
 * ��ѡ����󷵻ؼ�����
 * @param {ѡ�����} thisData
 */
function sumAllHookedMoney(obj){
	var allHooked = 0;
	$.each(checkObj,function(i,n){
		if($(this).attr("checked") == true){
			rateObj = $("input[id='rate"+ this.id +"']");
			thisMoneyObj = $("input[id='thisMoney"+ this.id +"']");

			allHooked = accAdd(allHooked,accMul( accDiv(rateObj.val(),100) ,thisMoneyObj.val() * 1 ));
		}
	})
	return allHooked;
}


/**
 * �����ɹ��󣬽������͸������ݵİٷֱ���������и���
 */
function updateCheckRate(thisData){
	thisObj = (thisData);

	//ˢ�¸��¼�¼
	for(var i = 0 ; i < thisObj["updateIds"].length ; i++){
		thisId = thisObj["updateIds"][i];
		$("#hiddenRate" + thisId).val($("#rate" + thisId).val());
	}

	//ˢ���½���¼
	for(var i = 0 ; i < thisObj["newIds"].length ; i++){
		thisId = thisObj["newIds"][i];
		$("#hiddenRate" + thisId).val($("#rate" + thisId).val());
	}
}