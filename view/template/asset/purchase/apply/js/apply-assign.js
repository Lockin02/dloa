$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=issuedListJson',
		param : {
			applyId : $("#applyId").val(),
			"purchDept" : '0',
			"isDel" : '0'
		},
		isAddAndDel : false,
//		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			type : 'statictext',
			process : function(v,row){
				if(v==''){
					return row.inputProductName;
				}else{
					return v
				}
			}
		}, {
			display : '规格',
			name : 'pattem',
			type : 'statictext'
		}, {
			display : '申请数量',
			name : 'applyAmount',
			type : 'statictext'
		}, {
			display : '供应商',
			name : 'supplierName',
			type : 'statictext'
		}, {
			display : '单位',
			name : 'unitName',
			type : 'statictext'
		}, {
			display : '采购数量',
			name : 'purchAmount',
			type : 'statictext'
		}, {
			display : '单价',
			name : 'price',
			type : 'statictext',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'moneyAll',
			type : 'statictext',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'statictext'
		}, {
			display : '备注',
			name : 'remark',
			type : 'statictext'
		}, {
			display : '采购部门',
			name : 'purchDept',
			type : 'select',
			options : [{
				name : "行政部",
				value : 0
			}, {
				name : "交付部",
				value : 1
			}]
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

});