$(document).ready(function() {

	$("#equInfo").yxeditgrid({
		objName : 'encryption[equ]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		param : {
			parentId : $("#id").val(),
		},

		isAdd : false,

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��ͬ�����嵥�ӱ�ID',
			name : 'equId',
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
			name : 'pattern',
			type : 'statictext'
		},{
			display : '����ͺ����������',
			name : 'pattern',
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
			name : 'needNum',
			type : 'statictext'
		},{
			display : '�����������������',
			name : 'needNum',
			type : 'hidden'
		},{
			display : '��ִ������',
			name : 'finshNum',
			type : 'statictext'
		},{
			display : '��ִ���������������',
			name : 'finshNum',
			type : 'hidden'
		},{
			display : '��������������',
			name : 'produceNum',
			tclass : 'txtshort',
			process : function($input ,rowData) {
				var produceNum = rowData.needNum - rowData.finshNum;
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
		}]
	});
});

//���������Ч��
function checkData() {
	var $produceNumObj = $("#equInfo").yxeditgrid("getCmpByCol" ,"produceNum");
	for (var i = 0 ;i < $produceNumObj.length ;i++) {
		if ($produceNumObj[i].value <= 0) {
			return false;
		}
	}
	return true;
}

//ֱ���´�
function toSubmit(){
	document.getElementById('form1').action="?model=stock_delivery_encryption&action=edit&issued=true";
}