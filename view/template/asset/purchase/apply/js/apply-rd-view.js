$(document).ready(function() {

	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#id").val()
		},
		event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var rowNum = 0; rowNum < rowCount; rowNum++) {
					if ($("#RDProductTable_cmp_equUseYear" + rowNum).val() == "0") {
						$("#RDProductTable_cmp_equUseYear" + rowNum)
								.val("一年以上");
					} else {
						$("#RDProductTable_cmp_equUseYear" + rowNum)
								.val("一年以下");
					}
					if ($("#RDProductTable_cmp_planPrice" + rowNum).val() == "0") {
						$("#RDProductTable_cmp_planPrice" + rowNum)
								.val("500元以上");
					} else {
						$("#RDProductTable_cmp_planPrice" + rowNum)
								.val("500元以下");
					}
				}
			}
		},
		colModel : [{
					// display : '设备编码',
					// name : 'productCode'
					// }, {
					display : '设备名称',
					name : 'productName'
				}, {
					display : '规格型号',
					name : 'pattem'

				}, {
					display : '单位',
					name : 'unitName',
					tclass : 'txtshort'
				}, {
					display : '数量',
					name : 'applyAmount',
					tclass : 'txtshort'
				}, {
					display : '希望交货日期',
					name : 'dateHope',
					type : 'date',
					tclass : 'txtshort'
				}, {
					display : '供应商',
					name : 'supplierName'

				}, {
					display : '设备使用年限',
					name : 'equUseYear',
					tclass : 'txtshort',
					process : function(v, row) {
						if (v == "0") {
							return "一年以上";
						} else {
							return "一年以下";
						}
					}
				}, {
					display : '预计购入单价',
					name : 'planPrice',
					type : 'select',
					tclass : 'txtmiddle',
					process : function(v, row) {
						if (v == "0") {
							return "500元以上";
						} else {
							return "500元以下";
						}
					}

				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
	})
	// 根据是否属于研发专项设备来显示部分字段（研发专项项目名称、研发专项编号）
	if ($("#isrd").text() == "1") {
		$("#hiddenA").hide();
	} else {
		$("#hiddenA").show();
	}

	// 判断是否显示关闭按钮
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});