$(document).ready(function() {

	//�ӱ���
	var itemTableObj = $("#suppInfo");
	$("#suppInfo").yxeditgrid({
		objName : 'rentalcar[supp]',
		dir : 'ASC',
		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'suppAffirm',
			display : '��Ӧ��ȷ��',
			width : 60,
			type : 'checkbox'
		},{
			name : 'deptName',
			display : '����',
			width : 80,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '��Ӧ������',
			type : 'statictext'
		},{
			name : 'linkManName',
			display : '��ϵ������',
			width : 60,
			type : 'statictext'
		},{
			name : 'linkManPhone',
			display : '��ϵ�˵绰',
			width : 80,
			type : 'statictext'
		},{
			name : 'useCarAmount',
			display : '�ó�����',
			width : 50,
			type : 'statictext'
		},{
			name : 'certificate',
			display : '֤�����',
			type : 'statictext'
		},{
			name : 'powerSupply',
			display : '�����������',
			type : 'statictext',
			width : 90,
			process : function (v) {
				if (v == 1) {
					return "������Ŀ����";
				}else {
					return "��������Ŀ����";
				}
			}
		},{
			name : 'paymentCycle',
			display : '��������',
			width : 70,
			type : 'statictext'
		},{
			name : 'invoice',
			display : '��Ʊ����',
			width : 60,
			type : 'statictext'
		},{
			name : 'taxPoint',
			display : '��Ʊ˰��',
			width : 50,
			type : 'statictext',
			datacode : 'WBZZSD' // �����ֵ����
		},{
			name : 'rentalFee',
			display : '�⳵�ѣ�����˾�����ʣ�',
			width : 150,
			type : 'statictext'
		},{
			name : 'gasolineFee',
			display : '�ͷ�',
			type : 'statictext'
		},{
			name : 'catering',
			display : '������',
			type : 'statictext'
		},{
			name : 'accommodationFee',
			display : 'ס�޷�',
			type : 'statictext'
		},{
			name : 'remark',
			display : '��ע',
			type : 'statictext',
			width : 300,
			align : 'left'
		}]
	});
});
