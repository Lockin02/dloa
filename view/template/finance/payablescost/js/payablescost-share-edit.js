//��ʼ������
$(document).ready(function() {
	
	//add chenrf 20130620
	//��ʼ����̯�ϼƽ��
	var money=$("#payMoney").val();
	changeMoney(money);
	
	var payablesapplyId=$("#payapplyId").val();
	//������Ϣ
		$("#payDetail").payDetailGrid({
			objName:'payablescost[detail]',
			url:'?model=finance_payablescost_payablescost&action=listJson',
			type:'edit',
			param : {
				payapplyId : payablesapplyId
			}
		});
		
	/*//���÷�̯����
	shareObjArr = getData('CWFYFT');

	//���ط�̯������Ⱦ
	initShare();

	//��ȡ������Ŀ
	feeTypeArr = getFeeType();
	//��ʼ������
	initFeeType();

	//������ֵ
	initFeeTypeEdit();
	//���¼�����
	reCalMoney();
});

//��������������ֵ
function initFeeTypeEdit(){
	//����
	var detailNo = $("#detailNo").val();

	for(var i = 1;i <= detailNo ; i++){
		setSelect('feeType' + i);
	}*/
})

function checkform(){
	//��̯�ܽ��
	var payDetailMoneyHidden=Number($("#payDetailMoneyHidden").val());

	//��̯������Ϊ��
	var shareObjNameArr= $("input[id^='payDetail_cmp_shareObjName']");
	for(i=0;i<shareObjNameArr.length;i++){
		if($(shareObjNameArr[i]).val()==''){
			alert('����д��̯����');
			return false;
		}
	}
//	��������
	var shareTypeArr=$("input[id^='payDetail_cmp_feeType']");
	for(i=0;i<shareTypeArr.length;i++){
		if($(shareTypeArr[i]).val()==''){
			alert('����д��������');
			return false;
		}
		
	}	
	//������
	var payMoney=$("#payapplyMoney").val()*1;

	if(payDetailMoneyHidden==0){
		alert('����д��̯��ϸ�б����̯���ϼƲ���Ϊ0');
		return false;
	}
	if(payMoney<payDetailMoneyHidden){
		alert('���󣡷�̯�ϼƽ��ܴ���������');
		return false;
	}
	if(payMoney>payDetailMoneyHidden){
		alert('�����벹���̯���');
		return false;
	}
	var shareMoneyArr=$("input[id^='payDetail_cmp_shareMoney']");
	for(i=0;i<shareMoneyArr.length;i++){
		if($(shareMoneyArr[i]).val()*1==0){
			alert('��̯����Ϊ0');
			return false;
		}
		
	}
}

