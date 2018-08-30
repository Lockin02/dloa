$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'back[items]',
		title : '退料申请单明细',
		isAdd : false,
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#id").val(),
			pickingId : $("#pickingId").val(),
			type : 'edit',
			dir : 'ASC'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '领料单Id',
			name : 'pickingId',
			type : 'hidden'
		},{
			display : '领料单清单Id',
			name : 'pickingItemId',
			type : 'hidden'
		},{
			display : '生产计划Id',
			name : 'planId',
			type : 'hidden'
		},{
			display : '生产计划单号',
			name : 'planCode',
			width : 120,
			type : "statictext"
		},{
			display : '生产任务Id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '生产任务Code',
			name : 'taskCode',
			type : 'hidden'
		},{
			display : '合同Id',
			name : 'relDocId',
			type : 'hidden'
		},{
			display : '合同编号',
			name : 'relDocCode',
			width : 120,
			type : "statictext"
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode',
			width : 75,
			type : "statictext"
		},{
			display : '物料名称',
			name : 'productName',
			width : 250,
			type : "statictext"
		},{
			display : '规格型号',
			name : 'pattern',
			width : 90,
			type : "statictext"
		},{
			display : '单位',
			name : 'unitName',
			width : 80,
			type : "statictext"
		},{
			display : '旧设备仓',
			name : 'JSBC',
			width : 50,
			type : "statictext"
		},{
			display : '库存商品',
			name : 'KCSP',
			width : 50,
			type : "statictext"
		},{
			display : '生产仓',
			name : 'SCC',
			width : 50,
			type : "statictext"
		},{
			display : '申请数量',
			name : 'applyNum',
			width : 50,
			process : function ($input, rowData) {
				var oldNum = $input.val();
				var maxNum = rowData['maxNum'];
				$input.change(function () {
					if(!isNum($(this).val())){
						alert("请输入正整数!");
						$(this).val(oldNum);
					}
					if(accSub($(this).val(),maxNum) > 0){
						alert("申请数量不能大于" + maxNum);
						$(this).val(oldNum);
					}
				})
			}
		},{
			display : '备注',
			name : 'remark',
			width : 120,
			align : 'left'
		}]
	});
});

//保存
function save(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit");
}

// 提交
function sub(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit&act=sub");
}

//提交时验证
function confirmCheck(){
	if($("#productItem").yxeditgrid('getCurShowRowNum') == 0){
		alert("退料申请单明细不能为空!");
		return false;
	}
}