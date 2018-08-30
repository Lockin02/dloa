function sub() {
	var dispose = $("input[name='deduct[deductDispose]']:checked").val();
	if (dispose == undefined) {
		alert("��ѡ����ʽ");
		return false;
	}

	//�������Ϳۿ�����֤
	var objGrid = $("#checkTable");
	var checkMoney = 0;
	var deductMoney = $("#deductMoney").val();
	var checkMoneyCols = objGrid.yxeditgrid("getCmpByCol","checkMoney");
	if (checkMoneyCols.length > 0) {
		checkMoneyCols.each(function(){
			checkMoney = accAdd(checkMoney,this.value,2);
		});
		if(checkMoney*1 != deductMoney*1){
			alert('���������ۿ����');
			return false;
		}
	}

	return true;
}

$(function(){
	initIncomeCheck();
});

//��Ⱦ��������ӱ�
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'deduct[incomeCheck]',
		title : '�ؿ����',
		param : { 'incomeId' : $("#id").val(),'incomeType' : 1 },
		tableClass : 'form_in_table',
		isAdd : false,
		event : {
			'reloadData' : function(e, g, data){
				if(!data){
//					objGrid.hide();
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'incomeId',
			name : 'incomeId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : 'incomeType',
			name : 'incomeType',
			type : 'hidden',
			value : 1
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '��ͬ���',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��������id',
			name : 'payConId',
			type : 'hidden'
		},  {
			display : 'δ������',
			name : 'unIncomMoney',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '���κ������',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money',
			event: {
				"blur" : function(v){
					var data = v.data;
					var rowNum = data.rowNum;
					var rowData = data.rowData;
					var inputVal = $("#checkTable_cmp_checkMoney"+rowNum).val();
					var unIncomMoney = $("#checkTable_cmp_unIncomMoney"+rowNum).val();
					console.log(Number(inputVal) > unIncomMoney);// �༭ҳ����Ҫ����
				}
			}
		}, {
			display : '��������',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	//���ظ�������ѡ��ť
	objGrid.find("tr:first td").append("<input type='button' value='ѡ�񸶿�����' class='txt_btn_a' style='margin-left:10px;' onclick='selectPayCon();'/>");
}

//ѡ���ͬ��������
function selectPayCon(){
	showOpenWin("?model=contract_contract_receiptplan"
			+ "&action=selectPayCon"
			+ "&contractId="
			+ $("#contractId").val() ,1,500,900,$("#id").val());
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
					alert('��ͬ��'+ rows[i].contractCode + "���ĸ���������"+ rows[i].paymentterm +"("+ rows[i].payDT +" "+ moneyFormat2(rows[i].money) +")���Ѿ������ڻؿ�����б���,�����ظ�ѡ��" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}

		var unIncomMoney = rows[i].unIncomMoney_name;
		unIncomMoney = (unIncomMoney == undefined)? 0 : unIncomMoney.replaceAll(",","");

		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractId",rows[i].contractId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractCode",rows[i].contractCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractName",rows[i].contractName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConName",rows[i].paymentterm);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"checkDate",$("#thisDate").val());
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"unIncomMoney",Number(unIncomMoney));
	}
}