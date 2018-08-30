$(document).ready(function () {
	$("#items").yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=produce_apply_produceapplyitem&action=listJson',
		param: {
			mainId: $("#id").val(),
			isTemp: 0
		},

		isAdd: false,

		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '发货清单id',
			name: 'relDocItemId',
			type: 'hidden'
		}, {
			display: '物料类型',
			name: 'proType',
			type: 'statictext'
		}, {
			display: '物料编码',
			name: 'productCode',
			type: 'statictext'
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext'
		}, {
			display: '规格型号',
			name: 'pattern',
			type: 'statictext'
		}, {
			display: '单位名称',
			name: 'unitName',
			type: 'statictext'
		}, {
			display: '需求数量',
			name: 'needNum',
			type: 'statictext'
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
			name: 'exeNum',
			type: 'statictext'
		}, {
			display: '申请数量',
			name: 'produceNum',
			tclass: 'txtshort',
			process: function ($input, rowData) {
				var produceNum = rowData.needNum - rowData.exeNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('申请不能大于（需求数量-已下达数量）');
						$input.val(produceNum).focus();
					}
				});
				$input.val(rowData.produceNum);
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
		}]
	});
});