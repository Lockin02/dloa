$(document).ready(function() {
	$("#suppInfo").yxeditgrid({
		objName : 'rentalcar[supp]',
		dir : 'ASC',
		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'suppName',
			display : '��Ӧ������',
			width : 150
		},{
			name : 'suppCode',
			display : '��Ӧ�̱��',
			type : 'hidden'
		},{
			name : 'suppId',
			display : '��Ӧ��ID',
			type : 'hidden'
		},{
			name : 'linkManName',
			display : '��ϵ������',
			width : 60
		},{
			name : 'linkManPhone',
			display : '��ϵ�˵绰',
			width : 80
		},{
			name : 'vehicleModel',
			display : '�⳵����',
			width : 100
		},{
			name : 'powerSupply',
			display : '�����������',
			width : 90,
			process : function (v) {
				if (v == 1) {
					return "������Ŀ����";
				}else {
					return "��������Ŀ����";
				}
			}
		},{
			name : 'spotPrice',
			display : '�ֳ���ͨ�۸�',
			width : 80,
			process : function (v) {
				return moneyFormat2(v);
			}
		},{
			name : 'useCarAmount',
			display : '�ó�����',
			width : 60
		},{
			name : 'spotPriceExplain',
			display : '�ֳ���ͨ�۸�˵��',
			type : 'textarea',
			rows : 3,
			width : 200,
			align:'left'
		},{
			name : 'paymentCycle',
			display : '��������',
			width : 70
		},{
			name : 'vehicleMileage',
			display : '���������',
			width : 80,
			process : function (v) {
				return moneyFormat2(v);
			}
		},{
			name : 'isProvideInvoice',
			display : '�Ƿ��ṩ��Ʊ',
			width : 80,
			process : function (v) {
				if (v == 1) {
					return "��";
				}else {
					return "��";
				}
			}
		},{
			name : 'invoice',
			display : '��Ʊ����',
			width : 70,
			process : function (v ,row) {
				if (row.invoiceCode != '') {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'taxPoint',
			display : '��Ʊ˰��',
			width : 50,
			process : function (v) {
				if (v != '') {
					return v + "%";
				}
			}
		},{
			name : 'taxationBears',
			display : '˰�ѳе���',
			width : 90
		},{
			name : 'remark',
			display : '��ע',
			type : 'textarea',
			rows : 3,
			width : 300,
			align:'left'
		}, {
            display : '�����ϴ�',
            name : 'file',
            type : 'file',
            serviceType:'rentalcar_supp'
        }]
	});

	//CDMA�ó��ص�
	if ($("#provinceId").val() == 43) {
		$("#usePlace").parent().show().prev().show().parent().show();
	}
});