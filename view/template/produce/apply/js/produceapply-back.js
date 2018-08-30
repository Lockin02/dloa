$(document).ready(function () {
	$('#itemInfo').yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=produce_apply_produceapplyitem&action=listJson',
		param: {
			state: 0,
			mainId: $('#id').val(),
			dir: 'ASC'
		},
		isAddAndDel: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			name: 'state',
			display: '是否打回',
			type: 'checkbox'
		}, {
			display: '物料类型',
			name: 'proType',
			type: 'statictext'
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext'
		}, {
			display: '物料编码',
			name: 'productCode',
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
			name: 'produceNum',
			display: '申请数量',
			type: 'statictext'
		}]
	});

	validate({
		"backReason": {
			required: true
		}
	});
});

// 数据校验
function checkData() {
	var rs = false;
	$('input[id^=itemInfo_cmp_state]').each(function () {
		if ($(this).attr('checked')) {
			rs = true;
			return false; // 退出循环
		}
	});

	if (!rs) {
		alert('至少选择一条记录进行打回！');
	}

	return rs;
}