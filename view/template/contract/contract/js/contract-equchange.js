
// ����ȷ�ϱ��
$(function() {
	equConfig.type = "change";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}
	var newEquRowNum = rowNum*1+1;
	getNoGoodsProducts(newEquRowNum);	
	
	// �Ƿ���ʾȷ�ϱ����ť,Ŀǰ����Ȩ�޷�������ϱ������,����ѡ��ȷ�ϱ��,����ȷ�Ϻ������ύ����
	if($("#dealStatus").val() != '1' && $("#dealStatus").val() != '3'){
		$(":submit").eq(1).css("display","none");
	}
});
//
function cc(){
     validate({
		"changeReason" : {
			required : true
		}

	});
}


// ֱ���ύ����
function toAddAudit() {
//	cc();
	/**
	 * �ж��Ƿ����Ժ�ͬ���,����Ǻ�ͬ�����,fromSalesΪ1,������ڱ��ҳ���ύ������fromSalesΪ0 2017-01-05
	 * fromSalesΪ1: ������Ҫ���в���ȷ�ϳɱ�
	 * fromSalesΪ0: ����ֻ�����۲��߸�����Ҫȷ��,�����ֱ����ԭ����
	 **/
	var fromSales = '&fromSales=0';
	if($("#dealStatus").val() != '1' && $("#dealStatus").val() != '3'){
		fromSales = '&fromSales=1';
	}
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equChange&act=audit"+fromSales;
}

//�ύ����ȷ��,�������� --2015.10.27��Ȩ��������󣬵��ȷ�ϱ����ť������Ϊ�����ϱ��--����ȷ��--������
function toNoAudit() {
//	cc();
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equChange&act=noaudit";
}