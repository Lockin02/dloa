$(document).ready(function () {
	var productObj = $("#productOut");
	productObj.yxeditgrid({
		objName: 'picking[back]',
		url: '?model=produce_plan_pickingitem&action=listJson',
		param: {
			idArr: $("#idStr").val(),
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
			type: 'statictext',
			width: 120
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '物料编码',
			name: 'productCode',
			type: 'statictext',
			isSubmit: true,
			width: 80
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext',
			width: 200,
			isSubmit: true
		}, {
			display: '规格型号',
			name: 'pattern',
			type: 'statictext',
			width: 90,
			isSubmit: true
		}, {
			display: '单位',
			name: 'unitName',
			type: 'statictext',
			width: 80,
			isSubmit: true
		}, {
			display: '生产仓',
			name: 'SCC',
			type: 'statictext',
			width: 60
		}, {
			display: '领料申请数量',
			name: 'applyNum',
			type: 'statictext',
			width: 70
		}, {
			display: '出库数量',
			name: 'realityNum',
			type: 'statictext',
			width: 70
		}, {
			display: '申请退料数量',
			name: 'proBackNum',
			tclass: 'txtshort',
			validation: {
				custom: ['onlyNumber']
			},
			process: function ($input, rowDate) {
				var validNum = parseInt(rowDate.realityNum);
				$input.val(validNum).blur(function () {
					if (accSub(validNum, $(this).val()) < 0 || $(this).val() == 0) {
						alert('申请退料数量无效！');
						$(this).val(validNum).focus();
					}
				});
			}
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: 150
		}]
	});

	validate({});
});

//数据校验
function checkData() {
	var productObj = $("#productOut").yxeditgrid("getCmpByCol", "proBackNum");
	var result = true;
	if (productObj.length == 0) {
		alert('没有申请退料的物料！');
		return false;
	} else if (productObj.length > 0) {
		productObj.each(function () {
			if (this.value == 0 || this.value < 0) {
				alert('申请退料数量无效！');
				$(this).focus();
				result = false;
				return false; //退出循环
			}
		});
	}

	return result;
}