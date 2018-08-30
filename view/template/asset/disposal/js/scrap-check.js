$(document).ready(function() {
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	 * 验证信息
	 */
	validate({
		"amount_v" : {
			required : true
		}
	});
	$("#purchaseProductTable").yxeditgrid({
		objName:'scrap[item]',
		url:'?model=asset_disposal_scrapitem&action=listJson',
		param:{allocateID:$("#allocateID").val()},
		isAddAndDel : false,
	colModel : [
		{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '遗失单Id',
			name : 'loseId',
			type : 'hidden'
		}, {
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display:'报废资产编码',
			name : 'assetCode',
			type : 'statictext',
			width : 120
		}, {
			display:'资产名称',
			name : 'assetName',
			type : 'statictext',
			width : 100
		},{
			display:'规格型号',
			name : 'spec',
			type : 'statictext',
			width : 100
		}, {
			display:'购置日期',
			name : 'buyDate',
			type : 'statictext',
			width : 100
		}, {
			display:'资产原值',
			name : 'origina',
			type : 'money',
			type : 'statictext',
			width : 100
		}, {
			display:'残值',
			name : 'salvage',
			type : 'money',
			// blur 失焦触发计算金额
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : '净值',
			name : 'netValue',
			type : 'money',
			// blur 失焦触发计算金额
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display:'已提折旧',
			name : 'depreciation',
			type : 'money',
			type : 'statictext',
			width : 100
		}, {
			display:'备注',
			name : 'remark',
			type : 'statictext',
			width : 100
		}]
   })
});
/*
 * 核对资产报废申请
 */
function confirmCheck() {
	if (confirm("确定要核对资产报废申请吗?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=confirmRequire&actType=check");
		$("#form1").submit();

	} else {
		return false;
	}
}
/*
 * 打回资产报废申请
 */
function confirmBack() {
	if (confirm("确定要打回资产报废申请吗?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=confirmRequire&actType=back");
		$("#form1").submit();

	} else {
		return false;
	}
}
/*
 * 根据从表计算总残值,总净值
 */
function countAmount() {
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		rowsalvageVa = accAdd(rowsalvageVa, $(this).val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		rownetValueVa = accAdd(rownetValueVa, $(this).val(), 2);
	});
	//总残值
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//总净值
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}