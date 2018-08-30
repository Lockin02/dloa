$(document).ready(function () {
	var itemTableObj = $("#suppInfo");

	$("#suppInfo").yxeditgrid({
		objName: 'rentalcar[supp]',
		dir: 'ASC',
		isFristRowDenyDel: true,
		colModel: [{
			name: 'suppName',
			display: '供应商名称',
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
			display: '供应商编号',
			type: 'hidden'
		}, {
			name: 'suppId',
			display: '供应商ID',
			type: 'hidden'
		}, {
			name: 'linkManName',
			display: '联系人姓名',
			width: 60,
			validation: {
				required: true
			}
		}, {
			name: 'linkManPhone',
			display: '联系人电话',
			width: 80,
			validation: {
				required: true,
				custom: ['onlyNumber']
			}
		}, {
			name: 'vehicleModelCode',
			display: '租车车型',
			width: 120,
			type: 'select',
			datacode: 'WBZCCX' // 数据字典编码
		}, {
			name: 'powerSupply',
			display: '车辆供电情况',
			width: 110,
			type: 'select',
			options: [{
				name: "满足项目需求",
				value: "1"
			}, {
				name: "不满足项目需求",
				value: "0"
			}]
		}, {
			name: 'spotPrice',
			display: '现场沟通价格',
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
			display: '用车数量',
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
			display: '现场沟通价格说明',
			type: 'textarea',
			rows: 3,
			width: 200
		}, {
			name: 'paymentCycleCode',
			display: '付款周期',
			width: 90,
			type: 'select',
			datacode: 'WBFKZQ' // 数据字典编码
		}, {
			name: 'vehicleMileage',
			display: '车辆里程数',
			type: 'money',
			width: 80
		}, {
			name: 'isProvideInvoice',
			display: '是否提供发票',
			width: 80,
			type: 'select',
			options: [{
				name: "是",
				value: "1"
			}, {
				name: "否",
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
			display: '发票种类',
			width: 120,
			type: 'select',
			datacode: 'WBFPZL' // 数据字典编码
		}, {
			name: 'taxPoint',
			display: '发票税点',
			width: 60,
			validation: {
				required: false,
				custom: ['percentage']
			}
		}, {
			name: 'taxationBearsCode',
			display: '税费承担方',
			width: 90,
			type: 'select',
			datacode: 'WBSFCD' // 数据字典编码
		}, {
			name: 'remark',
			display: '备注',
			type: 'textarea',
			rows: 3,
			width: 300
		}, {
            display : '附件上传',
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

//直接提交
function toSubmit() {
	document.getElementById('form1').action = "?model=outsourcing_vehicle_rentalcar&action=add&actType=audit";
}