function show_page(){
	window.location.reload();
}

//异步保存数据
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=productInCal",
			data : {
				"data" : thisDate
			},
			success : function(msg) {
				if (msg == 1) {
					alert('核算成功！');
				}else{
					alert("核算失败! ");
				}
			}
		});
	}else{
		alert('没有修改任何值');
		return false;
	}
}

//导出数据
function excelOut(){
	url = "?model=finance_stockbalance_stockbalance&action=productInCalExcelOut"
		+ '&productId=' + $("#productId").val()
		+ '&productNoBegin=' + $("#productNoBegin").val()
		+ '&productNoEnd=' + $("#productNoEnd").val()
		+ '&checkType=' + $("#checkType").val()
		+ '&isGroupByDept=' + $("#isGroupByDept").val()
						;
	window.open(url,"", "width=200,height=200,top=200,left=200");
}

//导入数据
function excelIn(){
	showThickboxWin("?model=finance_stockbalance_stockbalance&action=toProductInCalExcelInDept"
          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
}


/**
 * 浮点除法运算 arg3 为空时直接输出 不为空时根据id输出
 */
function FloatDivSix(arg1, arg2, arg3) {
	var value1 = $('#' + arg1).val();
	var value2 = $('#' + arg2).val();
	var t1 = 0, t2 = 0, r1, r2;
	if (value1 != "" && value2 != "") {
		var thisVal = accDiv(value1, value2, 6);
		if (arg3 == "") {
			return thisVal;
		} else {
			var newReturnMoney = moneyFormat2(thisVal,6);
			$('#' + arg3).val(thisVal);
			$('#' + arg3 + '_v').val(newReturnMoney);
		}
	} else {
		return false;
	}
}


//获取数据组成json数组
function getDataFun(){
	var tempProductId = '';   //临时产品id
	var tempSubPrice = 0;     //临时总金额
	var tempprice = 0;  //单价
	var tempOldSubPrice = 0;  //原始金额
	var tempPurchaserCode = '';  //部门

	var j = 0;

	var dataJson = {};

	//行数
	var countNum = $("#countNum").val();

	//循环够高json数组
	for(var i = 1 ; i <= countNum ; i++){
		//修改后的金额
		tempSubPrice = $('#subPrice' + i).val() * 1;
		//修改前的金额
		tempOldSubPrice = $('#oldSubPrice' + i).val() * 1;
		//如果不相等,则存入json数组内
		if( tempSubPrice != tempOldSubPrice ){
			j ++ ;
			tempProductId = $('#productId' + i).val();
			tempprice = $('#price' + i).val();
			tempPurchaserCode = $('#purchaserCode' + i).val();

			dataJson[i] = {
				"productId" : tempProductId ,
				"subPrice" : tempSubPrice ,
				"price" : tempprice,
				"purchaserCode" : tempPurchaserCode
			};
		}
	}

	if(j != 0){
		return dataJson;
	}else{
		return false;
	}
}