$(document).ready(function() {
	validate({
		"money" : {
			required : true
		}
	});

	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'uninvoice'
	});
});

//����֤
function checkform(){
	//����¼����
	var thisMoney = $("#money").val()*1;
	var canUninvoiceMoney = $("#canUninvoiceMoney").val()*1;//��¼����Ʊ���
	var uninvoiceMoney = $("#uninvoiceMoney").val()*1;//��¼����Ʊ���
	var isRed = $("#isRed").val(); //�Ƿ����

	if(canUninvoiceMoney <= 0 && isRed == '0'){
		alert('��¼����Ʊ���Ϊ' + canUninvoiceMoney + '������¼����Ʊ������0ʱ���ſ�������¼��!');
		return false;
	}

	if(canUninvoiceMoney < thisMoney && isRed == '0'){
		alert('��¼����Ʊ���Ϊ' + canUninvoiceMoney + '�����ܴ��ڿ�¼����Ʊ���!');
		return false;
	}

	if(uninvoiceMoney < thisMoney && isRed == '1'){
		alert('���ֲ���Ʊ���ֻ��С�ڻ������¼����Ʊ���' + uninvoiceMoney);
		return false;
	}

	if(thisMoney <= 0){
		alert('����Ʊ���ֻ����������������ȡ��ԭ����Ʊ���Ƿ����һ��ѡ���ǡ����ɣ�');
		return false;
	}

	if(!confirm('ȷ�ϱ���ò���Ʊ�����')){
		return false;
	}

	return true;
}