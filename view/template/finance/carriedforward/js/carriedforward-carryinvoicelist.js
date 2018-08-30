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
		msg = ' (注 ：如果下一期存在钩稽记录，则本期修改钩稽记录后则会删除下一期的对应的钩稽记录。)';
		$("#addMsg").html(msg);
	}

	if(ids != 'none'){
		hookedArr = ids.split(",");
		for(var i = 0 ;i < hookedArr.length ; i++){
			$("#" + hookedArr[i]).attr("checked",true);
		}
	}
})

//异步保存数据
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
					alert("钩稽失败");
				}else if(msg != 'none'){
					alert('钩稽成功');
					hookedArr = msg.split(",");
					updateCheckRate(thisData);
					$("#ids").val(msg);
				}else{
					alert('钩稽成功');
					hookedArr = [];
					updateCheckRate(thisData);
					$("#ids").val('');
				}
			}
		});
	}else{
		alert('没有选中值');
		return false;
	}
}

/**
 * 选择方法，若是本期，选择被其数据获取函数getDataFun
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

	//临时存储变量
	var tempKey = "";
	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();
		tempId = this.id;
		invoiceObj = $("#invoice" + tempId); //对应开票对象
		saleObj = $("#sale" + tempId);  //对应销售合同对象
		saleCodeObj = $("#saleCode" + tempId); //对应销售合同编号
		saleTypeObj = $("#objType" + tempId); //对应合同类型
		rateObj = $("#rate" + tempId);  //钩稽比例
		hiddenRateObj = $("#hiddenRate" + tempId);  //已钩稽比例
		outStockIdObj = $("#outStockId" + tempId);  //出库单据id对象
		outStockCodeObj = $("#outStockCode" + tempId);  //出库单据编号

		if($(this).attr('checked') == true){
			if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey) == -1){//新钩稽字符串处理
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
			}else{//修改字符串处理
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
		}else{//删除字符串处理
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

//获取数据组成json数组,非本期获取数据所使用方法
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

	//临时存储变量
	var tempKey = "";

	var rateDiffer;
	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();
		tempId = this.id;
		invoiceObj = $("#invoice" + tempId); //对应开票对象
		saleObj = $("#sale" + tempId);  //对应销售合同对象
		saleCodeObj = $("#saleCode" + tempId); //对应销售合同编号
		saleTypeObj = $("#objType" + tempId); //对应合同类型
		rateObj = $("#rate" + tempId);  //钩稽比例
		hiddenRateObj = $("#hiddenRate" + tempId);  //已钩稽比例
		outStockIdObj = $("#outStockId" + tempId);  //出库单据id对象
		outStockCodeObj = $("#outStockCode" + tempId);  //出库单据编号
		thisCarryObj = $("#thisCarry" + tempId); //本月钩稽金额
		beforeCarryObj = $("#beforeCarry" + tempId); //原钩稽金额
		rateDiffer = accSub(rateObj.val()*1, beforeCarryObj.val()*1);//新钩稽数和已钩稽数差额

		if($(this).attr('checked') == true){
			if(rateDiffer != 0){
				if(hookedArr.indexOf( invoiceObj.val() + '-' + tempKey) == -1 || thisCarryObj.val() == "" || thisCarryObj.val()*1 == 0){//新钩稽字符串处理
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
				}else{//修改字符串处理
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
		}else{//删除字符串处理
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
			alert('不能大于100或者小于0');
			thisObj.value = 0;
		}

		if(isSame == 0){
			thisCarry = $("#thisCarry" + rowId).val()*1; //本月钩稽金额
			beforeCarry = $("#beforeCarry" + rowId).val()*1; //原钩稽金额
			if( thisObj.value*1 <= beforeCarry ){
				alert('输入数字只能大于已钩稽金额');
				thisObj.value = beforeCarry + thisCarry;
				return false;
			}
		}
	}
}

/**
 * 点击选择后将对应的百分比变成100
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
 * 钩稽计算
 * @param {传入合同id} objId
 */
function countHooked(objId,mainId,thisType){
	//计算金额结算
	var allHooked = 0;
	checkObj = $(".countCheck"+ objId);
	allHooked = sumAllHookedMoney(checkObj);

	//判断，如果钩稽金额大于小计金额，则进行回滚
	if( allHooked > $("#count" + objId).val()*1){
		if(thisType == 'input'){//如果是输入框，则回写输入框值
			alert('钩稽金额大于出库金额，请重新填写');
			$("#rate" + mainId).val($("#hiddenRate" + mainId).val());
		}else{//否则取消选择
			alert('钩稽金额大于出库金额，请先填写钩稽比例或调整合同内的其他钩稽记录');
			$("#"+ mainId).attr('checked',false);
			setRate(mainId);
		}
		return false;
	}

	$("#hooked" + objId).val(allHooked);
	$("#hookedView" + objId).html(moneyFormat2(allHooked));
}


/**
 * 传选择对象返回计算金额
 * @param {选择对象} thisData
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
 * 钩稽成功后，将新增和更新数据的百分比隐藏域进行更新
 */
function updateCheckRate(thisData){
	thisObj = (thisData);

	//刷新更新记录
	for(var i = 0 ; i < thisObj["updateIds"].length ; i++){
		thisId = thisObj["updateIds"][i];
		$("#hiddenRate" + thisId).val($("#rate" + thisId).val());
	}

	//刷新新建记录
	for(var i = 0 ; i < thisObj["newIds"].length ; i++){
		thisId = thisObj["newIds"][i];
		$("#hiddenRate" + thisId).val($("#rate" + thisId).val());
	}
}