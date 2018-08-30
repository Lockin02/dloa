$(document).ready(function () {
	var itemTableObj = $("#suppInfo");

	$("#suppInfo").yxeditgrid({
		objName: 'rentalcar[supp]',
		dir: 'ASC',
		isFristRowDenyDel: true,
		colModel: [{
			name: 'suppName',
			display: '��Ӧ������',
			process: function ($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_outsuppvehicle({
					hiddenId: 'suppInfo_cmp_suppName' + rowNum,
					isShowButton: false,
					width: 615,
					isFocusoutCheck: false,
					gridOptions: {
						event: {
							row_dblclick: function (e, row, data) {
								itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "suppId").val(data.id);
								itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "suppName").val(data.suppName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "suppCode").val(data.suppCode);
								itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "linkManName").val(data.linkmanName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "linkManPhone").val(data.linkmanPhone);
							}
						}
					}
				});
			},
			validation: {
				required: true
			}
		}, {
			name: 'suppCode',
			display: '��Ӧ�̱��',
			type: 'hidden'
		}, {
			name: 'suppId',
			display: '��Ӧ��ID',
			type: 'hidden'
		}, {
			name: 'linkManName',
			display: '��ϵ������',
			width: 60,
			validation: {
				required: true
			}
		}, {
			name: 'linkManPhone',
			display: '��ϵ�˵绰',
			width: 80,
			validation: {
				required: true,
				custom: ['onlyNumber']
			}
		}, {
			name: 'vehicleModelCode',
			display: '�⳵����',
			width: 120,
			type: 'select',
			datacode: 'WBZCCX' // �����ֵ����
		}, {
			name: 'powerSupply',
			display: '�����������',
			width: 110,
			type: 'select',
			options: [{
				name: "������Ŀ����",
				value: "1"
			}, {
				name: "��������Ŀ����",
				value: "0"
			}]
		}, {
			name: 'spotPrice',
			display: '�ֳ���ͨ�۸�',
			width: 80,
			type: 'money',
			validation: {
				required: true
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.change(function () {
					$(this).trigger('blur');
					calMonthlyFee();
				});
			}
		}, {
			name: 'useCarAmount',
			display: '�ó�����',
			width: 60,
			validation: {
				required: true,
				custom: ['onlyNumber']
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.change(function () {
					calUseCarAmount();
				});
			}
		}, {
			name: 'spotPriceExplain',
			display: '�ֳ���ͨ�۸�˵��',
			type: 'textarea',
			rows: 3,
			width: 200
		}, {
			name: 'paymentCycleCode',
			display: '��������',
			width: 90,
			type: 'select',
			datacode: 'WBFKZQ' // �����ֵ����
		}, {
			name: 'vehicleMileage',
			display: '���������',
			type: 'money',
			width: 80
		}, {
			name: 'isProvideInvoice',
			display: '�Ƿ��ṩ��Ʊ',
			width: 80,
			type: 'select',
			options: [{
				name: "��",
				value: "1"
			}, {
				name: "��",
				value: "0"
			}],
			process: function ($input, rowData) {
				var rowNum = $input.data("rowNum");
					$("#suppInfo_cmp_isProvideInvoice" + rowNum).change(function () {
					var $invoiceCode = $("#suppInfo_cmp_invoiceCode" + rowNum);
					var $taxPoint = $("#suppInfo_cmp_taxPoint" + rowNum);
					if ($(this).val() != '1') {
						$invoiceCode.hide();
						$taxPoint.hide();
					} else {
						$invoiceCode.show();
						$taxPoint.show();
					}
				});
			}
		}, {
			name: 'invoiceCode',
			display: '��Ʊ����',
			width: 120,
			type: 'select',
			datacode: 'WBFPZL' // �����ֵ����
		}, {
			name: 'taxPoint',
			display: '��Ʊ˰��',
			width: 60,
			validation: {
				required: false,
				custom: ['percentage']
			}
		}, {
			name: 'taxationBearsCode',
			display: '˰�ѳе���',
			width: 90,
			type: 'select',
			datacode: 'WBSFCD' // �����ֵ����
		}, {
			name: 'remark',
			display: '��ע',
			type: 'textarea',
			rows: 3,
			width: 300
		}, {
            display : '�����ϴ�',
            name : 'file',
            type : 'file',
            serviceType:'rentalcar_supp'
        }],
		event: {
			removeRow: function () {
				calMonthlyFee();
				calUseCarAmount();
			}
		}
	});
});

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=outsourcing_vehicle_rentalcar&action=add&actType=audit";
}