//�ı�ֵ������֤
function checkThisNum(thisObj,thisI){
	//�ɹ���Դ����
	var orgCanCarryRate = $("#orgCanCarryRate" + thisI).val()*1;
	//�ѹ�������
	var rateHidden = $("#rateHidden" + thisI ).val()*1;

	//������������
	var hookType = $("#hookType" + thisI);
	if(!isNaN(thisObj.value)){
		//�ɹ�������
		var canCarryRate = $("#canCarryRate" + thisI).val()*1;
		//�ܿɹ�������
		var allCanCarryRate = $("#allCanCarryRate" + thisI ).val()*1;
		//ǰ�ڹ�������
		var beforePeriodCarryRate = $("#beforePeriodCarryRate" + thisI ).val()*1;
		//�¿ɹ�������
		var newCarryRate = accSub(allCanCarryRate,thisObj.value);

		if( thisObj.value > 100 || thisObj.value < 0){
			alert('�����������ܴ���100��С��0');
			thisObj.value = rateHidden;
			$("#canCarryRate" + thisI).val(orgCanCarryRate);
			$("#canCarryRateView" + thisI).html(orgCanCarryRate);
			hookType.val(0);
		}else{
			if( thisObj.value > allCanCarryRate){
				alert('�����������ܴ��ڿɹ�������');
				thisObj.value = rateHidden;
				$("#canCarryRate" + thisI).val(orgCanCarryRate);
				$("#canCarryRateView" + thisI).html(orgCanCarryRate);
				hookType.val(0);
			}else{
				canCarryRate = orgCanCarryRate - thisObj.value + rateHidden;
				$("#canCarryRate" + thisI).val(canCarryRate);
				$("#canCarryRateView" + thisI).html(canCarryRate);
				if(rateHidden < thisObj.value){//�������Ӳ���
					hookType.val(1);
				}else if(rateHidden > thisObj.value && thisObj.value > 0 ){//�����༭����
					hookType.val(2);
				}else if(rateHidden > thisObj.value && thisObj.value == 0 ){//����ɾ������
					hookType.val(3);
				}
			}
		}
	}else{
		alert('ֻ����������');
		thisObj.value = rateHidden;
		$("#canCarryRate" + thisI).val(orgCanCarryRate);
		$("#canCarryRateView" + thisI).html(orgCanCarryRate);
		hookType.val(0);
	}
}

//���㹳����Ϣ
function countHooked(thisI){
	//�ܳɱ�
	var subCost = $("#subCost" + thisI).val()*1;
	//��������
	var  carryRate = $("#rate" + thisI).val()*1;

	var hookMoney = accMul(subCost,accDiv(carryRate,100,2),2);

	$("#hookMoney" + thisI).val(hookMoney);
	$("#hookMoney" + thisI + "_v").val(moneyFormat2(hookMoney));

	//�����ܹ���������д���б���
	var allHookMoney = sumAllHookedMoney($(".carryRateClass"));
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
		if($(this).val()*1 != 0){
			j = i + 1;
			var rateObj = $("#rate" + j);
			var thisMoneyObj = $("#subCost" + j);

			allHooked = accAdd(allHooked,accMul( accDiv(rateObj.val()*1,100,2) ,thisMoneyObj.val() * 1 ,2),2);
		}
	})
	return allHooked;
}