//���淢Ʊ���ͱ��
function saveBillType(){
	//�Ķ�ǰ������
	var billTypeId = $("#BillTypeID").val();

	//������
	var newBillTypeId = $("#newBillTypeID").val();
	var newBillType = $("#newBillTypeID").find("option:selected").text();

	//��Ʊ����
	var BillNo = $("#BillNo").val();

	if(billTypeId == newBillTypeId){
		alert('û�б����Ӧ����');
		return false;
	}

	//��ǰ���ڷ�Ʊ����
	var orgBillTypes = $("#orgBillTypes").val();
	var orgBillTypesArr = orgBillTypes.split(',');

	//�Ƿ��Ǻϲ�
	var isMerge = 0;

	//�ж���ֵ�Ƿ��Ѵ���
	if(jQuery.inArray(newBillTypeId,orgBillTypesArr) != '-1'){
		if(!confirm('��Ʊ��Ϣ���Ѵ��ڡ�'+newBillType+'��,�޸ĺ�Ὣ��Ӧ��Ʊ���ϲ����������²�֣�ȷ���޸���')){
			return false;
		}
		isMerge = 1;
	}

	//�첽�޸�
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expenseinv&action=editBillTypeID",
	    data: {"billTypeId" : billTypeId , 'newBillTypeId' : newBillTypeId , 'BillNo' : BillNo},
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				alert('���³ɹ�');
				parent.show_pageInv(billTypeId,newBillTypeId,newBillType,isMerge);
				parent.tb_remove();
	   	    }else{
				alert('�޸�ʧ��');
	   	    }
		}
	});
}
