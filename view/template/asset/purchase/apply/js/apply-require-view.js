$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName: 'apply[applyItem]',
		title: '需求明细',
		url: '?model=asset_purchase_apply_applyItem&action=preListJson',
		delTagName: 'isDelTag',
		type: 'view',
		param: {
			applyId: $("#applyId").val(),
			"isDel": '0'
		},
		colModel: [{
			display: '物料名称',
			name: 'productName',
			tclass: 'readOnlyTxtItem',
			width: 200,
			process: function(v, row) {
				if (v == '') {
					return row.inputProductName;
				} else {
					return v
				}
			}
		}, {
			display: '规格',
			name: 'pattem',
			tclass: 'readOnlyTxtItem'
		}, {
			display: '申请数量',
			name: 'applyAmount',
			tclass: 'txtshort'
		}, {
			display: '供应商',
			name: 'supplierName',
			tclass: 'txtmiddle'
		}, {
			display: '单位',
			name: 'unitName',
			tclass: 'readOnlyTxtItem'
		}, {
			display: '采购数量',
			name: 'purchAmount',
			tclass: 'txtshort'
		}, {
			display: '单价',
			name: 'price',
			tclass: 'txtshort',
			// type : 'money'
			// 列表格式化千分位
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			display: '金额',
			name: 'moneyAll',
			tclass: 'txtshort',
			// 列表格式化千分位
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			display: '希望交货日期',
			name: 'dateHope',
			type: 'date'
		}, {
			display: '备注',
			name: 'remark',
			tclass: 'txt'
		}]
	});

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

// 查看需求申请的
function viewFrom(relDocId, relDocCode) {
	if (relDocId * 1 == relDocId) {
		window.open("?model=asset_require_requirement&action=toViewTab&requireId="
			+ relDocId
			+ "&requireCode="
			+ relDocCode
		);
	} else {
		window.open("?model=common_otherdatas&action=toSignInAwsMenu&reUrl="
			+ "%26cmd%3dcom.actionsoft.apps.asset_GetApplyTask%26id%3d"
			+ relDocId
		);
	}
}