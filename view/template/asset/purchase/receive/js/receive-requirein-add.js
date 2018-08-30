$(function() {
	/**
	 * 验证信息
	 */
	validate({
		"salvage" : {
			required : true
		},
		"limitYears" : {
			custom : ['date']
		},
		"result" : {
			required : true
		}
	});
	
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
		isAdd : false,
		url : '?model=asset_require_requireinitem&action=getReceiveDetail',
		param : {
			"requireId" : $("#requireinId").val()
		},
		event : {
			reloadData : function(e, g, data) {
				if(data.length == undefined){
					alert("该需求暂无需要验收的物料")
					self.parent.tb_remove();
				}
			}
		},
		title : '验收清单',
		colModel : [{
			display : 'requireinItemId',
			name : 'requireinItemId',
			process:function($input,row){
				$input.val(row.id);
			},
			type : 'hidden'
		}, {
			display : '物料id',
			name : 'assetId',
			process:function($input,row){
				$input.val(row.productId);
			},
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'assetName',
			process:function($input,row){
				$input.val(row.productName);
			},
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '物料编号',
			name : 'assetCode',
			process:function($input,row){
				$input.val(row.productCode);
			},
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '规格',
			name : 'spec',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '品牌',
			name : 'brand',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '最大验收数量',
			name : 'shouldReceiveNum',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'checkAmount',
			process:function($input,row){
				$input.val(row.shouldReceiveNum)
			},
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					//验证数量是否合法
					var maxNum = grid.getCmpByRowAndCol(rownum, 'shouldReceiveNum').val();
					var checkAmount = $(this).val();
					if(checkAmount != "" && !isNum(checkAmount)){
						alert("数量请填正整数");
						$(this).val(maxNum).focus();
						return false;
					}else if(accSub(checkAmount,maxNum) > 0){
						alert("验收数量不能大于【" + maxNum + "】");
						$(this).val(maxNum).focus();
						return false;
					}
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '单价',
			name : 'productPrice',
			type : 'hidden'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// 选择人员组件
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#company').val(returnValue.companyCode)
					$('#companyName').val(returnValue.companyName)
				}
			}
		}
	});
});

//提交确认
function confirmAudit() {
	if($("#receiveTable").yxeditgrid('getCurShowRowNum') == 0){
		alert("验收清单不能为空");
		return false;
	}
	if (confirm("你确定要提交验收单吗?")) {
		$("#form1").attr("action","?model=asset_purchase_receive_receive&action=addByRequirein");
		$("#form1").submit();
	} else {
		return false;
	}
}