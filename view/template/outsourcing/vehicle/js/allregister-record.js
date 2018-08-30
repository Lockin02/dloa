$(document).ready(function() {

	$("#registerInfo").yxeditgrid({
		url : '?model=outsourcing_vehicle_register&action=recordJson',
		param : {
			dir : 'ASC',
			sort : 'useCarDate',
			allregisterId : $("#id").val(),
			carNum : $("#carNum").val()
		},
		bodyAlign : 'center',
		type : 'view',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			name : 'useCarDate',
			display : '��  ��',
			width : 70
		},{
			name : 'startMileage',
			display : '��ʼ���',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'endMileage',
			display : '�������',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectMileage',
			display : '��Ч���',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵��',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '�ͼ�',
			width : 40,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'reimbursedFuel',
			display : '�ͷ�',
			width : 50,
			type : 'statictext',
			process : function (v ,row) {
				if(v != 0) {
					return moneyFormat2(v ,2);
				} else{
					return moneyFormat2(row.gasolineKMCost ,2);
				}
			}
		},{
			name : 'parkingCost',
			display : 'ͣ����',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'tollCost',
			display : '·�ŷ�',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'mealsCost',
			display : '������',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷�',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'overtimePay',
			display : '�Ӱ��',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'specialGas',
			display : '�����ͷ�',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'drivingReason',
			display : '�г�����',
			width : 150,
			align : 'left'
		},{
			name : 'effectLogTime',
			width : 80,
			display : '��ЧLOGʱ��'
		},{
			name : '',
			width : 70,
			display : '�ó���ǩ��'
		},{
			name : '',
			width : 60,
			display : '˾��ǩ��'
		}]
	});

});