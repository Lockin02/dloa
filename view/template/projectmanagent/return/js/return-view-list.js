// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
$(function() {
	// ��Ʒ�嵥
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		url:'?model=projectmanagent_return_returnequ&action=listJson',
    	type:'view',
    	param:{
        	'returnId' : $("#returnId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '�ӱ�id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '����ִ������',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '�˻�����',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
});
