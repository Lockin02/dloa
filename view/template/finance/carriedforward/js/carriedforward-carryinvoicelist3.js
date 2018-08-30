//初始化信息
var hookedArr = new Array(); //已钩稽出库ID数组
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
 * 根据合同信息获取出库单号
 **/
function getStockoutInfo(thisI,isReload){
	if($("span[id='row"+ thisI +"'] img").length == 1 && isReload != 1){
		return false;
	}

	//获取行信息
	var salesId = $("#objId" + thisI).val(); //合同id
	var salesCode = $("#objCode" + thisI).val();  //合同编号
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

   			//显示被选择发票行
   			selectedRowsView(thisI);

	   		if(data != ""){
	   			//载入出库列表
	   			$("#stockList").empty();
	   			$("#stockList").html(data);

	   			//初始化出库信息
	   			stockListInit();

		   	    var outstockIds = $("#outstockIds").val();
				if(outstockIds != 'none'){
					hookedArr = outstockIds.split(",");
				}
	   	    }else{
	   			$("#stockList").empty();
	   			$("#stockList").html("<tr class='tr_odd'><td colspan='7'>该合同没有相关出库记录</td></tr>");
	   	    }

	   	    //设置当前被选择记录信息
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
 * 异步存储方法
 */
function ajaxSave(){
	if($(":checkbox[name='outstock']").length != 0){//存在出库信息
		var thisData = chooseFun();//获取数据，数据格式为json
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
						alert('钩稽成功');
						getStockoutInfo($("#thisI").val(),1);
					}else{
						alert('钩稽失败');
					}
				}
			});
		}else{
			alert('没有选中或者修改的值');
			return false;
		}
	}else{//不存在出库信息
		alert('没有对应单据');
		return false;
	}
}

/**
 * 选择方法
 */
function chooseFun(){
	if(isSame == 1){
		return getDataFun();
	}else{
		return getDataChangeFun();
	}
}

//获取数据组成json数组,本期内获取数据所使用方法
function getDataFun(){
	//新增字符串
	newDataStr = "";
	//新增id字符串
	newDataIds = [];

	//修改字符串
	dataStr = "";
	dataIds = [];

	//删除对象字符串
	unDataStr = "";

	//行号
	rowNum = "";

	var salesId = $("#salesId").val(); //合同id
	var salesCode = $("#salesCode").val();  //合同编号
	var salesType = $("#salesType").val();  //合同类型
	var invoiceId = $("#invoiceId").val(); //开票id
	var rObjCode = $("#rObjCode").val(); //开票id
	var invoiceDetailId = $("#invoiceDetailId").val();  //开票详细id

	$(":checkbox[name='outstock']").each(function(i,n){
		rowNum = $(this).val();

		var outStockId = $("#outstockId" + rowNum).val();//出库ID
		var outStockCode = $("#outstockCode" + rowNum).val();//出库单号
		var carryRate = $("#rate" + rowNum).val(); //钩稽比例
		var carryRateHidden = $("#rateHidden" + rowNum).val(); //隐藏钩稽比例

		if($(this).attr('checked') == true){
			if(hookedArr.indexOf( outStockId) == -1){//新钩稽字符串处理
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
			}else{//修改字符串处理
				if(  carryRateHidden * 1 !=  carryRate * 1 ){
					if(dataStr == "") dataStr = {};
					dataStr[i] = {
						'invoiceDetailId' : invoiceDetailId,
						'outStockId' : outStockId,
						'carryRate' : carryRate
					};
				}
			}
		}else{//删除字符串处理
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

//获取数据组成json数组,非本期内获取数据所使用方法
function getDataChangeFun(){
	//新增字符串
	newDataStr = "";
	//新增id字符串
	newDataIds = [];

	//修改字符串
	dataStr = "";
	dataIds = [];

	//删除对象字符串
	unDataStr = "";

	//行号
	rowNum = "";

	var salesId = $("#salesId").val(); //合同id
	var salesCode = $("#salesCode").val();  //合同编号
	var salesType = $("#salesType").val();  //合同类型
	var rObjCode = $("#rObjCode").val(); //开票id
	var invoiceId = $("#invoiceId").val(); //开票id
	var invoiceDetailId = $("#invoiceDetailId").val();  //开票详细id

	$(":checkbox[name='outstock']").each(function(i,n){
		rowNum = $(this).val();

		var outStockId = $("#outstockId" + rowNum).val();//出库ID
		var outStockCode = $("#outstockCode" + rowNum).val();//出库单号
		var carryRate = $("#rate" + rowNum).val(); //钩稽比例
		var carryRateHidden = $("#rateHidden" + rowNum).val(); //隐藏钩稽比例
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + rowNum).val(); //前期钩稽比例
		var thisPeriodCarryRate = $("#thisPeriodCarryRate" + rowNum).val(); //本期钩稽比例
		rateDiffer = accSub(carryRate,beforePeriodCarryRate,2);//新钩稽数和已钩稽数差额


		if($(this).attr('checked') == true){
			if(rateDiffer*1 != 0){//当存在钩稽比例增加时,进行钩稽数据获取
				if(hookedArr.indexOf( outStockId) == -1 || thisPeriodCarryRate*1 == 0){//新钩稽字符串处理
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
				}else{//修改字符串处理
					if(  carryRateHidden * 1 !=  carryRate * 1 ){
						if(dataStr == "") dataStr = {};
						dataStr[i] = {
							'invoiceDetailId' : invoiceDetailId,
							'outStockId' : outStockId,
							'carryRate' : rateDiffer
						};
					}
				}
			}else if(rateDiffer*1 == 0 && thisPeriodCarryRate != 0){//当存在钩稽比例增加为0,但是本期钩稽比例不为0时
				if(hookedArr.indexOf( outStockId ) != -1){
					if(unDataStr == "") unDataStr = {};
					unDataStr[i] = {
						'outStockId' : outStockId,
						'invoiceDetailId' : invoiceDetailId
					};
				}
			}
		}else{//删除字符串处理
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


//出库表单初始化
function stockListInit(){
	// 渲染 千分位金额
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

//显示被选择行
function selectedRowsView(thisI){
	$("span[id^='row'] img").remove();
	$("#row" + thisI ).html("<img src='images/icon/icon002.gif'/>");
}

//计算钩稽信息
function countHooked(thisI,thisType){
	//钩稽按键对象
	var checkBtnObj = $("#checkBtn" + thisI);
	//钩稽比例对象
	var rateObj = $("#rate" + thisI);
	//出库金额对象
	var outstockMoneyObj =  $("#outstockMoney" + thisI);
	//钩稽金额对象
	var hookMoneyObj = $("#hookMoney" + thisI);
	//行已钩稽金额
	var rowHookMoney = 0;

	//计算单行钩稽金额
	if(checkBtnObj.attr('checked') == true){
		//行钩稽金额
		rowHookMoney = accMul( accDiv(rateObj.val()*1,100,2) ,outstockMoneyObj.val() * 1 ,2);
		hookMoneyObj.val(rowHookMoney);
		$("#hookMoney" + thisI + "_v").val(moneyFormat2(rowHookMoney));
	}else{
		hookMoneyObj.val(0);
		$("#hookMoney" + thisI + "_v").val(0);
	}

	//计算总钩稽金额，并且写道列表中
	var allHookMoney = sumAllHookedMoney($(":checkbox[name='outstock']"));
	$("#allHookMoney").text(moneyFormat2(allHookMoney));

}

/**
 * 传选择对象返回计算金额
 * @param {选择对象} thisData
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
 * 点击选择后将对应的百分比变成100
 * @param {} objId
 */
function setRate(thisId){
	var rateObj = $("#rate" + thisId);
	var checkObk = $("#checkBtn" + thisId);
	//计算可钩稽比例
	var canCarryRate = $("#canCarryRate" + thisId).val();
	if(checkObk.attr("checked") == true){//选中时将可钩稽比例放入到钩稽比例中
		if(rateObj.val() == 0 ){
			rateObj.val(canCarryRate);
			$("#canCarryRate" + thisId).val(0);
			$("#canCarryRateView" + thisId).html(0);
		}
	}else{
		//取消选择时将钩稽比例合并到可钩稽比例中
		var newCarryRate = accAdd(canCarryRate,rateObj.val(),2) ;
		$("#canCarryRate" + thisId).val(newCarryRate);
		$("#canCarryRateView" + thisId).html(newCarryRate);
		rateObj.val(0);
	}
}

//文本值输入验证
function checkThisNum(thisObj,thisI){
	checkRate = thisObj.value*1;
	if(!isNaN(checkRate)){

		//可钩稽比例
		var canCarryRate = $("#canCarryRate" + thisI).val()*1;
		//可钩稽源比例
		var orgCanCarryRate = $("#orgCanCarryRate" + thisI).val()*1;
		//已钩稽比例
		var rateHidden = $("#rateHidden" + thisI ).val()*1;
		//总可钩稽比例
		var allCanCarryRate = $("#allCanCarryRate" + thisI ).val()*1;
		//前期钩稽比例
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + thisI ).val()*1;
		//新可钩稽比例
		var newCarryRate = accSub(allCanCarryRate,checkRate,2);


		if(checkRate > 100 || checkRate < 0){
			alert('不能大于100或者小于0');
			checkRate = rateHidden;
			$("#canCarryRate" + thisI).val(orgCanCarryRate);
			$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			return false;
		}else{//当钩稽比例大于 可钩稽比例 + 已钩稽比例时,报错
			if( checkRate < beforePeriodCarryRate){
				alert('钩稽比例不能小于前期钩稽比例');
				checkRate = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
				return false;
			}

			if( checkRate > allCanCarryRate*1){
				alert('钩稽比例不能超过可钩稽比例 + 已钩稽比例');
				checkRate = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			}else{
				$("#canCarryRate" + thisI).val(newCarryRate);
				$("#canCarryRateView" + thisI).html(newCarryRate);
			}

		}
	}else{
		alert('只能输入数字');
		checkRate = 0;
	}
}


//出库详细钩稽
function carryDetail(thisI,outstockId){
	var invoiceDetailId = $("#invoiceDetailId").val();  //开票详细id
	var sysYear = $("#sysYear").val();  //开票详细id
	var sysMonth = $("#sysMonth").val();  //开票详细id

	var salesId = $("#salesId").val(); //合同id
	var salesCode = $("#salesCode").val();  //合同编号
	var salesType = $("#salesType").val();  //合同类型
	var rObjCode = $("#rObjCode").val();  //合同类型
	var invoiceId = $("#invoiceId").val(); //开票
	var mainI = $("#mainI" + thisI).val(); //开票
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

//查看出库单据
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

//查看开票记录
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

//查看合同
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
		default : alert('不存在的合同类型');break;
	}
}

//双击隐藏/显示列表
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


//打印出库
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