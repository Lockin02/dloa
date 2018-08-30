//初始化从表信息
$(document).ready(function() {
    var sourceType=$("#sourceType").val();
    if(sourceType=="YFQTYD03"){
        $(".finalAmount").removeAttr('style');
    }
	//归属公司
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		height: 250,
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					//初始化树结构
					initTree();
					//重置责任范围
					reloadManager();
				}
			}
		}
	});

	$("#innerTable").yxeditgrid({
		url: '?model=finance_invother_invotherdetail&action=listJson',
		objName: 'invother[items]',
		title: '发票明细',
		param: {'mainId': $("#id").val()},
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '发票名目',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '发票名目编码',
			name: 'productCode',
			type: 'hidden'
		}, {
			display: '发票名目',
			name: 'productName',
			validation: {
				required: true
			},
			tclass: 'txt'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '单价',
			name: 'price',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"), 'price');
				}
			}
		}, {
			display: '含税单价',
			name: 'taxPrice',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"), 'taxPrice');
				}
			}
		}, {
			display: '金额',
			name: 'amount',
			validation: {
				required: true
			},
			type: 'money',
			event: {
				blur: function() {
					//初始化单价和数量
					initNumAndPrice($(this).data("rowNum"), 'amount');
					countAccount($(this).data("rowNum"));
					countForm();
				}
			}
		}, {
			display: '税额',
			name: 'assessment',
			type: 'money',
			event: {
				blur: function() {
					countAccount($(this).data("rowNum"));
					countForm();
				}
			}
		}, {
			display: '价税合计',
			name: 'allCount',
			type: 'money',
			event: {
				blur: function() {
					//初始化单价和数量
					initNumAndPrice($(this).data("rowNum"), 'allCount');
					countForm();
				}
			}
		}, {
			display: '源单编号',
			name: 'objCode',
			readonly: true,
			tclass: 'readOnlyTxtNormal'
		}, {
			display: '源单id',
			name: 'objId',
			type: 'hidden'
		}, {
			display: '源单类型',
			name: 'objType',
			type: 'hidden'
		}],
		event: {
			'reloadData': function() {
				$("#innerTable").find("tbody").after("<tr class='tr_count'><td colspan='3'>合计</td>"
				+ "<td><input type='text' class='readOnlyTxtShortCount' id='listNumber' value='"
				+ $("#formNumber").val()
				+ "' readonly='readonly'/></td>"
				+ "<td colspan='2'></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAmount' value='"
				+ moneyFormat2($("#allAmount").val())
				+ "' readonly='readonly'/></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAssessment' value='"
				+ moneyFormat2($("#formAssessment").val())
				+ "' readonly='readonly'/></td>"
				+ "<td><input type='text' class='readOnlyTxtMiddleCount' id='listCount' value='"
				+ moneyFormat2($("#formCount").val())
				+ "' readonly='readonly'/></td>"
				+ "<td></td></tr>");
			},
			removeRow: function(t, rowNum, rowData) {
				countForm();
			},
			clickAddRow: function (e, rowNum, g) {
				//带出源单信息
				if($('#sourceType').val() != ''){
					g.setRowColValue(rowNum, "objCode", $('#menuNo').val());
					g.setRowColValue(rowNum, "objId", $('#sourceId').val());
					g.setRowColValue(rowNum, "objType", $('#sourceType').val());
				}
	        }
		}
	});

	// 获取源单类型
	var sourceType = $("#sourceType").val();

	if (sourceType != "") {
		$("#invType").hide();
		$("#invTypeCN").show();

		$("#taxRate").attr('class', 'readOnlyTxtNormal').attr('readonly', 'readonly');
	} else {
		// 发票事件绑定
		$("#invType").bind("change", invoiceTypeChange);

		// 税率时间绑定
		$("#taxRate").bind('blur', countAll);
	}

	//update chenrf 外包合同不需要费用分摊
	if ((sourceType == 'YFQTYD02' || sourceType == '其他合同') && $("#isShare").val() == "1") {
		//显示费用分摊明细
		$("#shareGrid").costShareGrid({
			objName: 'invother[costshare]',
			url: "?model=finance_cost_costshare&action=hookJson",
			type: "hook",
			showSelectWin: true,
			param: {objType: 2, hookId: $("#id").val()},
			isShowCountRow: true,
			countKey: getMoneyKey()
		});
	}
});