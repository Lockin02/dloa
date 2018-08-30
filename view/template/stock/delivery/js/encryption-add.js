$(document).ready(function() {

	$("#equInfo").yxeditgrid({
		objName : 'encryption[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		param : {
			equIds : $("#equIds").val(),
		},

		isAdd : false,

		colModel : [{
			display : '������Id',
			name : 'id',
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productCode',
			type : 'statictext'
		},{
			display : '���ϱ�����������',
			name : 'productCode',
			type : 'hidden'
		},{
			display : '��������',
			name : 'productName',
			type : 'statictext'
		},{
			display : '�����������������',
			name : 'productName',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '����ͺ�',
			name : 'productModel',
			type : 'statictext'
		},{
			display : '����ͺ����������',
			name : 'productModel',
			type : 'hidden'
		},{
			display : '��λ����',
			name : 'unitName',
			type : 'statictext'
		},{
			display : '��λ�������������',
			name : 'unitName',
			type : 'hidden'
		},{
			display : '��������',
			name : 'number',
			type : 'statictext'
		},{
			display : '�����������������',
			name : 'number',
			type : 'hidden'
		},{
			display : '��ִ������',
			name : 'encryptionNum',
			type : 'statictext'
		},{
			display : '��ִ���������������',
			name : 'encryptionNum',
			type : 'hidden'
		},{
			display : '��������������',
			name : 'produceNum',
			tclass : 'txtshort',
			process : function($input ,rowData) {
				var produceNum = rowData.number - rowData.encryptionNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('���ܴ��ڣ���������-��ִ��������');
						$input.val(produceNum).focus();
					}
				});
				$input.val(produceNum);
			},
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '������Ԥ�����ʱ��',
			name : 'planFinshDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			},
			readonly : true
		},{
			display : '��ע',
			name : 'remark'
		},{
			display : 'license',
			name : 'license',
			type : 'hidden'
		}]
	});

	validate({
		"issueDate" : {
			required : true
		}
	});
});

//ֱ���´�
function toSubmit(){
	document.getElementById('form1').action="?model=stock_delivery_encryption&action=add&issued=true";
}