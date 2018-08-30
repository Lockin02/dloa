//����������
var billTypeArr = [];

$(function(){
	//��ȡ��Ʊ����
	billTypeArr = getBillType();
	//���⴦����
	billTypeArr = arrChange(billTypeArr);

	//��Ⱦ��Ʊ��ϸ
	$("#innerTable").yxeditgrid({
		objName : 'expensedetail[expenseinv]',
		title : '��Ʊ��ϸ',
		tableClass : 'form_in_table',
		url : '?model=finance_expense_expenseinv&action=listJson',
		param : { 'BillDetailID' : $("#ID").val() },
		event : {
			removeRow : function(t, rowNum, rowData) {

			}
		},
		colModel : [{
			display : 'ID',
			name : 'ID',
			type : 'hidden'
		}, {
			display : '��Ʊ����',
			name : 'BillTypeID',
			validation : {
				required : true
			},
			tclass : 'select',
			type : 'select',
			options : billTypeArr
		}, {
			display : '��Ʊ���',
			name : 'Amount',
			validation : {
				required : true
			},
			type : 'money',
			tclass : 'txt'
		}, {
			display : '��Ʊ����',
			name : 'invoiceNumber',
			tclass : 'txt',
			value : 1
		}]
	})
});

//ת��һ������
function arrChange(billTypeArr){
	var newArr = [];
	var innerArr;
	for(var i = 0; i < billTypeArr.length ; i++){
		innerArr = {"name" : billTypeArr[i].name , "value" : billTypeArr[i].id};
		newArr.push(innerArr);
	}
	return newArr;
}

//�ж���ѡ�����Ƿ����
function checkCanSel(thisObj){
	var newCostObj = $("#newCostTypeID");
	var childrenObjs = newCostObj.find("option[parentId='"+ thisObj.value +"']");
	if(childrenObjs.length > 0){
		alert('������������ͣ����ܽ���ѡ��');
		var costTypeId = $("#CostTypeID").val();
		newCostObj.val(costTypeId);
		return false;
	}else{
		//�����и�ֵ
		var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
		var newMainType = newCostObj.find("option:selected").attr("parentName");
		$("#mainType").val(newMainTypeId);
		$("#mainTypeName").val(newMainType);
	}
	return true;
}


//���淢Ʊ���ͱ��
function checkform(){
	//�����֤
	var costMoney = $("#costMoney").val();
	if(costMoney*1 == 0 || costMoney == ""){
		alert('���ý���Ϊ0���߿�');
		return false;
	}

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

	//��ǰ���ڷ�������
	var orgCostTypes = $("#orgCostTypes").val();
	var orgCostTypesArr = orgCostTypes.split(',');

	//�ж���ֵ�Ƿ��Ѵ���
//	if(jQuery.inArray(newCostTypeId,orgCostTypesArr) != '-1' && newCostTypeId != costTypeId){
//		alert('�����������Ѵ��ڡ�'+newCostType+'��,�����޸ĳɸ�����Ϣ');
//		return false;
//	}

	//��ǰ���ڸ���������
	var mainTypes = $("#mainTypes").val();
	var mainTypesArr = mainTypes.split(',');

	//�ӱ����
	var thisGrid = $("#innerTable");
	var cmps = thisGrid.yxeditgrid("getCmpByCol", "Amount");
	cmps.each(function(i,n) {
		//���㵱ǰ����
		costMoney = accSub(costMoney,this.value,2);
	});
	//�����Ϊ0���򵥾ݽ�һ��
	if(costMoney != 0){
		alert('���ý���뷢Ʊ��һ��');
		return false;
	}

	if(confirm('ȷ���޸���')){
		return true;
	}else{
		return false;
	}
}