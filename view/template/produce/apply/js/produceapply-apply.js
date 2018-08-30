$(document).ready(function () {
	$("#items").yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=contract_contract_equ&action=listJsonWith',
		param: {
			equIds: $("#equIds").val()
		},

		isAdd: false,

		colModel: [{
			display: '源单清单Id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '产品Id',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '物料类型',
			name: 'proType',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '物料类型Id',
			name: 'proTypeId',
			type: 'hidden'
		}, {
			display: '物料编码',
			name: 'productCode',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '规格型号',
			name: 'productModel',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '单位名称',
			name: 'unitName',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '需求数量',
			name: 'number',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '库存数量',
			name: 'inventory',
			type: 'statictext'
		}, {
			display: '半成品<br>库存数量',
			name: 'bcpkcsl',
			type: 'statictext',
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct" +
					"&code=" + row.productCode +
					"\",1)'><img title='库存信息' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}, {
			display: '已下达数量',
			name: 'issuedProNum',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '申请数量',
			name: 'produceNum',
			tclass: 'txtshort',
			process: function ($input, rowData) {
				var produceNum = rowData.number - rowData.issuedProNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('申请不能大于（需求数量-已下达数量）');
						$input.val(produceNum).focus();
					}
				});
				$input.val(produceNum);
			},
			validation: {
				custom: ['onlyNumber']
			}
		}, {
			display: '期望交货时间',
			name: 'planEndDate',
			tclass: 'txtshort',
			type: 'date',
			readonly: true
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			rows: 2
		}, {
			display: 'license',
			name: 'license',
			type: 'hidden'
		}]
	});
});