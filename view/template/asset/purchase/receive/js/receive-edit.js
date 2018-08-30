$(function() {
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
//		delTagName : 'isDelTag',
		url : '?model=asset_purchase_receive_receiveItem&action=listJson',
		param : {
			receiveId : $("#receiveId").val()
		},
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '资产名称',
			name : 'assetName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '规格',
			name : 'spec',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '数量',
			name : 'checkAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
					var price = $(this).val();
					var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
//					$amount.val(price * checkAmount);
					$amount.val(accMul(price,checkAmount));
					var $amountv = $("#"+$amount.attr('id')+'_v');
					$amountv.val(moneyFormat2(accMul(price,checkAmount)));
					check_all();
				}
			}
		}, {
			display : '金额',
			name : 'amount',
			type : 'money',
			readonly : true
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})
	// 选择人员组件
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */
	validate({
		"name" : {
			required : true
		},
		"code" : {
			required : true
		},
		"salvage" : {
			required : true
		},
		"limitYears" : {
			custom : ['date']
		},
		"amount" : {
			custom : ['money']
		},
		"result" : {
			required : true
		}
	});

});

// 根据从表的金额动态计算总金额
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#receiveTable").yxeditgrid("getCmpByCol", "amount");
	cmps.each(function() {
//		rowAmountVa = accAdd(rowAmountVa, $(this).val());
//		rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(),2);
	});
	$("#amount").val(rowAmountVa);
	$("#amount_v").val(moneyFormat2(rowAmountVa));
	return false;
}

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_receive_receive&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}