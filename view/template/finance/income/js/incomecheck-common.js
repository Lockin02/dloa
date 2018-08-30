

//����������Ⱦ
function buildInputSet(thisId,thisType){
	//��Ⱦһ��ƥ�䰴ť
	var thisObj = $("#" + thisId);

	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "˫������ѡ�����";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");

	//�����հ�ť
	var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");

	//��˫���¼�
	if(thisType == 'income'){
		//�ı�����¼�
		thisObj.dblclick(function(){
			selectIncome('incomecheck');
		});
		//��ѯ��ť���¼�
		$button.click(function(){
			//�����¼�
			selectIncome('incomecheck');
		});
		//��հ�ť���¼�
		$button2.click(function(){
			clearIncome();
		});
	}else{
		//�ı�����¼�
		thisObj.dblclick(function(){
			selectPayCon('incomecheck');
		});
		//��ѯ��ť���¼�
		$button.click(function(){
			selectPayCon('incomecheck');
		});
		//��հ�ť���¼�
		$button2.click(function(){
			clearContract();
		});
	}

	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true);
}

//��ͬ�����¼�
function selectPayCon(winName){
	showOpenWin("?model=contract_contract_receiptplan"
			+ "&action=selectPayCon"
			+ "&contractId="
			+ "&modeType=1" ,1,500,900, winName );
}

/**
 * ���ú�ͬ��������
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

//�����ͬ��Ϣ
function clearContract(){
	$("#contractId").val('');
	$("#contractCode").val('');
	$("#contractName").val('');
	$("#payConId").val('');
	$("#payConName").val('');
	$("#incomeId").val('');
	$("#incomeNo").val('');
}

//�򿪵��ѡ��
function selectIncome(winName){
	var contractId = $("#contractId").val();
	if(contractId!= "" && contractId!="0"){
		showOpenWin("?model=finance_income_income"
				+ "&action=selectPage"
				+ "&objId=" + contractId
				+ "&objType=KPRK-12",1,500,900, winName );
	}else{
		alert('ѡ�񵽿ǰ������ѡ���ͬ��Ϣ');
	}
}

/**
 * ���õ�������
 */
function setIncomeObj(obj){
	if(obj){
		$("#incomeId").val(obj.id);
		$("#incomeNo").val(obj.incomeNo);
	}
}

//�����
function clearIncome(){
	$("#incomeId").val('');
	$("#incomeNo").val('');
}