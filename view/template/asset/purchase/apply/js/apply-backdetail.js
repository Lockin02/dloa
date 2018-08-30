$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#id").val(),
			"isDel" : '0'
		},
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'inputProductName',
			tclass : 'txt'
		}, {
			display : '物料id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : ' 已下达数量',
			name : 'issuedAmount',
			tclass : 'readOnlyTxtShort',
			process:function($input,row){
				if(row.issuedAmount==""){
					$input.val(0);
				}
			},
			readonly : true
		}, {
			display : '可撤回数量',
			name : 'backAmount',
			tclass : 'txtshort',
			process:function($input,row){
				if(row.issuedAmount==""){
					$input.val(row.applyAmount);
				}else{
					$input.val(row.applyAmount - row.issuedAmount);
				}
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var issuedAmount = grid.getCmpByRowAndCol(rownum, 'issuedAmount').val();
					var canBackAmount = applyAmount - issuedAmount;

					if($(this).val() *1 < 0){
						alert("撤回数量不能小于0！");
						$(this).val(canBackAmount);
					}

					if($(this).val() *1 > canBackAmount *1){
						alert("撤回数量不能大于申请数量 - 已下达数量！");
						$(this).val(canBackAmount);
					}
				}
			}
		}]
	})
});

// 根据从表的金额动态计算总金额
function checkform() {
	return confirm('确认撤回物料吗？');
}
