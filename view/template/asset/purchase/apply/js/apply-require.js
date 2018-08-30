$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#applyId").val(),
			"isDel" : '0'
		},
		isAdd : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'inputProductName',
			tclass : 'txtshort'
		}, {
			display : '规格',
			name : 'pattem',
			tclass : 'txtshort'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '供应商',
			name : 'supplierName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '采购数量',
			name : 'purchAmount',
			tclass : 'txtshort',
			process:function($input,row){
				if(row.purchAmount==""){
					$input.val(row.applyAmount);
				}
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// 第几行
					var colnum = $(this).data('colNum');// 第几列
					var grid = $(this).data('grid');// 表格组件
					var price = grid.getCmpByRowAndCol(rownum, 'price').val();
					var purchAmount = $(this).val();
					var applyAmount = grid.getCmpByRowAndCol(rownum,
							'applyAmount').val();
					applyAmount = parseFloat(applyAmount);
					purchAmount = parseFloat(purchAmount);
					if (purchAmount > applyAmount) {
						alert("采购数量不能超过申请数量！");
						$(this).val(applyAmount);
					}
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
					var purchAmount = $(this).val();
//					$moneyAll.val(price * purchAmount);
					$("#"+$moneyAll.attr('id').replace('_v',"")).val(accMul(price,purchAmount));
//					$moneyAll.val(accMul(price,purchAmount));
//					var $moneyAllv = $("#"+$moneyAll.attr('id')+'_v');
					$moneyAll.val(moneyFormat2(accMul(price,purchAmount)));
					check_all();
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// 第几行
					var colnum = $(this).data('colNum');// 第几列
					var grid = $(this).data('grid');// 表格组件
					var purchAmount = grid.getCmpByRowAndCol(rownum,
							'purchAmount').val();
					var price = $(this).val();
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
//					$moneyAll.val(accMul(price,purchAmount));
					$("#"+$moneyAll.attr('id').replace('_v',"")).val(accMul(price,purchAmount));
					$moneyAll.val(moneyFormat2(accMul(price,purchAmount)));
					check_all();
				}
			},
			validation : {
				required : true
			}
		}, {
			display : '金额',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '删除',
			name : 'isDel',
			type : 'hidden'
		}]
	})

	// 根据采购类型来判断是否显示部分的字段
	// alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "计划内 ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
	// alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "研发类") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}

	/**
	 * 验证信息
	 */
//	validate({
//		"estimatPrice" : {
//			required : true
//		},
//		"moneyAll" : {
//			required : true
//		}
//	});
});

// 根据从表的金额动态计算总金额
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "moneyAll");
	cmps.each(function() {
//		rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(),2);
	});
	$("#moneyAll").val(rowAmountVa);
	$("#moneyAll_v").val(moneyFormat2(rowAmountVa));
	return false;
}
