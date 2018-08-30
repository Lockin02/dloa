$(document).ready(function () {

	var productObj = $("#productItem")
	productObj.yxeditgrid({
		objName: 'picking[item]',
		url: '?model=produce_plan_pickingitem&action=listJson',
		param: {
			pickingId: $("#id").val(),
			dir: 'ASC'
		},
		isAdd: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '生产计划单号',
			name: 'planCode',
			type: 'statictext',
			width: 120
		}, {
			display: '合同编号',
			name: 'relDocCode',
			width: 120,
			type: 'statictext'
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '物料编码',
			name: 'productCode',
			type: 'statictext',
			width: 100
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext',
			width: 250
		}, {
			display: '规格型号',
			name: 'pattern',
			width: 100,
			type: 'statictext'
		}, {
			display: '单位',
			name: 'unitName',
			width: 80,
			type: 'statictext'
		}, {
			display: '旧设备仓',
			name: 'JSBC',
			type: 'statictext',
			width: 80
		}, {
			display: '库存商品',
			name: 'KCSP',
			type: 'statictext',
			width: 80
		}, {
			display: '生产仓',
			name: 'SCC',
			type: 'statictext',
			width: 80
		}, {
			display: '申请数量',
			name: 'applyNum',
			tclass: 'txtmin',
			validation: {
				custom: ['onlyNumber']
			}
		}, {
			display: '计划领料日期',
			name: 'planDate',
			tclass: 'txtshort',
			type: 'date',
			readonly: true,
			validation: {
				required: true
			}
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: 100
		}]
	});
});

//直接提交
function toSubmit() {
	document.getElementById('form1').action = "?model=produce_plan_picking&action=edit&actType=audit";
}