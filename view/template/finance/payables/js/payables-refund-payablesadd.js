var objTypeArr = [];// ҵ����������

$(function() {
	objTypeArr = getData('YFRK');

	// ��ѡ�ͻ�
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID'
		});
	}

	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS'
		});
	}
});

//ɾ���ӱ���
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
	countAll();
}

//�����ܽ��
function countAll(){
	var invnumber = $('#coutNumb').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i <= invnumber;i++){
		thisAmount = $('#money' + i).val() * 1;
		if(!isNaN(thisAmount)){
			allAmount += thisAmount;
		}
	}

	$("#amount").val(allAmount);
	$("#amount_v").val(moneyFormat2(allAmount));
}


//��֤���˿������
function checkMax(thisI){
	if(accSub($("#money" + thisI).val(),$("#orgMoney" + thisI).val(),2) > 0){
		alert('�˿���ܴ��ڸ�����');
		setMoney("money" + thisI,$("#orgMoney" + thisI).val());
	}
}