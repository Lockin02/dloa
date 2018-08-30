

//构建填入渲染
function buildInputSet(thisId,thisType){
	//渲染一个匹配按钮
	var thisObj = $("#" + thisId);

	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "双击弹出选择界面";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");

	//添加清空按钮
	var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");

	//绑定双击事件
	if(thisType == 'income'){
		//文本域绑定事件
		thisObj.dblclick(function(){
			selectIncome('incomecheck');
		});
		//查询按钮绑定事件
		$button.click(function(){
			//弹出事件
			selectIncome('incomecheck');
		});
		//清空按钮绑定事件
		$button2.click(function(){
			clearIncome();
		});
	}else{
		//文本域绑定事件
		thisObj.dblclick(function(){
			selectPayCon('incomecheck');
		});
		//查询按钮绑定事件
		$button.click(function(){
			selectPayCon('incomecheck');
		});
		//清空按钮绑定事件
		$button2.click(function(){
			clearContract();
		});
	}

	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true);
}

//合同弹出事件
function selectPayCon(winName){
	showOpenWin("?model=contract_contract_receiptplan"
			+ "&action=selectPayCon"
			+ "&contractId="
			+ "&modeType=1" ,1,500,900, winName );
}

/**
 * 设置合同付款内容
 * @return {Boolean}
 */
function setDatas(obj){
	if(obj){
		$("#contractId").val(obj.contractId);
		$("#contractCode").val(obj.contractCode);
		$("#contractName").val(obj.contractName);
		$("#payConId").val(obj.id);
		$("#payConName").val(obj.paymentterm);
	}
}

//清除合同信息
function clearContract(){
	$("#contractId").val('');
	$("#contractCode").val('');
	$("#contractName").val('');
	$("#payConId").val('');
	$("#payConName").val('');
	$("#incomeId").val('');
	$("#incomeNo").val('');
}

//打开到款单选择
function selectIncome(winName){
	var contractId = $("#contractId").val();
	if(contractId!= "" && contractId!="0"){
		showOpenWin("?model=finance_income_income"
				+ "&action=selectPage"
				+ "&objId=" + contractId
				+ "&objType=KPRK-12",1,500,900, winName );
	}else{
		alert('选择到款单前，请先选择合同信息');
	}
}

/**
 * 设置到款数据
 */
function setIncomeObj(obj){
	if(obj){
		$("#incomeId").val(obj.id);
		$("#incomeNo").val(obj.incomeNo);
	}
}

//清楚入款单
function clearIncome(){
	$("#incomeId").val('');
	$("#incomeNo").val('');
}