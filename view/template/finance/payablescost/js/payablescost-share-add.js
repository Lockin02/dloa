//��ʼ������
$(document).ready(function() {
	/*//���÷�̯����
	shareObjArr = getData('CWFYFT');

	//���ص�һ�ж�������
	addDataToSelect(shareObjArr, 'shareType1');

	//���ط�̯������Ⱦ
	initShare();

	//��ȡ������Ŀ
	feeTypeArr = getFeeType();
	//��ʼ������
	initFeeType();*/
	
	//������Ϣ
	$("#payDetail").payDetailGrid({
		objName:'payablescost[detail]'
			});
	initListCount();
});
/**
 * 
 * �ύǰ����
 * @returns {Boolean}
 */
function checkform(){
	//��̯�ܽ��
	var payDetailMoneyHidden=$("#payDetailMoneyHidden").val()*1;
	
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
	var payMoney=$("#payMoney").val()*1;

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
//��ʼ���ϼ���
function initListCount(){
	var $tbody = $("#payDetail").find('tbody');
	$tbody.after("<tr class='tr_count'><td colspan='2'>�ϼ�</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>" 
		+'<span id="payDetailMoney">0.00</span>'
		+"</td>" 
		+ "</tr>");
}