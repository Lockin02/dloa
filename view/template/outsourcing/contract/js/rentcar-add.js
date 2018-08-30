$(document).ready(function() {
	setSignCompany(); //ǩԼ��˾������Ⱦ

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		isFristRowDenyDel : true,
		colModel : [{
			name : 'carModelCode',
			display : '����',
			width : '15%',
			type : 'select',
			datacode : 'WBZCCX' // �����ֵ����
		},{
			name : 'carNumber',
			display : '���ƺ�',
			width : '10%',
			validation : {
				required : true
			},
			process : function ($input) {
				$input.change(function () {
					if ($.trim($(this).val()) != '') {
						$.ajax({
							type : 'POST',
							url : '?model=outsourcing_contract_vehicle&action=checkByCarNumber',
							data : {
								carNumber : $.trim($(this).val())
							},
							async : false,
							success : function (data) {
								if (data == 'true') {
									alert('�ó��ƺ��Ѿ����ں�ͬ��');
								}
							}
						});
					}
				});
			}
		},{
			name : 'driver',
			display : '��ʻԱ',
			width : '10%',
			validation : {
				required : true
			}
		},{
			name : 'idNumber',
			display : '��ʻԱ���֤��',
			width : '25%',
			validation : {
				required : true
			}
		},{
			name : 'displacement',
			display : '������ʹ�ú�������',
			width : '15%'
		},{
			name : 'oilCarUse',
			display : '�Ϳ��ֳ�',
			width : '10%',
			type : 'select',
			value : '��',
			options : [{
				name : "��",
				value : "��"
			},{
				name : "��",
				value : "��"
			}]
		},{
			name : 'oilCarAmount',
			display : '�Ϳ����',
			width : '15%',
			type : 'money'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		isAddOneRow : false,
		colModel : [{
			name : 'feeName',
			display : '��������',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '���ý��',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '��  ע',
			type : 'textarea',
			width : 220,
			rows : 2
		}]
	});
});

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=add&actType=actType";
}