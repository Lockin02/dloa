$(document).ready(function() {
	$("#registerInfo").yxeditgrid({
		objName : 'allregister[register]',
		url : '?model=outsourcing_vehicle_register&action=listJson',
		param : {
			sort : 'carNum,useCarDate',
			dir : 'ASC',
			allregisterId : $("#id").val(),
			state : 1,
			useCarDateLimit : $("#useCarDate").val()
		},
		isAddAndDel : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�Ƿ���<input type="checkbox" id="backAll" style="width:60px;">',
			name : 'back',
			type : 'checkbox',
			checkVal : '1',
			width : 60
		},{
			name : 'useCarDate',
			display : '�ó�����',
			width : 80,
			type : 'statictext'
		},{
			name : 'createName',
			display : '¼����',
			width : 80,
			type : 'statictext'
		},{
			name : 'carNum',
			display : '��  ��',
			width : 80,
			type : 'statictext'
		},{
			name : 'carModel',
			display : '��  ��',
			width : 100,
			type : 'statictext'
		},{
			name : 'rentalProperty',
			display : '�⳵����',
			width : 70,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '��Ӧ������',
			width : 130,
			type : 'statictext'
		},{
			display : '�⳵��ͬID',
			name : 'rentalContractId',
			type : 'hidden'
		},{
			display : '����Hidden',
			name : 'carNum',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '�⳵��ͬ���',
			width : 120,
			type : 'statictext',
			process : function (v ,row) {
				if (row.rentalContractId > 0) {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'effectMileage',
			display : '��Ч���',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolinePrice',
			display : '�ͼۣ�Ԫ��',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '������Ƽ��ͷѵ��ۣ�Ԫ��',
			width : 140,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '������Ƽ��ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : 'ʵ��ʵ���ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : 'ͣ���ѣ�Ԫ��',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '·�ŷѣ�Ԫ��',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵�ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'mealsCost',
			display : '�����ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'overtimePay',
			display : '�Ӱ�ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'specialGas',
			display : '�����ͷѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'allCost',
			display : '�ܷ��ã�Ԫ��',
			width : 90,
			type : 'statictext',
			process : function (v ,row) {
				var allCost = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(allCost ,2);
			}
		},{
			name : 'effectLogTime',
			display : '��ЧLOGʱ��',
			width : 120,
			type : 'statictext'
		},{
			name : 'deductInformation',
			display : '�ۿ���Ϣ',
			width : 200,
			type : 'statictext'
		},{
			name : 'estimate',
			display : '����',
			width : 200,
			type : 'statictext'
		}]
	});

    //�Ƿ���ȫѡ
	$('#backAll').click(function () {
		var backObj = $("#registerInfo").yxeditgrid('getCmpByCol' ,'back');
		if ($(this).attr('checked') == true) {
			backObj.each(function () {
				$(this).attr('checked' ,'checked');
			});
		} else {
			backObj.each(function () {
				$(this).attr('checked' ,'');
			});
		}
	});
});

//�ύ����
function checkData() {
	var backObj = $("#registerInfo").yxeditgrid('getCmpByCol' ,'back');
	var result = false;
	backObj.each(function () {
		if ($(this).attr('checked') == true) {
			result = true;
			return false; //�˳�ѭ��
		}
	});
	if (!result) { //û��һ��ѡ��
		alert('������ѡ��һ����¼��');
		return false;
	}

	return true;
}