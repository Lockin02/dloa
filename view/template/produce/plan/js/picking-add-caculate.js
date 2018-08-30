$(document).ready(function () {
	var data = eval("(" + $("#productJson").text() + ")");
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName: 'picking[item]',
		data: data,
		isAdd : false,
		colModel: [{
			display: '计划Id',
			name: 'planId',
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
		},{
			display: '生产批次号',
			name: 'productionBatch',
			width: 120,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '物料编码',
			name: 'productCode',
			width: 100,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '物料名称',
			name: 'productName',
			width: 250,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '规格型号',
			name: 'pattern',
			width: 90,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '单位',
			name: 'unitName',
			type: 'statictext',
			width: 60,
			isSubmit: true
		}, {
			display: '旧设备仓',
			name: 'JSBC',
			type: 'statictext',
			width: 60
		}, {
			display: '库存商品',
			name: 'KCSP',
			type: 'statictext',
			width: 60
		}, {
			display: '生产仓',
			name: 'SCC',
			type: 'statictext',
			width: 60
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

	//验证数据属性
	validate({});
});

//设置从表计划领料日期
function setPlanDate(e) {
	if (e.value != '') {
		var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol', 'planDate');
		planDateObjs.each(function (k, v) {
			if (this.value == '') {
				$(this).val(e.value);
			}
		});
	}
}

// 数据校验
function checkData() {
	var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol', 'planDate');
	if (planDateObjs.length == 0) {
		alert('没有可操作记录！');
		return false;
	}
	return true;
}

//直接提交
function toSubmit() {
	document.getElementById('form1').action = "?model=produce_plan_picking&action=addCaculate&actType=audit";
}