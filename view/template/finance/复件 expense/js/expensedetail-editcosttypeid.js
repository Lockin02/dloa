//�ж���ѡ�����Ƿ����
function checkCanSel(thisObj){
	var childrenObjs = $("#newCostTypeID").find("option[parentId='"+ thisObj.value +"']");
	if(childrenObjs.length > 0){
		alert('������������ͣ����ܽ���ѡ��');
		var costTypeId = $("#CostTypeID").val();
		$("#newCostTypeID").val(costTypeId);
		return false;
	}
	return true;
}


//���淢Ʊ���ͱ��
function saveCostType(){
	//�Ķ�ǰ������
	var costTypeId = $("#CostTypeID").val();
	var mainType = $("#mainType").val();
	var mainTypeName = $("#mainTypeName").val();
	//������
	var newCostObj = $("#newCostTypeID");
	var newCostTypeId = newCostObj.val();
	var newCostType = newCostObj.find("option:selected").text();
	var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
	var newMainType = newCostObj.find("option:selected").attr("parentName");

	//��Ʊ����
	var BillNo = $("#BillNo").val();

	if(costTypeId == newCostTypeId){
		alert('û�б����Ӧ����');
		return false;
	}

	//��ǰ���ڷ�������
	var orgCostTypes = $("#orgCostTypes").val();
	var orgCostTypesArr = orgCostTypes.split(',');

	//�ж���ֵ�Ƿ��Ѵ���
	if(jQuery.inArray(newCostTypeId,orgCostTypesArr) != '-1'){
		alert('�����������Ѵ��ڡ�'+newCostType+'��,�����޸ĳɸ�����Ϣ');
		return false;
	}

	//��ǰ���ڸ���������
	var mainTypes = $("#mainTypes").val();
	var mainTypesArr = mainTypes.split(',');

	//�ж��Ƿ�Ҫˢ��
	var isReload = 0;

	//�ж���ֵ�Ƿ��Ѵ���
	if(jQuery.inArray(newMainTypeId,mainTypesArr) == '-1'){
		isReload = 1;
	}

	//�첽�޸�
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expensedetail&action=editCostTypeID",
	    data: {
	    	"costTypeId" : costTypeId ,
	    	'newCostTypeId' : newCostTypeId ,
	    	'BillNo' : BillNo,
	    	'newMainTypeId' : newMainTypeId,
	    	'newMainType' : newMainType
	    },
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				alert('���³ɹ�');
				parent.show_pageDetail();
				parent.tb_remove();
	   	    }else{
				alert('�޸�ʧ��');
	   	    }
		}
	});
}