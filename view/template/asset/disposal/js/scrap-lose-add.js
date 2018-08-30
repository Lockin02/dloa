$(function() {
	// 选择人员组件
	$("#payer").yxselect_user({
		hiddenId : 'payerId'
	});
    //alert($("#loseBillNo").val());
	$("#purchaseProductTable").yxeditgrid({

		objName : 'scrap[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			loseId: $("#loseId").val(),
			loseBillNo : $("#loseBillNo").val(),
			//只显示未报废的卡片
			isScrap:'0'
		},
		//isAddAndDel : false,
		isAdd : false,
		colModel : [
			{
			display : '遗失单Id',
			name : 'loseId',
			type : 'hidden'
		},
//		,{
//			display : '遗失单编号',
//			name : 'loseBillNo',
//			type : 'hidden',
//			//给从表文本框赋值
//			process : function($input,row){
//				//var assetId = row.id;
//				var loseBillNo = $("#loseBillNo").val();
//				$input.val(loseBillNo);
//			}
//		},
			{
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '卡片编号',
			name : 'assetCode',
			validation : {
				required : true
			},
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
			},
			readonly : true
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
			},
			readonly : true
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			tclass : 'txtshort',
			// type:'date',
			readonly : true
		}, {
			display : '资产原值',
			name : 'origina',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '残值',
			name : 'salvage',
			tclass : 'txtshort',
			readonly : true,
			type : 'money',
			process : function() {
				countAmount();
			}
		}, {
			display : '已提折旧',
			name : 'depreciation',
			tclass : 'txtshort',
			readonly : true,
			type : 'money',
			process : function() {
				countAmount();
			}
		}, {
			display : '出售状态',
			name : 'sellStatus',
			readonly : true,
			//给从表文本框赋值
			process : function($input,row){
				//var assetId = row.id;
				$input.val('未出售');
			},
			type:'hidden'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// 选择人员组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */

	validate({
		"billNo" : {
			required : true

		},
		"proposer" : {
			required : true
		},
		"scrapNum" : {
			required : true,
			custom : ['onlyNumber']
		},
//		"amount" : {
//			required : true,
//			custom : ['money']
//		},
		"salvage" : {
			required : true,
			custom : ['money']
		}
	});

});

// 根据从表的残值动态计算对应的总残值
function countAmount() {
	// 获取当前的行数即卡片的资产数  getCurRowNum  getCurShowRowNum
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#scrapNum").val(curRowNum);
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#salvage").val(rowAmountVa);
	$("#salvage_v").val(moneyFormat2(rowAmountVa));

	return true;
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
