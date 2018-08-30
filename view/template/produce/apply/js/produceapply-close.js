$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'produceapply[items]',
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'state',
			display : '是否关闭',
			type : 'checkbox',
			checkVal : 1,
			process : function ($input ,row) {
				if ($input.val() == 1) {
					$input.change(function () {
						alert('该物料已关闭！');
						$(this).attr('checked' ,true);
					});
				} else {
					$input.change(function () {
						if ($(this).attr('checked')) {
							if (row.exeNum > 0) {
								if (window.confirm("该物料已下达任务，确认要关闭？")) {
									$(this).attr('checked' ,true);
								} else {
									$(this).attr('checked' ,'');
								}
							}
						}
					});
				}
			}
		},{
			name : 'productCode',
			display : '物料编码',
			type : 'statictext'
		},{
			name : 'productName',
			display : '物料名称',
			type : 'statictext'
		},{
			name : 'pattern',
			display : '规格型号',
			type : 'statictext'
		},{
			name : 'unitName',
			display : '单位',
			type : 'statictext'
		},{
			name : 'produceNum',
			display : '申请数量',
			type : 'statictext'
		},{
			name : 'exeNum',
			display : '已下达数量',
			type : 'statictext'
		},{
			name : 'stockNum',
			display : '已入库数量',
			type : 'statictext'
		},{
			name : 'inventory',
			display : '库存数量',
			type : 'statictext'
		},{
			name : 'onwayAmount',
			display : '在途数量',
			type : 'statictext'
		},{
			name : 'planEndDate',
			display : '计划交货时间',
			type : 'statictext'
		},{
			name : 'remark',
			display : '备注',
			width : '20%',
			align : 'left',
			type : 'statictext'
		}]
	});

	validate({
		"closeReason" : {
			required : true
		}
	});
});

//提交数据检验
function checkData() {
	var stateObjs = $("#itemTable").yxeditgrid('getCmpByCol' ,'state');
	var tmp = false; //是否有选中记录的标志位
	stateObjs.each(function () {
		if ($(this).attr('checked')) {
			tmp = true;
			return false; //这里只是退出循环
		}
	});
	if (tmp) {
		return true;
	} else {
		alert('没有选中的记录！');
		return false;
	}
}