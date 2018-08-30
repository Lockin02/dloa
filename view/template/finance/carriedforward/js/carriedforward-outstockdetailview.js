//文本值输入验证
function checkThisNum(thisObj,thisI){
	//可钩稽源比例
	var orgCanCarryRate = $("#orgCanCarryRate" + thisI).val()*1;
	//已钩稽比例
	var rateHidden = $("#rateHidden" + thisI ).val()*1;

	//钩稽动作对象
	var hookType = $("#hookType" + thisI);
	if(!isNaN(thisObj.value)){
		//可钩稽比例
		var canCarryRate = $("#canCarryRate" + thisI).val()*1;
		//总可钩稽比例
		var allCanCarryRate = $("#allCanCarryRate" + thisI ).val()*1;
		//前期钩稽比例
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + thisI ).val()*1;
		//新可钩稽比例
		var newCarryRate = accSub(allCanCarryRate,thisObj.value);

		if( thisObj.value > 100 || thisObj.value < 0){
			alert('钩稽比例不能大于100或小于0');
			thisObj.value = rateHidden;
			$("#canCarryRate" + thisI).val(orgCanCarryRate);
			$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			hookType.val(0);
		}else{
			if( thisObj.value > allCanCarryRate){
				alert('钩稽比例不能大于可钩稽比例');
				thisObj.value = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
				hookType.val(0);
			}else{
				canCarryRate = orgCanCarryRate - thisObj.value + rateHidden;
				$("#canCarryRate" + thisI).val(canCarryRate);
				$("#canCarryRateView" + thisI).html(canCarryRate);
				if(rateHidden < thisObj.value){//钩稽增加操作
					hookType.val(1);
				}else if(rateHidden > thisObj.value && thisObj.value > 0 ){//钩稽编辑操作
					hookType.val(2);
				}else if(rateHidden > thisObj.value && thisObj.value == 0 ){//钩稽删除操作
					hookType.val(3);
				}
			}
		}
	}else{
		alert('只能输入数字');
		thisObj.value = rateHidden;
		$("#canCarryRate" + thisI).val(orgCanCarryRate);
		$("#canCarryRateView" + thisI).html(orgCanCarryRate);
		hookType.val(0);
	}
}

//计算钩稽信息
function countHooked(thisI){
	//总成本
	var subCost = $("#subCost" + thisI).val()*1;
	//钩稽比例
	var  carryRate = $("#rate" + thisI).val()*1;

	var hookMoney = accMul(subCost,accDiv(carryRate,100,2),2);

	$("#hookMoney" + thisI).val(hookMoney);
	$("#hookMoney" + thisI + "_v").val(moneyFormat2(hookMoney));

	//计算总钩稽金额，并且写道列表中
	var allHookMoney = sumAllHookedMoney($(".carryRateClass"));
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
		if($(this).val()*1 != 0){
			j = i + 1;
			var rateObj = $("#rate" + j);
			var thisMoneyObj = $("#subCost" + j);

			allHooked = accAdd(allHooked,accMul( accDiv(rateObj.val()*1,100,2) ,thisMoneyObj.val() * 1 ,2),2);
		}
	})
	return allHooked;
}