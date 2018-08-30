$(function() {
	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// delTagName : 'isDelTag',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'productCode',
			validation : {
				required : true
			}
		}, {
			display : '设备名称',
			name : 'productName',
			validation : {
				required : true
			}
		}, {
			display : '规格型号',
			name : 'pattem',
			validation : {
				required : true
			}
		}, {
			display : '供应商',
			name : 'supplierName',
			validation : {
				required : true
			}
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '数量',
			name : 'applyAmount',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort',
			validation : {
				custom : ['date']
			}
		}, {
			display : '设备使用年限',
			name : 'life',
			type : 'select',
			tclass : 'txtshort',
			options : [{
				name : "一年以上",
				value : 0
			}, {
				name : "一年以下",
				value : 1
			}]
		}, {
			display : '预计购入单价',
			name : 'exPrice',
			type : 'select',
			tclass : 'txtmiddle',
			options : [{
				name : "500元以上",
				value : 0
			}, {
				name : "500元以下",
				value : 1
			}]
		}, {
			display : '是否归属固定资产',
			name : 'isAsset',
			type : 'checkbox',
			tclass : 'txtmin'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// 根据是否属于研发专项设备来显示部分字段（研发专项项目名称、研发专项编号）

	$('#isrd').change(function() {
		if ($("#isrd").val() == "1") {
			$("#hiddenA").hide();
		} else {
			$('#rdProject').val("");
			$('#rdProjectCode').val("");
			$("#hiddenA").show();
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
		"userTel" : {
			required : false,
			custom : ['onlyNumber']
		}
	});

	});




/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_apply_apply&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}