//��ʼ����Ϣ
var hookedArr = new Array(); //�ѹ�������ID����
var thisYear ;
var thisMonth;
var sysYear ;
var sysMonth;
var isSame ;

$(function(){
	thisYear = $("#thisYear").val();
	thisMonth = $("#thisMonth").val();
	sysYear = $("#sysYear").val();
	sysMonth = $("#sysMonth").val();

	if(thisYear == sysYear && thisMonth == sysMonth){
		isSame = 1;
	}else{
		isSame = 0;
	}
});

/**
 * ���ݺ�ͬ��Ϣ��ȡ���ⵥ��
 **/
function getStockoutInfo(thisI,isReload){
	if($("span[id='row"+ thisI +"'] img").length == 1 && isReload != 1){
		return false;
	}

	//��ȡ����Ϣ
	var salesId = $("#objId" + thisI).val(); //��ͬid
	var salesCode = $("#objCode" + thisI).val();  //��ͬ���
	var salesType = $("#objType" + thisI).val();
	var rObjCode = $("#rObjCode" + thisI).val();
	var invoiceId = $("#invoiceId" + thisI).val();
	var invoiceNo = $("#invoiceNo" + thisI).val();
	var invoiceDetailId = $("#invoiceDetailId" + thisI).val();
	var productModel = $("#productModel" + thisI).val();

	$("#stockList").empty();
	$("#stockList").html("<tr class='tr_odd'><td colspan='7'><img src='images/loading1.gif'/></td></tr>");

	$.ajax({
	    type: "POST",
	    url: "?model=finance_carriedforward_carriedforward&action=getOutstockByInvoiceDetailId",
	    data: {"saleId" : salesId , 'saleType' : salesType , 'invoiceDetailId' : invoiceDetailId ,'thisYear': thisYear,'thisMonth': thisMonth , 'thisI' : thisI},
	    async: false,
	    success: function(data){

   			//��ʾ��ѡ��Ʊ��
   			selectedRowsView(thisI);

	   		if(data != ""){
	   			//��������б�
	   			$("#stockList").empty();
	   			$("#stockList").html(data);

	   			//��ʼ��������Ϣ
	   			stockListInit();

		   	    var outstockIds = $("#outstockIds").val();
				if(outstockIds != 'none'){
					hookedArr = outstockIds.split(",");
				}
	   	    }else{
	   			$("#stockList").empty();
	   			$("#stockList").html("<tr class='tr_odd'><td colspan='7'>�ú�ͬû����س����¼</td></tr>");
	   	    }

	   	    //���õ�ǰ��ѡ���¼��Ϣ
	   	    $("#salesId").val(salesId);
	   	    $("#salesCode").val(salesCode);
	   	    $("#salesType").val(salesType);
	   	    $("#rObjCode").val(rObjCode);
	   	    $("#invoiceId").val(invoiceId);
	   	    $("#invoiceDetailId").val(invoiceDetailId);
	   	    $("#thisI").val(thisI);

	   	    $("#addMsg").html(salesCode + ' ' + invoiceNo + ' ' + productModel );
		}
	});
}

/**
 * �첽�洢����
 */
function ajaxSave(){
	if($(":checkbox[name='outstock']").length != 0){//���ڳ�����Ϣ
		var thisData = chooseFun();//��ȡ���ݣ����ݸ�ʽΪjson
//		$.showDump(thisData);
		if(thisData != false){
			$.ajax({
				type : "POST",
				url : "?model=finance_carriedforward_carriedforward&action=carryInvoice2",
				data : {
					"data" : thisData
				},
				success : function(msg) {
					msg = strTrim(msg);
					if(msg == 1){
						alert('�����ɹ�');
						getStockoutInfo($("#thisI").val(),1);
					}else{
						alert('����ʧ��');
					}
				}
			});
		}else{
			alert('û��ѡ�л����޸ĵ�ֵ');
			return false;
		}
	}else{//�����ڳ�����Ϣ
		alert('û�ж�Ӧ����');
		return false;
	}
}

/**
 * ѡ�񷽷�
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

	//�к�
	rowNum = "";

	var salesId = $("#salesId").val(); //��ͬid
	var salesCode = $("#salesCode").val();  //��ͬ���
	var salesType = $("#salesType").val();  //��ͬ����
	var invoiceId = $("#invoiceId").val(); //��Ʊid
	var rObjCode = $("#rObjCode").val(); //��Ʊid
	var invoiceDetailId = $("#invoiceDetailId").val();  //��Ʊ��ϸid

	$(":checkbox[name='outstock']").each(function(i,n){
		rowNum = $(this).val();

		var outStockId = $("#outstockId" + rowNum).val();//����ID
		var outStockCode = $("#outstockCode" + rowNum).val();//���ⵥ��
		var carryRate = $("#rate" + rowNum).val(); //��������
		var carryRateHidden = $("#rateHidden" + rowNum).val(); //���ع�������

		if($(this).attr('checked') == true){
			if(hookedArr.indexOf( outStockId) == -1){//�¹����ַ�������
				if(newDataStr == "") newDataStr = {};
				newDataStr[i] = {
					'saleId' : salesId,
					'saleCode' : salesCode,
					'saleType' : salesType,
					'rObjCode' : rObjCode,
					'invoiceId' : invoiceId,
					'invoiceDetailId' : invoiceDetailId,
					'outStockId' : outStockId,
					'outStockCode' : outStockCode,
					'carryRate' : carryRate
				};
			}else{//�޸��ַ�������
				if(  carryRateHidden * 1 !=  carryRate * 1 ){
					if(dataStr == "") dataStr = {};
					dataStr[i] = {
						'invoiceDetailId' : invoiceDetailId,
						'outStockId' : outStockId,
						'carryRate' : carryRate
					};
				}
			}
		}else{//ɾ���ַ�������
			if(hookedArr.indexOf( outStockId ) != -1){
				if(unDataStr == "") unDataStr = {};
				unDataStr[i] = {
					'outStockId' : outStockId,
					'invoiceDetailId' : invoiceDetailId
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

//��ȡ�������json����,�Ǳ����ڻ�ȡ������ʹ�÷���
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

	//�к�
	rowNum = "";

	var salesId = $("#salesId").val(); //��ͬid
	var salesCode = $("#salesCode").val();  //��ͬ���
	var salesType = $("#salesType").val();  //��ͬ����
	var rObjCode = $("#rObjCode").val(); //��Ʊid
	var invoiceId = $("#invoiceId").val(); //��Ʊid
	var invoiceDetailId = $("#invoiceDetailId").val();  //��Ʊ��ϸid

	$(":checkbox[name='outstock']").each(function(i,n){
		rowNum = $(this).val();

		var outStockId = $("#outstockId" + rowNum).val();//����ID
		var outStockCode = $("#outstockCode" + rowNum).val();//���ⵥ��
		var carryRate = $("#rate" + rowNum).val(); //��������
		var carryRateHidden = $("#rateHidden" + rowNum).val(); //���ع�������
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + rowNum).val(); //ǰ�ڹ�������
		var thisPeriodCarryRate = $("#thisPeriodCarryRate" + rowNum).val(); //���ڹ�������
		rateDiffer = accSub(carryRate,beforePeriodCarryRate,2);//�¹��������ѹ��������


		if($(this).attr('checked') == true){
			if(rateDiffer*1 != 0){//�����ڹ�����������ʱ,���й������ݻ�ȡ
				if(hookedArr.indexOf( outStockId) == -1 || thisPeriodCarryRate*1 == 0){//�¹����ַ�������
					if(newDataStr == "") newDataStr = {};
					newDataStr[i] = {
						'saleId' : salesId,
						'saleCode' : salesCode,
						'saleType' : salesType,
						'rObjCode' : rObjCode,
						'invoiceId' : invoiceId,
						'invoiceDetailId' : invoiceDetailId,
						'outStockId' : outStockId,
						'outStockCode' : outStockCode,
						'carryRate' : rateDiffer
					};
				}else{//�޸��ַ�������
					if(  carryRateHidden * 1 !=  carryRate * 1 ){
						if(dataStr == "") dataStr = {};
						dataStr[i] = {
							'invoiceDetailId' : invoiceDetailId,
							'outStockId' : outStockId,
							'carryRate' : rateDiffer
						};
					}
				}
			}else if(rateDiffer*1 == 0 && thisPeriodCarryRate != 0){//�����ڹ�����������Ϊ0,���Ǳ��ڹ���������Ϊ0ʱ
				if(hookedArr.indexOf( outStockId ) != -1){
					if(unDataStr == "") unDataStr = {};
					unDataStr[i] = {
						'outStockId' : outStockId,
						'invoiceDetailId' : invoiceDetailId
					};
				}
			}
		}else{//ɾ���ַ�������
			if(hookedArr.indexOf( outStockId ) != -1){
				if(unDataStr == "") unDataStr = {};
				unDataStr[i] = {
					'outStockId' : outStockId,
					'invoiceDetailId' : invoiceDetailId
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


//�������ʼ��
function stockListInit(){
	// ��Ⱦ ǧ��λ���
	$.each($("#outstockDetail .formatMoney"), function(i, n) {
		if ($(this).get(0).tagName == 'INPUT') {
			var strHidden = "<input type='hidden' name='" + n.name
					+ "' id='" + n.id + "' value='" + n.value + "' />";
			$(this).attr('name', '');
			$(this).attr('id', n.id + '_v');
			$(this).val(moneyFormat2(n.value));
			$(this).bind("blur", function() {
						moneyFormat1(this);
						if (n.onblur)
							n.onblur();
					});
			$(this).after(strHidden);
		} else {
			returnMoney = moneyFormat2($(this).text());
			$(this).text(returnMoney);
		}
	});
}

//��ʾ��ѡ����
function selectedRowsView(thisI){
	$("span[id^='row'] img").remove();
	$("#row" + thisI ).html("<img src='images/icon/icon002.gif'/>");
}

//���㹳����Ϣ
function countHooked(thisI,thisType){
	//������������
	var checkBtnObj = $("#checkBtn" + thisI);
	//������������
	var rateObj = $("#rate" + thisI);
	//���������
	var outstockMoneyObj =  $("#outstockMoney" + thisI);
	//����������
	var hookMoneyObj = $("#hookMoney" + thisI);
	//���ѹ������
	var rowHookMoney = 0;

	//���㵥�й������
	if(checkBtnObj.attr('checked') == true){
		//�й������
		rowHookMoney = accMul( accDiv(rateObj.val()*1,100,2) ,outstockMoneyObj.val() * 1 ,2);
		hookMoneyObj.val(rowHookMoney);
		$("#hookMoney" + thisI + "_v").val(moneyFormat2(rowHookMoney));
	}else{
		hookMoneyObj.val(0);
		$("#hookMoney" + thisI + "_v").val(0);
	}

	//�����ܹ���������д���б���
	var allHookMoney = sumAllHookedMoney($(":checkbox[name='outstock']"));
	$("#allHookMoney").text(moneyFormat2(allHookMoney));

}

/**
 * ��ѡ����󷵻ؼ�����
 * @param {ѡ�����} thisData
 */
function sumAllHookedMoney(checkObj){
	var allHooked = 0;
	var j;
	$.each(checkObj,function(i,n){
		if($(this).attr("checked") == true){
			j = i + 1;
			var rateObj = $("input[id='rate"+ j +"']");
			var thisMoneyObj = $("input[id='outstockMoney"+ j +"']");

			allHooked = accAdd(allHooked,accMul( accDiv(rateObj.val()*1,100,2) ,thisMoneyObj.val() * 1 ,2),2);
		}
	})
	return allHooked;
}

/**
 * ���ѡ��󽫶�Ӧ�İٷֱȱ��100
 * @param {} objId
 */
function setRate(thisId){
	var rateObj = $("#rate" + thisId);
	var checkObk = $("#checkBtn" + thisId);
	//����ɹ�������
	var canCarryRate = $("#canCarryRate" + thisId).val();
	if(checkObk.attr("checked") == true){//ѡ��ʱ���ɹ����������뵽����������
		if(rateObj.val() == 0 ){
			rateObj.val(canCarryRate);
			$("#canCarryRate" + thisId).val(0);
			$("#canCarryRateView" + thisId).html(0);
		}
	}else{
		//ȡ��ѡ��ʱ�����������ϲ����ɹ���������
		var newCarryRate = accAdd(canCarryRate,rateObj.val(),2) ;
		$("#canCarryRate" + thisId).val(newCarryRate);
		$("#canCarryRateView" + thisId).html(newCarryRate);
		rateObj.val(0);
	}
}

//�ı�ֵ������֤
function checkThisNum(thisObj,thisI){
	checkRate = thisObj.value*1;
	if(!isNaN(checkRate)){

		//�ɹ�������
		var canCarryRate = $("#canCarryRate" + thisI).val()*1;
		//�ɹ���Դ����
		var orgCanCarryRate = $("#orgCanCarryRate" + thisI).val()*1;
		//�ѹ�������
		var rateHidden = $("#rateHidden" + thisI ).val()*1;
		//�ܿɹ�������
		var allCanCarryRate = $("#allCanCarryRate" + thisI ).val()*1;
		//ǰ�ڹ�������
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + thisI ).val()*1;
		//�¿ɹ�������
		var newCarryRate = accSub(allCanCarryRate,checkRate,2);


		if(checkRate > 100 || checkRate < 0){
			alert('���ܴ���100����С��0');
			checkRate = rateHidden;
			$("#canCarryRate" + thisI).val(orgCanCarryRate);
			$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			return false;
		}else{//�������������� �ɹ������� + �ѹ�������ʱ,����
			if( checkRate < beforePeriodCarryRate){
				alert('������������С��ǰ�ڹ�������');
				checkRate = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
				return false;
			}

			if( checkRate > allCanCarryRate*1){
				alert('�����������ܳ����ɹ������� + �ѹ�������');
				checkRate = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			}else{
				$("#canCarryRate" + thisI).val(newCarryRate);
				$("#canCarryRateView" + thisI).html(newCarryRate);
			}

		}
	}else{
		alert('ֻ����������');
		checkRate = 0;
	}
}


//������ϸ����
function carryDetail(thisI,outstockId){
	var invoiceDetailId = $("#invoiceDetailId").val();  //��Ʊ��ϸid
	var sysYear = $("#sysYear").val();  //��Ʊ��ϸid
	var sysMonth = $("#sysMonth").val();  //��Ʊ��ϸid

	var salesId = $("#salesId").val(); //��ͬid
	var salesCode = $("#salesCode").val();  //��ͬ���
	var salesType = $("#salesType").val();  //��ͬ����
	var rObjCode = $("#rObjCode").val();  //��ͬ����
	var invoiceId = $("#invoiceId").val(); //��Ʊ
	var mainI = $("#mainI" + thisI).val(); //��Ʊ
	showThickboxWin("?model=finance_carriedforward_carriedforward&action=outstockDetailCarryView"
		+ "&outstockId=" + outstockId
		+ "&invoiceDetailId=" + invoiceDetailId
		+ "&sysYear=" + sysYear
		+ "&sysMonth=" + sysMonth
		+ "&salesId=" + salesId
		+ "&salesCode=" + salesCode
		+ "&salesType=" + salesType
		+ "&rObjCode=" + rObjCode
		+ "&invoiceId=" + invoiceId
		+ "&mainI=" + mainI
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
}

//�鿴���ⵥ��
function viewStock(outstockId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=stock_outstock_stockout&action=md5RowAjax",
	    data: {"id" : outstockId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("index1.php?model=stock_outstock_stockout&action=toView&id="+outstockId+"&docType=CKSALES&skey=" + skey);
}

//�鿴��Ʊ��¼
function viewInvoice(invoiceId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=finance_invoice_invoice&action=md5RowAjax",
	    data: {"id" : invoiceId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("index1.php?model=finance_invoice_invoice&action=init&perm=view&id="+invoiceId+"&skey=" + skey);
}

//�鿴��ͬ
function viewSale(saleId,saleType){
	var skey = "";

	switch(saleType){
		case 'oa_sale_order':
		    $.ajax({
			    type: "POST",
			    url: "?model=projectmanagent_order_order&action=md5RowAjax",
			    data: {"id" : saleId},
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("index1.php?model=projectmanagent_order_order&action=toViewTab&id="+saleId+"&skey=" + skey);
			break;
		case 'oa_sale_service':
		    $.ajax({
			    type: "POST",
			    url: "?model=engineering_serviceContract_serviceContract&action=md5RowAjax",
			    data: {"id" : saleId},
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("index1.php?model=engineering_serviceContract_serviceContract&action=toViewTab&id="+saleId+"&skey=" + skey);
			break;
		case 'oa_sale_lease':
		    $.ajax({
			    type: "POST",
			    url: "?model=contract_rental_rentalcontract&action=md5RowAjax",
			    data: {"id" : saleId},
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("index1.php?model=contract_rental_rentalcontract&action=toViewTab&id="+saleId+"&skey=" + skey);
			break;
		case 'oa_sale_rdproject':
		    $.ajax({
			    type: "POST",
			    url: "?model=rdproject_yxrdproject_rdproject&action=md5RowAjax",
			    data: {"id" : saleId},
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("index1.php?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="+saleId+"&skey=" + skey);
			break;
		case 'KPRK-12':
		    $.ajax({
			    type: "POST",
			    url: "?model=contract_contract_contract&action=md5RowAjax",
			    data: {"id" : saleId},
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("index1.php?model=contract_contract_contract&action=toViewTab&id="+saleId+"&skey=" + skey);
			break;
		default : alert('�����ڵĺ�ͬ����');break;
	}
}

//˫������/��ʾ�б�
function closeList(markId){
	var markObj = $("#mark_" + markId);
	if(markObj.val() == 0){
		$(".lc_" + markId ).hide();
		$("#img_" + markId).attr('src','images/icon/icon001.gif');
		markObj.val(1);
	}else{
		$(".lc_" + markId ).show();
		$("#img_" + markId).attr('src','images/icon/icon003.gif');
		markObj.val(0);
	}
}


//��ӡ����
function printOutstock(outstockId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=stock_outstock_stockout&action=md5RowAjax",
	    data: {"id" : outstockId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	var url = '?model=stock_outstock_stockout&action=toPrintForCarry&id='
		+ outstockId
		+ "&docType=CKSALES"
		+ "&skey="
		+ skey;
	showOpenWin(url,1);
}