$(document).ready(function() {
	$("#rentalPropertyCode").trigger("change"); //�����⳵�����¼�

	//�Ƿ�ʹ���Ϳ�֧��
	$("#isCardPay").val($("#isCardPayVal").val());


	$("#feeInfo").yxeditgrid({
		objName : 'register[fee]',
		isAddAndDel : false,
		url : '?model=outsourcing_vehicle_registerfee&action=listJson',
		param : {
			dir : 'ASC',
			registerId : $("#id").val()
		},
		colModel : [{
			name : 'contractId',
			display : '��ͬID',
			type : 'hidden'
		},{
			name : 'orderCode',
			display : '��ͬ���',
			type : 'hidden'
		},{
			name : 'feeName',
			display : '��������',
			type : 'statictext',
			align : 'left',
			width : '25%'
		},{
			name : 'feeName',
			display : '�������ƺ�̨��',
			type : 'hidden'
		},{
			name : 'feeAmount',
			display : '���ý��',
			type : 'statictext',
			width : '15%',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'yesOrNo',
			display : '�Ƿ�ѡ��',
			type : 'checkbox',
			checkVal : 1,
			width : '10%'
		},{
			name : 'feeAmount',
			display : '���ý���̨��',
			type : 'hidden'
		},{
			name : 'remark',
			display : '��  ע',
			type : 'statictext',
			align : 'left',
			width : '50%'
		},{
			name : 'remark',
			display : '��ע��̨��',
			type : 'hidden'
		}]
	});
});

//ֱ���ύ
function toSubmit(type){
	if(checkData() == 'pass'){
		switch(type){
			case 'sub':
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=edit&isSub=1";
				$('#form1').submit();
				break;
			default:
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=edit";
				$('#form1').submit();
				break;
		}
	}
}